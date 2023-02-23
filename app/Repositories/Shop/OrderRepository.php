<?php

namespace App\Repositories\Shop;

use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use ErrorException;
use Exception;

class OrderRepository extends Repository
{
    /**
     * Увеличение значения столбца на заданную величину
     *
     * @param string $class модель
     * @param int $id идентификатор
     * @param string $column колонка в таблице для увеличения
     * @param float|int $amount значение для увеличения
     * @param array $extra дополнительный массив значений для обновления (если требуется)
     * @return int
     *
     * @throws Exception
     */
    public function incrementItem(string $class, int $id, string $column, float|int $amount, array $extra = []): mixed
    {
        $res = null;
        DB::beginTransaction();
        try {
            $res = $class::where('id', $id)->increment($column, $amount, $extra);

            DB::commit();
        } catch (Exception | ErrorException $e) {
            DB::rollback();
            throw $e;
        }
        //---
        return $res;
    }
}
