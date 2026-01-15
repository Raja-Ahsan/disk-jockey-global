<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationAttribute;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Main Categories
        $apparel = ProductCategory::firstOrCreate(
            ['slug' => 'apparel'],
            [
                'name' => 'Apparel',
                'description' => 'DJ and MC branded clothing and accessories',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $accessories = ProductCategory::firstOrCreate(
            ['slug' => 'accessories'],
            [
                'name' => 'Accessories',
                'description' => 'DJ equipment accessories and branded items',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $merchandise = ProductCategory::firstOrCreate(
            ['slug' => 'merchandise'],
            [
                'name' => 'Merchandise',
                'description' => 'Official Disk Jockey Global merchandise',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // Create Subcategories for Apparel
        $tShirts = ProductCategory::firstOrCreate(
            ['slug' => 't-shirts'],
            [
                'name' => 'T-Shirts',
                'parent_id' => $apparel->id,
                'description' => 'Comfortable and stylish DJ t-shirts',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $hoodies = ProductCategory::firstOrCreate(
            ['slug' => 'hoodies'],
            [
                'name' => 'Hoodies',
                'parent_id' => $apparel->id,
                'description' => 'Warm and cozy DJ hoodies',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $caps = ProductCategory::firstOrCreate(
            ['slug' => 'caps'],
            [
                'name' => 'Caps & Hats',
                'parent_id' => $apparel->id,
                'description' => 'Stylish DJ caps and hats',
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // Create Subcategories for Accessories
        $headphones = ProductCategory::firstOrCreate(
            ['slug' => 'headphones'],
            [
                'name' => 'Headphones',
                'parent_id' => $accessories->id,
                'description' => 'Professional DJ headphones',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $cases = ProductCategory::firstOrCreate(
            ['slug' => 'cases'],
            [
                'name' => 'Cases & Bags',
                'parent_id' => $accessories->id,
                'description' => 'Protective cases for DJ equipment',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // Create Simple Products
        $this->createSimpleProducts($tShirts, $hoodies, $caps, $headphones, $cases);

        // Create Variable Products
        $this->createVariableProducts($tShirts, $hoodies, $caps, $headphones);
    }

    private function createSimpleProducts($tShirts, $hoodies, $caps, $headphones, $cases)
    {
        // Simple T-Shirt
        $product1 = Product::firstOrCreate(
            ['slug' => 'dj-classic-t-shirt'],
            [
                'name' => 'DJ Classic T-Shirt',
                'description' => 'Premium quality cotton t-shirt with DJ logo. Perfect for casual wear or events.',
                'short_description' => 'Classic DJ branded t-shirt',
                'price' => 29.99,
                'sale_price' => 24.99,
                'sku' => 'TSH-DJ-001',
                'stock' => 50,
                'category_id' => $tShirts->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 1,
            ]
        );

        // Simple Cap
        $product2 = Product::firstOrCreate(
            ['slug' => 'dj-premium-cap'],
            [
                'name' => 'DJ Premium Cap',
                'description' => 'Adjustable premium cap with embroidered DJ logo. One size fits all.',
                'short_description' => 'Premium DJ cap with logo',
                'price' => 19.99,
                'sku' => 'CAP-DJ-001',
                'stock' => 30,
                'category_id' => $caps->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => false,
                'sort_order' => 2,
            ]
        );

        // Simple Headphones
        $product3 = Product::firstOrCreate(
            ['slug' => 'dj-pro-headphones'],
            [
                'name' => 'DJ Pro Headphones',
                'description' => 'Professional DJ headphones with excellent sound quality and noise isolation.',
                'short_description' => 'Professional DJ headphones',
                'price' => 89.99,
                'sale_price' => 79.99,
                'sku' => 'HP-DJ-001',
                'stock' => 25,
                'category_id' => $headphones->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 3,
            ]
        );

        // Simple Case
        $product4 = Product::firstOrCreate(
            ['slug' => 'dj-equipment-case'],
            [
                'name' => 'DJ Equipment Case',
                'description' => 'Durable hard case for protecting your DJ equipment during transport.',
                'short_description' => 'Protective case for DJ equipment',
                'price' => 49.99,
                'sku' => 'CASE-DJ-001',
                'stock' => 15,
                'category_id' => $cases->id,
                'product_type' => 'simple',
                'is_active' => true,
                'featured' => false,
                'sort_order' => 4,
            ]
        );
    }

    private function createVariableProducts($tShirts, $hoodies, $caps, $headphones)
    {
        // Variable T-Shirt with Color and Size variations
        $variableTShirt = Product::firstOrCreate(
            ['slug' => 'dj-premium-t-shirt-variable'],
            [
                'name' => 'DJ Premium T-Shirt',
                'description' => 'Premium quality t-shirt available in multiple colors and sizes. Made from 100% organic cotton.',
                'short_description' => 'Premium t-shirt with color and size options',
                'price' => 34.99, // Base price
                'sku' => 'TSH-DJ-PREM',
                'stock' => 0, // Stock managed per variation
                'category_id' => $tShirts->id,
                'product_type' => 'variable',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 5,
            ]
        );

        // Create variations for T-Shirt
        $colors = ['Black', 'White', 'Red', 'Navy Blue'];
        $sizes = ['Small', 'Medium', 'Large', 'XL'];
        $colorHex = ['#000000', '#FFFFFF', '#FF0000', '#000080'];
        $variationCount = 0;

        foreach ($colors as $colorIndex => $color) {
            foreach ($sizes as $sizeIndex => $size) {
                $variationCount++;
                $sku = 'TSH-DJ-PREM-' . strtoupper(substr($color, 0, 3)) . '-' . $size;
                
                $variation = ProductVariation::firstOrCreate(
                    ['sku' => $sku],
                    [
                        'product_id' => $variableTShirt->id,
                        'price' => 34.99,
                        'sale_price' => $variationCount % 4 === 0 ? 29.99 : null, // Every 4th variation on sale
                        'stock' => rand(10, 50),
                        'sort_order' => $variationCount,
                        'is_default' => $colorIndex === 0 && $sizeIndex === 1, // Medium Black as default
                        'is_active' => true,
                    ]
                );

                // Delete existing attributes and recreate
                $variation->attributes()->delete();

                // Add attributes
                ProductVariationAttribute::create([
                    'variation_id' => $variation->id,
                    'attribute_name' => 'color',
                    'attribute_value' => $color,
                    'attribute_display' => $colorHex[$colorIndex],
                    'sort_order' => 1,
                ]);

                ProductVariationAttribute::create([
                    'variation_id' => $variation->id,
                    'attribute_name' => 'size',
                    'attribute_value' => $size,
                    'sort_order' => 2,
                ]);
            }
        }

        // Variable Hoodie with Color, Size, and Type variations
        $variableHoodie = Product::firstOrCreate(
            ['slug' => 'dj-premium-hoodie-variable'],
            [
                'name' => 'DJ Premium Hoodie',
                'description' => 'Premium quality hoodie available in multiple colors, sizes, and types. Perfect for cold weather events.',
                'short_description' => 'Premium hoodie with multiple options',
                'price' => 59.99,
                'sku' => 'HOD-DJ-PREM',
                'stock' => 0,
                'category_id' => $hoodies->id,
                'product_type' => 'variable',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 6,
            ]
        );

        // Create variations for Hoodie
        $hoodieColors = ['Black', 'Gray', 'Navy Blue'];
        $hoodieSizes = ['Medium', 'Large', 'XL', 'XXL'];
        $hoodieTypes = ['Standard', 'Premium', 'Limited Edition'];
        $hoodieColorHex = ['#000000', '#808080', '#000080'];
        $variationCount = 0;

        foreach ($hoodieColors as $colorIndex => $color) {
            foreach ($hoodieSizes as $sizeIndex => $size) {
                foreach ($hoodieTypes as $typeIndex => $type) {
                    $variationCount++;
                    $basePrice = 59.99;
                    if ($type === 'Premium') $basePrice = 69.99;
                    if ($type === 'Limited Edition') $basePrice = 79.99;

                    $sku = 'HOD-DJ-PREM-' . strtoupper(substr($color, 0, 3)) . '-' . $size . '-' . strtoupper(substr($type, 0, 3));
                    
                    $variation = ProductVariation::firstOrCreate(
                        ['sku' => $sku],
                        [
                            'product_id' => $variableHoodie->id,
                            'price' => $basePrice,
                            'sale_price' => $variationCount % 6 === 0 ? ($basePrice - 10) : null,
                            'stock' => $type === 'Limited Edition' ? rand(5, 15) : rand(15, 40),
                            'sort_order' => $variationCount,
                            'is_default' => $colorIndex === 0 && $sizeIndex === 1 && $typeIndex === 0, // Medium Black Standard
                            'is_active' => true,
                        ]
                    );

                    // Delete existing attributes and recreate
                    $variation->attributes()->delete();

                    // Add attributes
                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => 'color',
                        'attribute_value' => $color,
                        'attribute_display' => $hoodieColorHex[$colorIndex],
                        'sort_order' => 1,
                    ]);

                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => 'size',
                        'attribute_value' => $size,
                        'sort_order' => 2,
                    ]);

                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => 'type',
                        'attribute_value' => $type,
                        'sort_order' => 3,
                    ]);
                }
            }
        }

        // Variable Cap with Color and Size
        $variableCap = Product::firstOrCreate(
            ['slug' => 'dj-premium-cap-variable'],
            [
                'name' => 'DJ Premium Cap',
                'description' => 'Premium adjustable cap available in multiple colors and sizes. Perfect for any DJ or MC.',
                'short_description' => 'Premium cap with color and size options',
                'price' => 24.99,
                'sku' => 'CAP-DJ-PREM',
                'stock' => 0,
                'category_id' => $caps->id,
                'product_type' => 'variable',
                'is_active' => true,
                'featured' => false,
                'sort_order' => 7,
            ]
        );

        $capColors = ['Black', 'White', 'Navy Blue', 'Red'];
        $capSizes = ['One Size', 'Adjustable'];
        $capColorHex = ['#000000', '#FFFFFF', '#000080', '#FF0000'];
        $variationCount = 0;

        foreach ($capColors as $colorIndex => $color) {
            foreach ($capSizes as $sizeIndex => $size) {
                $variationCount++;
                $sku = 'CAP-DJ-PREM-' . strtoupper(substr($color, 0, 3)) . '-' . strtoupper(substr($size, 0, 2));
                
                $variation = ProductVariation::firstOrCreate(
                    ['sku' => $sku],
                    [
                        'product_id' => $variableCap->id,
                        'price' => 24.99,
                        'sale_price' => $variationCount % 3 === 0 ? 19.99 : null,
                        'stock' => rand(15, 40),
                        'sort_order' => $variationCount,
                        'is_default' => $colorIndex === 0 && $sizeIndex === 0,
                        'is_active' => true,
                    ]
                );

                $variation->attributes()->delete();

                ProductVariationAttribute::create([
                    'variation_id' => $variation->id,
                    'attribute_name' => 'color',
                    'attribute_value' => $color,
                    'attribute_display' => $capColorHex[$colorIndex],
                    'sort_order' => 1,
                ]);

                ProductVariationAttribute::create([
                    'variation_id' => $variation->id,
                    'attribute_name' => 'size',
                    'attribute_value' => $size,
                    'sort_order' => 2,
                ]);
            }
        }

        // Variable Headphones with Type and Color
        $variableHeadphones = Product::firstOrCreate(
            ['slug' => 'dj-pro-headphones-variable'],
            [
                'name' => 'DJ Pro Headphones',
                'description' => 'Professional DJ headphones available in different types and colors. Perfect for any DJ setup.',
                'short_description' => 'Professional headphones with type and color options',
                'price' => 99.99,
                'sku' => 'HP-DJ-PRO',
                'stock' => 0,
                'category_id' => $headphones->id,
                'product_type' => 'variable',
                'is_active' => true,
                'featured' => true,
                'sort_order' => 8,
            ]
        );

        $hpTypes = ['Standard', 'Wireless', 'Premium'];
        $hpColors = ['Black', 'White', 'Silver'];
        $hpColorHex = ['#000000', '#FFFFFF', '#C0C0C0'];
        $variationCount = 0;

        foreach ($hpTypes as $typeIndex => $type) {
            foreach ($hpColors as $colorIndex => $color) {
                $variationCount++;
                $basePrice = 99.99;
                if ($type === 'Wireless') $basePrice = 129.99;
                if ($type === 'Premium') $basePrice = 149.99;

                $sku = 'HP-DJ-PRO-' . strtoupper(substr($type, 0, 3)) . '-' . strtoupper(substr($color, 0, 3));
                
                $variation = ProductVariation::firstOrCreate(
                    ['sku' => $sku],
                    [
                        'product_id' => $variableHeadphones->id,
                        'price' => $basePrice,
                        'sale_price' => $variationCount % 4 === 0 ? ($basePrice - 15) : null,
                        'stock' => $type === 'Premium' ? rand(5, 15) : rand(10, 30),
                        'sort_order' => $variationCount,
                        'is_default' => $typeIndex === 0 && $colorIndex === 0,
                        'is_active' => true,
                    ]
                );

                $variation->attributes()->delete();

                ProductVariationAttribute::create([
                    'variation_id' => $variation->id,
                    'attribute_name' => 'type',
                    'attribute_value' => $type,
                    'sort_order' => 1,
                ]);

                ProductVariationAttribute::create([
                    'variation_id' => $variation->id,
                    'attribute_name' => 'color',
                    'attribute_value' => $color,
                    'attribute_display' => $hpColorHex[$colorIndex],
                    'sort_order' => 2,
                ]);
            }
        }

        // Variable T-Shirt (Long Sleeve) with Color, Size, and Type
        $variableLongSleeve = Product::firstOrCreate(
            ['slug' => 'dj-long-sleeve-t-shirt-variable'],
            [
                'name' => 'DJ Long Sleeve T-Shirt',
                'description' => 'Premium long sleeve t-shirt available in multiple colors, sizes, and types. Perfect for cooler weather events.',
                'short_description' => 'Long sleeve t-shirt with multiple options',
                'price' => 39.99,
                'sku' => 'TSH-DJ-LS',
                'stock' => 0,
                'category_id' => $tShirts->id,
                'product_type' => 'variable',
                'is_active' => true,
                'featured' => false,
                'sort_order' => 9,
            ]
        );

        $lsColors = ['Black', 'Gray', 'Navy Blue'];
        $lsSizes = ['Medium', 'Large', 'XL'];
        $lsTypes = ['Classic', 'Premium'];
        $lsColorHex = ['#000000', '#808080', '#000080'];
        $variationCount = 0;

        foreach ($lsColors as $colorIndex => $color) {
            foreach ($lsSizes as $sizeIndex => $size) {
                foreach ($lsTypes as $typeIndex => $type) {
                    $variationCount++;
                    $basePrice = 39.99;
                    if ($type === 'Premium') $basePrice = 49.99;

                    $sku = 'TSH-DJ-LS-' . strtoupper(substr($color, 0, 3)) . '-' . $size . '-' . strtoupper(substr($type, 0, 3));
                    
                    $variation = ProductVariation::firstOrCreate(
                        ['sku' => $sku],
                        [
                            'product_id' => $variableLongSleeve->id,
                            'price' => $basePrice,
                            'sale_price' => $variationCount % 5 === 0 ? ($basePrice - 10) : null,
                            'stock' => rand(10, 35),
                            'sort_order' => $variationCount,
                            'is_default' => $colorIndex === 0 && $sizeIndex === 1 && $typeIndex === 0,
                            'is_active' => true,
                        ]
                    );

                    $variation->attributes()->delete();

                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => 'color',
                        'attribute_value' => $color,
                        'attribute_display' => $lsColorHex[$colorIndex],
                        'sort_order' => 1,
                    ]);

                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => 'size',
                        'attribute_value' => $size,
                        'sort_order' => 2,
                    ]);

                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => 'type',
                        'attribute_value' => $type,
                        'sort_order' => 3,
                    ]);
                }
            }
        }
    }
}
