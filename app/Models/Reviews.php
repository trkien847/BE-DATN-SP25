<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'review_text',
        'is_active',
        'admin_reply',
        'replied_at',
        'is_auto'
    ];

    protected $dates = ['created_at', 'updated_at', 'replied_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function parent()
    {
        return $this->belongsTo(Reviews::class, 'parent_id');
    }
}
