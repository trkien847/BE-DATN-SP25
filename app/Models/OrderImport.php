<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'order_name',
        'notes',
        'import_id'
    ];

    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}