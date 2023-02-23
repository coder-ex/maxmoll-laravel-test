<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserException;
use App\Exceptions\ValidException;
use App\Http\Controllers\Controller;
use App\Services\Shop\UserServices;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class UserController extends Controller
{

    public function __construct(private UserServices $service)
    {
    }

    public function create(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'name' => 'required|string|min:3|max:255',
            ], [
                'required' => 'Необходимо заполнить поле :attribute.',
                'min:3|max:255'=>'Длина поля :attribute должна быть от 3 до 255 знаков'
            ]);
        } catch (ValidationException $e) {
            return ValidException::Unvalidate($e, isset($e->status) ? $e->status : 401);
        }

        try {
            $data = $this->service->createUser($validated['name']);
            return response()->json(['success' => 'OK', 'user' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function read(int $id)
    {
        if (gettype($id) !== 'integer') {
            return response()->json(['message' => 'id пользователя должно быть число'], 422);
        }

        try {
            $data = $this->service->readUser($id);
            return response()->json(['success' => 'OK', 'user' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required|integer',
                'name' => 'required|string|min:3|max:255',
            ], [
                'required' => 'Необходимо заполнить поле :attribute.',
                'min:3|max:255' => 'Длина поля :attribute должна быть от 3 до 255 знаков'
            ]);
        } catch (ValidationException $e) {
            return ValidException::Unvalidate($e, isset($e->status) ? $e->status : 401);
        }

        try {
            $data = $this->service->updateUser(...$request->toArray());
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
            $data = $this->service->deleteUser($id);
            return response()->json(['success' => 'OK', $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 'ERROR', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    public function getAll()
    {
        return response()->json(['success' => 'OK', 'users' => $this->service->getUsers()], 200);
    }
}
