<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceProductVariation extends Model
{
    protected $table = 'marketplace_product_variations';

    protected $fillable = [
        'marketplace_product_id', 'sku', 'price', 'sale_price', 'stock', 'image',
        'sort_order', 'is_default', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'sort_order' => 'integer',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(MarketplaceProduct::class, 'marketplace_product_id');
    }

    public function attributes()
    {
        return $this->hasMany(MarketplaceProductVariationAttribute::class, 'variation_id')->orderBy('sort_order');
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price ?? $this->product->price;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->product->image_url;
    }

    public function getVariationNameAttribute()
    {
        if ($this->relationLoaded('attributes')) {
            $variationAttributes = $this->getRelation('attributes');
        } else {
            $variationAttributes = $this->attributes()->get();
        }

        if ($variationAttributes->isEmpty()) {
            return 'Default';
        }

        $attrs = $variationAttributes->map(function ($attr) {
            return $attr->attribute_value;
        })->implode(' - ');

        return $attrs ?: 'Default';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }
}
