<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_product_id')->constrained('marketplace_products')->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['marketplace_product_id', 'is_active'], 'mpv_product_active_idx');
        });

        Schema::create('marketplace_product_variation_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('marketplace_product_variations')->cascadeOnDelete();
            $table->string('attribute_name');
            $table->string('attribute_value');
            $table->string('attribute_display')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('variation_id', 'mpva_variation_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_product_variation_attributes');
        Schema::dropIfExists('marketplace_product_variations');
    }
};
