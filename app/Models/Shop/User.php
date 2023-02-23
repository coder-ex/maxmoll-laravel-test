<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'users';
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
    ];

    // protected $hidden = [
    //     'id',
    // ];

    /**
     * заказы связанные с пользователем
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
