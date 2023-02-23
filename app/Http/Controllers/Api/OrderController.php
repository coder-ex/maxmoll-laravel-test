<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ValidException;
use App\Http\Controllers\Controller;
use App\Services\Shop\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderController extends Controller
{
    public function __construct(private OrderServices $service)
    {
    }

    public function create(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'customer' => 'required|string|min:3|max:255',
                'phone' => 'required|string|min:3|max:255',
                'products' => 'required|array|min:1',

                'products.*.name' => 'required|string|min:3|max:255',
                'products.*.count' => 'required|integer',
                'products.*.discount' => 'required|numeric',
            ], [
                'required' => 'Необходимо заполнить поле :attribute.'
                // 'name.required|string|min:3|max:255' => 'не корректно указано наименование товара',
                // 'price.required|numeric' => 'не корректно указана цена товара',
                // 'stocks.required' => 'не корректны остатки товара',
            ]);
        } catch (ValidationException $e) {
            return ValidException::Unvalidate($e, isset($e->status) ? $e->status : 401);
        }

        try {
            //['customers'=>$customers, 'phone'=>$phone, 'products'=>$products] = $validated;
            $data = $this->service->createOrder(...$validated);
            return response()->json(['success' => 'OK', ...$data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function read(int $id)
    {
        if (gettype($id) !== 'integer') {
            return response()->json(['message' => 'id заказа должно быть число'], 422);
        }

        try {
            $data = $this->service->readOrder($id);
            return response()->json(['success' => 'OK', 'order' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'id' => 'required|integer',
                'type' => 'string|min:3|max:255',
                'status' => 'string|min:3|max:255',
                'manager' => 'string|min:3|max:255',
                'customer' => 'required|string|min:3|max:255',
                'phone' => 'required|string|min:3|max:255',
                'products' => 'required|array|min:1',

                'products.*.name' => 'required|string|min:3|max:255',
                'products.*.count' => 'required|integer',
                'products.*.discount' => 'required|numeric',
            ], [
                'required' => 'Необходимо заполнить поле :attribute.'
            ]);
        } catch (ValidationException $e) {
            return ValidException::Unvalidate($e, isset($e->status) ? $e->status : 401);
        }

        try {
            //--- костыль из-за входных параметров в updateOrder
            if(!isset($validated['type'])) $validated['type'] = null;
            if(!isset($validated['status'])) $validated['status'] = null;
            if(!isset($validated['manager'])) $validated['manager'] = null;

            $data = $this->service->updateOrder(...$validated);
            return response()->json(['success' => 'OK', ...$data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function cancelled(int $id)
    {
        if (gettype($id) !== 'integer') {
            return response()->json(['message' => 'id пользователя должно быть число'], 422);
        }

        try {
            $data = $this->service->cancelledOrder($id);
            return response()->json(['success' => 'OK', $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function recancelled(int $id)
    {
        if (gettype($id) !== 'integer') {
            return response()->json(['message' => 'id пользователя должно быть число'], 422);
        }

        try {
            $data = $this->service->refundCancellation($id);
            return response()->json(['success' => 'OK', $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function getAll()
    {
        return response()->json(['success' => 'OK', 'products' => $this->service->orderAll()], 200);
    }
}
