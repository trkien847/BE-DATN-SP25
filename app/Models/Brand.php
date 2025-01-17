<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
}
