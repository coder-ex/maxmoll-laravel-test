<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ValidException;
use App\Http\Controllers\Controller;
use App\Services\Shop\ProductServices;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class ProductController extends Controller
{

    public function __construct(private ProductServices $service)
    {
    }

    public function create(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'name' => 'required|string|min:3|max:255',
                'price' => 'required|numeric',
                'stocks' => 'required|integer',
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
            $data = $this->service->createProduct(...$validated);
            return response()->json(['success' => 'OK', 'user' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function read(int $id)
    {
        if (gettype($id) !== 'integer') {
            return response()->json(['message' => 'id товара должно быть число'], 422);
        }

        try {
            $data = $this->service->readProduct($id);
            return response()->json(['success' => 'OK', 'user' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'id'=> 'required|integer',
                'name' => 'required|string|min:3|max:255',
                'price' => 'required|numeric',
                'stocks' => 'required|integer',
            ], [
                'required' => 'Необходимо заполнить поле :attribute.'
            ]);
        } catch (ValidationException $e) {
            return ValidException::Unvalidate($e, isset($e->status) ? $e->status : 401);
        }

        try {
            $data = $this->service->updateProduct(...$validated);
            return response()->json(['success' => 'OK', 'user' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function delete(int $id)
    {
        if (gettype($id) !== 'integer') {
            return response()->json(['message' => 'id пользователя должно быть число'], 422);
        }

        try {
            $data = $this->service->deleteProduct($id);
            return response()->json(['success' => 'OK', $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function getAll()
    {
        return response()->json(['success' => 'OK', 'products' => $this->service->getUsers()], 200);
    }
}
