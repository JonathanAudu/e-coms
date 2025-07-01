<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'post_code',
        'city',
        'country',
        'state',
        'notes',
        'quantity',
        'total',
        'shipping_fee',
        'payment_method',
        'payment_status',
        'status',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
