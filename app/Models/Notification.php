<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeUserOrSystem($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        });
    }
}