<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOrderStatus extends Model
{
    use HasFactory;
    protected $table = 'order_order_status';

    protected $fillable = [
        'order_id',
        'order_status_id',
        'modified_by', 
        'note',
        'evidence',
        'customer_confirmation',
    ];

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }
}
