<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'sku',
        'price',
        'sale_price',
        'stock',
        'size',
        'color',
        'material',
        'image',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->slug = $product->slug ?? Str::slug($product->name);
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }
}
