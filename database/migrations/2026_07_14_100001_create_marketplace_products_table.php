<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->unique()->nullable();
            $table->integer('stock')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('marketplace_categories')->nullOnDelete();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->enum('product_type', ['simple', 'variable'])->default('simple');
            $table->boolean('is_active')->default(true);
            $table->boolean('featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'category_id']);
            $table->index(['featured', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_products');
    }
};
