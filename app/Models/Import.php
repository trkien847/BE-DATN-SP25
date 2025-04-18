<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_code',
        'import_date',
        'user_id',
        'supplier_id',
        'is_confirmed',
        'total_quantity',
        'total_price',
        'proof_image'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(ImportProduct::class, 'import_id');
    }
    
    public function importProducts()
    {
        return $this->hasMany(ImportProduct::class);
    }

    public function orderImport()
    {
        return $this->hasOne(OrderImport::class);
    }
    
}
