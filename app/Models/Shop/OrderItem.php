<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'order_items';
    protected $connection = 'mysql';

    protected $fillable = [
        'order_id', 'product_id', 'count', 'discount', 'cost'
    ];

    // protected $hidden = [
    //     'id',
    // ];

    /**
     * продукт (товар) связанный с элементом заказа
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * заказ связанный с элементом заказа
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
