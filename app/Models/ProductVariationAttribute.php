<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationAttribute extends Model
{
    protected $fillable = [
        'variation_id', 'attribute_name', 'attribute_value', 'attribute_display', 'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    // Relationships
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
