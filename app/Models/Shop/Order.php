<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $connection = 'mysql';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'completed_at';


    protected $fillable = [
        'customer', 'phone', 'user_id', 'type', 'status',   // 'completed_at', 'created_at'
    ];


    // protected $hidden = [
    //     'id',
    // ];

    /**
     * получить пользователя создавшего заказ
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * элементы заказа связанные с заказом
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
