<?php

namespace App\Services\Shop;

use App\Models\Shop\Product;
use App\Repositories\Repository;

class OrderItemServices
{
    /**
     * создание элементов заказа
     * @param array $products массив с элементами заказа
     */
    public function createItems(array $products): mixed
    {
        $status_orders = false;
        $cnt = 0;
        foreach ($products as $key => $value) {
            $product = Product::where('name', $value['name'])->first();

            //--- проверим наличие товара на складе
            if (!$product) {
                $orders[$key]['name'] = ["не корректное наименование товара [ {$value['name']} ]"];
                continue;
            } else {
                $orders[$key]['id'] = $product->id;
                $orders[$key]['name'] = $product->name;
            }

            //--- проверим количество на складе
            $stock = $product->stock - $value['count'];
            if ($stock < 0) {
                $orders[$key]['message'] = ["товар [ {$value['name']} ] доступно для заказа {$product->stock}"];
                if (!$status_orders) $status_orders = true;
            }

            //--- расчитаем cost
            $cost = $product->price - ($product->price * ($value['discount'] / 100));

            //--- запишем итоговые данные по элементу товара в массив orders
            $orders[$key]['item'] = [
                'stock' => ($stock < 0) ? $product->stock : $stock,
                'cost' => $cost,
                'count' => $value['count'],
                'discount' => $value['discount'],
            ];

            $cnt++;     // посчитаем позиции товара для фиксации
        }
        //---
        return [$orders, $status_orders, $cnt];
    }
}
