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
        'employee_evidence',
        'customer_confirmation',
    ];

    public function orderStatus()
    {
        return $this->belongsTo(OrderOrderStatus::class, 'id', 'order_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}
