<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceProduct;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $equipment = MarketplaceCategory::firstOrCreate(
            ['slug' => 'dj-equipment'],
            [
                'name' => 'DJ Equipment',
                'description' => 'Professional DJ gear and controllers',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $services = MarketplaceCategory::firstOrCreate(
            ['slug' => 'digital-services'],
            [
                'name' => 'Digital Services',
                'description' => 'Digital tools and downloads for DJs and MCs',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        MarketplaceProduct::firstOrCreate(
            ['slug' => 'portable-dj-controller'],
            [
                'name' => 'Portable DJ Controller',
                'description' => 'Compact 2-channel DJ controller ideal for mobile sets and practice sessions.',
                'short_description' => 'Compact 2-channel controller',
                'price' => 249.99,
                'sale_price' => 219.99,
                'sku' => 'MKT-DJ-CTL-001',
                'stock' => 20,
                'category_id' => $equipment->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 1,
            ]
        );

        MarketplaceProduct::firstOrCreate(
            ['slug' => 'studio-monitor-pair'],
            [
                'name' => 'Studio Monitor Pair',
                'description' => 'Pair of near-field studio monitors for accurate mix checks and home studio setups.',
                'short_description' => 'Near-field studio monitors',
                'price' => 349.99,
                'sku' => 'MKT-MON-001',
                'stock' => 12,
                'category_id' => $equipment->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => false,
                'sort_order' => 2,
            ]
        );

        MarketplaceProduct::firstOrCreate(
            ['slug' => 'sample-pack-starter'],
            [
                'name' => 'Sample Pack Starter',
                'description' => 'Curated digital sample pack with loops, one-shots, and transition FX for DJ sets.',
                'short_description' => 'Digital DJ sample pack',
                'price' => 29.99,
                'sale_price' => 19.99,
                'sku' => 'MKT-SMP-001',
                'stock' => 100,
                'category_id' => $services->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 3,
            ]
        );
    }
}
