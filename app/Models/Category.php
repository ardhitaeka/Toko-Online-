<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value
                ? (str_starts_with($value, 'http') ? $value : asset('storage/' . $value))
                : null,
        );
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
