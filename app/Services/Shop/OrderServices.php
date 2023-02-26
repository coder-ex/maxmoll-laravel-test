<?php

namespace App\Services\Shop;

use App\Helpers\TypeOrder;
use App\Helpers\TypeStatus;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\Shop\User;
use App\Repositories\Shop\OrderRepository;
use Illuminate\Support\Arr;
use Exception;

class OrderServices
{
    public function __construct(
        private OrderItemServices $itemService,
        private OrderRepository $repository,
    ) {
    }
    /**
     * создание заказа
     * @param string $customer имя клиента
     * @param string $phone телефон клиента
     * @param array $products массив с данными по заказу
     */
    public function createOrder(string $customer, string $phone, array $products): mixed
    {
        $candidate = Order::where('customer', $customer)
            ->where('phone', $phone)
            ->where('status', 'active')
            ->first();

        //--- если есть заказ и он не пуст, предложим перейти в редактирование заказа (предусмотреть код ошибки для работы на front-end)
        if ($candidate) {
            if (OrderItem::where('order_id', $candidate->id)->first()) {
                return [
                    'message' => "есть активный не завершенный заказ с id [ {$candidate->id} ], перейдите в редактирование заказа и завершите его",
                    'order' => $candidate
                ];
            }
        }

        //--- формируем элементы заказа для order_items
        [$orders, $status_orders, $cnt] = $this->itemService->createItems($products);

        //--- $cnt == 0 то выходим (предусмотреть код ошибки для работы на front-end)
        if ($cnt == 0) {
            return [
                'message' => 'заказанного товара в каталоге склада нет, укажите корректно наименование товара',
                'order' => $orders
            ];
        }

        //--- если заказа нет, то создадим, если есть пустой заказ с тем же покупателем, то новый не создаем
        $order_id = $candidate ? $candidate->id : $this->repository->saveItem(Order::class, [
            'customer' => $customer,
            'phone' => $phone,
            'user_id' => Arr::random(User::all()->toArray())['id'],             // назначим рандомно менеджера, потом сделать через отложенное событи
            'type' => TypeOrder::ONLINE->value,
            //'status' => ($status_orders) ? TypeStatus::ACTIVE->value : TypeStatus::COMPLETED->value
            'status' => TypeStatus::ACTIVE->value
        ])?->id;

        //--- обновим данные по товару на складе
        $dataDB = [];
        foreach ($orders as $unit) {
            if (!isset($unit['id'])) continue;
            if (!isset($unit['item'])) continue;

            $this->repository->updateItem(Product::class, $unit['id'], ['stock' => $unit['item']['stock']]);

            $dataDB[] = [
                'order_id' => $order_id,
                'product_id' => $unit['id'],
                'count' => isset($unit['message']) ? 0 : $unit['item']['count'],
                'discount' => $unit['item']['discount'],
                'cost' => $unit['item']['cost'] * (isset($unit['message']) ? 0 : $unit['item']['count']),
            ];
        }

        //--- пишем данные в таблицу order_items
        foreach (array_chunk($dataDB, 500) as $chunk) {
            $this->repository->upsertData(OrderItem::class, $chunk, ['order_id', 'product_id']);
        }

        //---
        return [
            //'status' => ($status_orders) ? TypeStatus::ACTIVE->value : TypeStatus::COMPLETED->value,
            'status' => TypeStatus::ACTIVE->value,
            'message' => '',
            'order' => $dataDB
        ];
    }

    /**
     * получение заказа
     * @param int $id идентификатор товара
     */
    public function readOrder(int $id): mixed
    {
        $order = Order::where('id', $id)->first();
        if (!$order) {
            throw new Exception("заказ с id [ {$id} ] не сущестует", 422);
        }
        //---
        return $order->load('user', 'orderItems', 'orderItems.product');
    }

    /**
     * редактирование заказа по id
     * @param int $id идентификатор заказа
     * @param ?string $type необязательный аргумент
     * @param ?string $status необязательный аргумент статуса заказа
     * @param ?string $manager необязательный аргумент имя менеджера (User::class->name)
     * @param string $customer имя клиента
     * @param string $phone телефон клиента
     * @param array $products массив с данными по заказу
     */
    public function updateOrder(int $id, ?string $type, ?string $status, ?string $manager, string $customer, string $phone, array $products): array
    {
        $message = [];

        $order = Order::where('customer', $customer)
            ->where('phone', $phone)
            ->where('status', 'active')
            ->first();

        //--- если заказа нет то выбросим исключение (предусмотреть код ошибки для работы на front-end)
        if (!$order) {
            throw new Exception("заказ с id [ {$id} ] не найден", 422);
        }

        //--- обновление $type, $status, $manager
        {
            $update = [];
            ['message' => $msg, 'value' => $value] = $this->checkType($type, $order->type);
            if ($msg !== '') $message[] = $msg;
            $update['type'] = $value;
            ['message' => $msg, 'value' => $value] = $this->checkStatus($status, $order->status);
            if ($msg !== '') $message[] = $msg;
            $update['status'] = $value;
            ['message' => $msg, 'value' => $value] = $this->checkManager($manager, $order->user_id);
            if ($msg !== '') $message[] = $msg;
            $update['user_id'] = $value;

            $this->repository->updateItem(Order::class, $id, $update);
        }

        //--- получаем массив элементов заказа
        $items_old = $order->orderItems;

        //--- делаем откат товара на склад по всем элементам items_old
        foreach ($items_old as $unit) {
            if ($unit->count === 0) continue;

            $productDB = $unit->product;
            $this->repository->updateItem(Product::class, $productDB->id, ['stock' => $productDB->stock + $unit->count]);
        }

        //--- создаем новый массив элементов заказа и помечаем как items_new
        [$items_new, $status_orders, $cnt] = $this->itemService->createItems($products);

        //--- далее как при создании заказа за исключением создания заказа в таблице orders
        //--- обновим данные по товару на складе
        $dataDB = [];
        foreach ($items_new as $unit) {
            if (!isset($unit['id'])) continue;
            if (!isset($unit['item'])) continue;

            $this->repository->updateItem(Product::class, $unit['id'], ['stock' => $unit['item']['stock']]);

            $dataDB[] = [
                'order_id' => $order->id,   // $id == $order->id
                'product_id' => $unit['id'],
                'count' => isset($unit['message']) ? 0 : $unit['item']['count'],
                'discount' => $unit['item']['discount'],
                'cost' => $unit['item']['cost'] * (isset($unit['message']) ? 0 : $unit['item']['count']),
            ];
        }

        //--- пишем данные в таблицу order_items
        foreach (array_chunk($dataDB, 500) as $chunk) {
            $this->repository->upsertData(OrderItem::class, $chunk, ['order_id', 'product_id'], ['count', 'discount', 'cost']);
        }

        //--- сравниваем два массива и удаляем из таблицы order_items элементы, которых нет в массиве items_new
        $items_full = $order->orderItems;
        $flag = false;
        $del_id = [];
        foreach ($items_full as $v) {
            if ($flag) $flag = false;

            foreach ($items_new as $new) {
                $product_id = Product::where('id', $new['id'])->first()?->id;

                if ($product_id == $v->product_id) {
                    $flag = true;
                    break;
                }
            }

            //--- если флаг не тронут, значит элемента быть не должно, запомним id для удаления
            if (!$flag) {
                $del_id[] = $v->id;
            }
        }

        if (count($del_id) > 0) {
            $this->repository->destroyData(OrderItem::class, $del_id);
        }

        //---
        return [
            'message' => $message,
            'order' => $order->load('user', 'orderItems', 'orderItems.product')
        ];
    }

    /**
     * отмена заказа по id
     * int $id идентификатор заказа
     */
    public function cancelledOrder(int $id)
    {
        $message = [];

        $order = Order::where('id', $id)->first();

        //--- если заказа нет то выбросим исключение (предусмотреть код ошибки для работы на front-end)
        if (!$order) {
            throw new Exception("заказ с id [ {$id} ] не найден", 422);
        }

        //--- сверить состояние status, если уже canselled, то ни чего не делаем
        if ($order->status === TypeStatus::CANCELLED->value) {
            return [
                'status' => TypeStatus::CANCELLED->value,
                'message' => "заказ уже в статусе отмененных, дополнительно переводить не требуется",
                'order' => $order->load('user', 'orderItems', 'orderItems.product')
            ];
        }

        //--- вытаскиваем из заказа все позиции товара и возвращаем на склад
        foreach ($order->orderItems as $item) {
            $this->repository->incrementItem(Product::class, $item->product_id, 'stock', $item->count);
        }

        $this->repository->updateItem(Order::class, $order->id, ['status' => TypeStatus::CANCELLED->value]);
        $order = Order::where('id', $id)->first();
        //---
        return ['message' => "товар с id [ {$id} ] отменен", 'order' => $order->loadMissing('user', 'orderItems', 'orderItems.product')];
    }

    /**
     * возврат отмены заказа по id
     * int $id идентификатор заказа
     */
    public function refundCancellation(int $id)
    {
        $message = [];

        //--- ищем только со статусом canselled
        $order = Order::where('id', $id)
            ->where('status', TypeStatus::CANCELLED->value)
            ->first();

        //--- если заказа нет то выбросим исключение (предусмотреть код ошибки для работы на front-end)
        if (!$order) {
            throw new Exception("заказ с id [ {$id} ] не найден", 422);
        }

        //--- вытаскиваем из заказа все позиции товара и пишем в массив [ products.id, products.stock ]
        //$order->loadMissing('user', 'orderItems', 'orderItems.product');
        $products = [];
        foreach ($order->orderItems as $item) {
            $products[] = [
                'name' => $item->product->name,
                'count' => $item->count,
                'discount' => $item->discount,
            ];
        }

        //--- создаем новый массив элементов заказа и помечаем как items_new
        [$items_new, $status_orders, $cnt] = $this->itemService->createItems($products);

        //--- далее как при создании заказа за исключением создания заказа в таблице orders
        //--- обновим данные по товару на складе
        $dataDB = [];
        foreach ($items_new as $unit) {
            if (!isset($unit['id'])) continue;
            if (!isset($unit['item'])) continue;

            $this->repository->updateItem(Product::class, $unit['id'], ['stock' => $unit['item']['stock']]);

            $dataDB[] = [
                'order_id' => $order->id,   // $id == $order->id
                'product_id' => $unit['id'],
                'count' => isset($unit['message']) ? 0 : $unit['item']['count'],
                'discount' => $unit['item']['discount'],
                'cost' => $unit['item']['cost'] * (isset($unit['message']) ? 0 : $unit['item']['count']),
            ];

            if (isset($unit['message'])) {
                $message[] = $unit['message'];
            }
        }

        //--- пишем данные в таблицу order_items
        foreach (array_chunk($dataDB, 500) as $chunk) {
            $this->repository->upsertData(OrderItem::class, $chunk, ['order_id', 'product_id'], ['count', 'discount', 'cost']);
        }


        $this->repository->updateItem(Order::class, $order->id, ['status' => TypeStatus::ACTIVE->value]);
        $order = Order::where('id', $id)->first();

        //---
        return [
            'message' => $message,
            'order' => $order->loadMissing('user', 'orderItems', 'orderItems.product')
        ];
    }

    /**
     * показать всех товаров
     */
    public function orderAll()
    {
        $order = Order::all();
        return $order->loadMissing('user', 'orderItems', 'orderItems.product');
    }

    /**
     * проверка на корректность  type
     * @param string $value значение для проверки
     * @param string $oldValue значение из заказа
     */
    protected function checkType(?string $value, string $oldValue): array
    {
        return match ($value) {
            TypeOrder::OFFLINE->value => ['message' => '', 'value' => 'offline'],
            TypeOrder::ONLINE->value => ['message' => '', 'value' => 'online'],
            default => ['message' => "type [ {$value} ] не определен, используем заданный в заказе [ {$oldValue} ]", 'value' => $oldValue],
        };
    }

    /**
     * проверка на корректность status
     * @param string $value значение для проверки
     * @param string $oldValue значение из заказа
     */
    protected function checkStatus(?string $value, string $oldValue): array
    {
        return match ($value) {
            TypeStatus::ACTIVE->value => ['message' => '', 'value' => 'active'],
            TypeStatus::CANCELLED->value => ['message' => '', 'value' => 'canselled'],
            TypeStatus::COMPLETED->value => ['message' => '', 'value' => 'completed'],
            default => ['message' => "status [ {$value} ] не определен, используем заданный в заказе [ {$oldValue} ]", 'value' => $oldValue],
        };
    }

    /**
     * проверка на корректность manager
     * @param string $value значение для проверки
     * @param int $oldValue значение из заказа
     */
    protected function checkManager(?string $value, int $oldValue): array
    {
        if (isset($value)) {
            $user = User::where('name', $value)->first();
            if ($user) {
                return ['message' => '', 'value' => $user->id];
            }
        }

        $name = User::where('id', $oldValue)->first()?->name;
        return ['message' => "менеджер [ {$value} ] не определен, используем заданный в заказе [ {$name} ]", 'value' => $oldValue];
    }
}
