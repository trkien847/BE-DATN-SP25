<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'imported_by', 'imported_at', 'is_active'];

    protected $dates = ['imported_at'];

    public function details()
    {
        return $this->hasMany(ProductImportDetail::class, 'import_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
