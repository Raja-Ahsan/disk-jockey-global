<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketplaceCategory extends Model
{
    use SoftDeletes;

    protected $table = 'marketplace_categories';

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'parent_id', 'sort_order', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MarketplaceCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(MarketplaceProduct::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSubcategories($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function isSubcategory()
    {
        return $this->parent_id !== null;
    }

    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
