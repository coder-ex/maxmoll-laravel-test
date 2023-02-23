<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'products';
    protected $connection = 'mysql';

    protected $fillable = [
        'name', 'price', 'stocks',
    ];

    // protected $hidden = [
    //     'id',
    // ];

    /**
     * элементы заказа связанные с продуктом
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}
