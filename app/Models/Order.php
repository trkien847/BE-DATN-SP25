<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'phone_number',
        'email',
        'fullname',
        'address',
        'total_amount',
        'is_paid',
        'coupon_id',
        'coupon_code',
        'coupon_description',
        'coupon_discount_type',
        'coupon_discount_value',
    ];

    public function orderStatuses() 
    {
        return $this->hasMany(OrderOrderStatus::class, 'order_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function latestOrderStatus()
    {
        return $this->hasOneThrough(
            OrderStatus::class,
            OrderOrderStatus::class,
            'order_id',         
            'id',             
            'id',             
            'order_status_id' 
        )->latest('created_at'); 
    }

    public function orderStatusDetails()
    {
        return $this->hasOneThrough(
            OrderStatus::class,
            OrderOrderStatus::class,
            'order_id',         
            'id',               
            'id',               
            'order_status_id'   
        );
    }

}