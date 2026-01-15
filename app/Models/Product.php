<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 'sale_price',
        'sku', 'stock', 'category_id', 'category', 'image', 'gallery', 'product_type',
        'is_active', 'featured', 'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'gallery' => 'array',
            'is_active' => 'boolean',
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('sort_order');
    }

    public function defaultVariation()
    {
        return $this->hasOne(ProductVariation::class)->where('is_default', true);
    }

    // Backward compatibility - get category name
    public function getCategoryNameAttribute()
    {
        if ($this->productCategory) {
            return $this->productCategory->full_name;
        }
        return $this->category ?? 'Uncategorized';
    }

    // Helper methods
    public function isVariable()
    {
        return $this->product_type === 'variable';
    }

    public function isSimple()
    {
        return $this->product_type === 'simple';
    }

    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->gallery);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/shop-img-001.png'); // Default product image
    }

    public function isInStock()
    {
        if ($this->isVariable()) {
            return $this->variations()->where('stock', '>', 0)->where('is_active', true)->exists();
        }
        return $this->stock > 0;
    }

    public function getTotalStockAttribute()
    {
        if ($this->isVariable()) {
            return $this->variations()->where('is_active', true)->sum('stock');
        }
        return $this->stock;
    }

    public function getHasStockAttribute()
    {
        return $this->isInStock();
    }

    public function getMinPriceAttribute()
    {
        if ($this->isVariable() && $this->variations()->count() > 0) {
            $minPrice = $this->variations()->where('is_active', true)->min('price');
            return $minPrice ?? $this->price;
        }
        return $this->current_price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
