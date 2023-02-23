<?php

namespace App\Repositories;

use App\Models\Shop\User;
use Illuminate\Support\Facades\DB;
use ErrorException;
use Exception;

class Repository
{
    /**
     * запись элемента
     *
     * @param string $class класс модели
     * @param array $item элемент для записи в БД
     */
    public function saveItem(string $class, array $item)
    {
        $res = null;

        DB::beginTransaction();
        try {
            $res = $class::create($item);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
        //---
        return $res;
    }

    /**
     * обновление элемента
     *
     * @param string $class класс модели
     * @param int $id идентификатор записи для поиска в таблице обновляемого элемента
     * @param array $item элемент для записи в БД
     */
    public function updateItem(string $class, int $id, array $item): bool
    {
        $res = null;

        DB::beginTransaction();
        try {
            $res = $class::where('id', $id)->update($item);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
        //---
        return $res ? true : false;
    }

    /**
     * вставка или обновление элемента
     *
     * @param string $class класс модели
     * @param array $data массив элементов для вставки (упакованы в общий массив)
     * @param  array $uniqueBy массив полей однозначно идентификацирующих запись
     * @param  array|null $update по умолчанию обновить все поля
     * @return void
     */
    public function upsertData(string $class, array $data, array $uniqueBy, $update = null)
    {
        $res = null;

        DB::beginTransaction();
        try {
            $res = $class::upsert(
                $data,          // например [ [ 'order'=>'', 'product_id'=>'', 'count'=>'', 'discount'=>'', 'cost'=>'' ] ]
                $uniqueBy,      // например [ 'id']
                $update         // обновим [ поля для обновления, если запись существует ]
            );

            DB::commit();
        } catch (Exception | ErrorException $e) {
            DB::rollback();
            throw $e;
        }
        //---
        return $res;
    }

    /**
     * массовое удаление моделей
     * @param array $data массив идентификаторов моделей для удаления
     */
    public function destroyData(string $class, array $data)
    {
        $res = null;
        DB::beginTransaction();
        try {
            $res = $class::destroy($data);

            DB::commit();
        } catch (Exception | ErrorException $e) {
            DB::rollback();
            throw $e;
        }
        //---
        return $res;
    }
}
