<?php

namespace App\Services\Shop;

use App\Models\Shop\User;
use App\Repositories\Repository;
use Exception;

class UserServices
{
    public function __construct(private Repository $repository)
    {
    }
    /**
     * создание пользователя
     *  string $name имя пользователя
     */
    public function createUser(string $name): mixed
    {
        $candidate = User::where('name', $name)->first();
        if ($candidate) {
            throw new Exception("пользователь [ {$name} ] уже сущестует", 422);
        }
        //---
        return $this->repository->saveItem(User::class, ['name' => $name,]);
    }

    /**
     * получение пользователя
     * int $id идентификатор пользователя
     */
    public function readUser(int $id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            throw new Exception("пользователь с id [ {$id} ] не сущестует", 422);
        }
        //---
        return $user;
    }

    /**
     * редактирование пользователя по id
     * int $id идентификатор пользователя
     * string $name новое имя пользователя
     */
    public function updateUser(int $id, string $name)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            throw new Exception("пользователь с id [ {$id} ] не сущестует", 422);
        }
        //---
        return $this->repository->updateItem(User::class, $id, ['name' => $name,]) ? ['message' => 'данные обновлены'] : ['message' => 'обновление не требуется'];
    }

    /**
     * удаление пользователя по id
     * int $id идентификатор пользователя
     */
    public function deleteUser(int $id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            throw new Exception("пользователь с id [ {$id} ] не сущестует", 422);
        }

        $user->delete();
        //---
        return ['message' => "пользователь с id [ {$id} ] удален"];
    }

    /**
     * показать всех пользователей
     */
    public function getUsers()
    {
        return User::all();
    }
}
