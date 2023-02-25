<?php

namespace App\Services\Shop;

use App\Models\Shop\Product;
use App\Repositories\Repository;
use Exception;

class ProductServices
{
    public function __construct(private Repository $repository)
    {
    }
    /**
     * создание товара
     * string $name наименование товара
     * float $price стоимость одной единицы товара
     * int $stocks остаток на складе
     */
    public function createProduct(string $name, float $price, int $stocks): mixed
    {
        $candidate = Product::where('name', $name)->first();
        if ($candidate) {
            throw new Exception("товар [ {$name} ] уже сущестует", 422);
        }
        //---
        return $this->repository->saveItem(Product::class, ['name' => $name, 'price' => $price, 'stocks' => $stocks]);
    }

    /**
     * получение товара
     * int $id идентификатор товара
     */
    public function readProduct(int $id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            throw new Exception("товар с id [ {$id} ] не сущестует", 422);
        }
        //---
        return $product;
    }

    /**
     * редактирование товара по id
     * int $id идентификатор товара
     * string $name наименование товара
     * float $price стоимость одной единицы товара
     * int $stocks остаток на складе
     */
    public function updateProduct(int $id, string $name, float $price, int $stock)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            throw new Exception("товар с id [ {$id} ] не сущестует", 422);
        }

        $item = [];
        if ($product->name !== $name) {
            $item['name'] = $name;
        }
        $item['price'] = $price;
        $item['stock'] = $stock;
        //---
        return $this->repository->updateItem(Product::class, $id, $item) ? ['message' => 'данные обновлены'] : ['message' => 'обновление не требуется'];
    }

    /**
     * удаление товара по id
     * int $id идентификатор товара
     */
    public function deleteProduct(int $id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            throw new Exception("товар с id [ {$id} ] не сущестует", 422);
        }

        $product->delete();
        //---
        return ['message' => "товар с id [ {$id} ] удален"];
    }

    /**
     * показать всех товаров
     */
    public function getUsers()
    {
        return Product::all();
    }
}
