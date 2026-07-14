<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceProductVariationAttribute extends Model
{
    protected $table = 'marketplace_product_variation_attributes';

    protected $fillable = [
        'variation_id', 'attribute_name', 'attribute_value', 'attribute_display', 'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function variation()
    {
        return $this->belongsTo(MarketplaceProductVariation::class, 'variation_id');
    }
}
