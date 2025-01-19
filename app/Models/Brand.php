<?php

namespace App\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'is_active',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
        
    ];

    /**
     * Boot the model and add event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate a slug when the name is changed
        static::saving(function ($brand) {
            if ($brand->isDirty('name')) { // Check if the 'name' field has been modified
                $slug = Str::slug($brand->name);
                $originalSlug = $slug;
                $count = 1;

                // Ensure the slug is unique
                while (static::where('slug', $slug)->where('id', '!=', $brand->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $brand->slug = $slug;
            }
        });
    }
   
    use SoftDeletes;
    protected $dates = ['deleted_at']; // Khai báo để Laravel hiểu rằng trường deleted_at chứa giá trị ngày giờ



}
