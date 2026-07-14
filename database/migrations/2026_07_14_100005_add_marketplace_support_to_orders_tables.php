<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'source')) {
                $table->string('source', 32)->default('merch')->after('user_id');
                $table->index('source');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'marketplace_product_id')) {
                $table->unsignedBigInteger('marketplace_product_id')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'item_source')) {
                $table->string('item_source', 32)->default('merch')->after('marketplace_product_id');
                $table->index('item_source');
            }
            if (!Schema::hasColumn('order_items', 'variation_id')) {
                $table->unsignedBigInteger('variation_id')->nullable()->after('item_source');
            }
        });

        // Avoid ->change() (requires doctrine/dbal); use raw SQL for nullable product_id
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE order_items ALTER COLUMN product_id DROP NOT NULL');
        }

        // Add FK separately so re-runs don't fail hard if it already exists
        if (Schema::hasColumn('order_items', 'marketplace_product_id')) {
            $foreignKeys = $this->listForeignKeys('order_items');
            if (!in_array('order_items_marketplace_product_id_foreign', $foreignKeys)) {
                Schema::table('order_items', function (Blueprint $table) {
                    $table->foreign('marketplace_product_id')
                        ->references('id')
                        ->on('marketplace_products')
                        ->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $foreignKeys = $this->listForeignKeys('order_items');
            if (in_array('order_items_marketplace_product_id_foreign', $foreignKeys)) {
                $table->dropForeign(['marketplace_product_id']);
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            $cols = [];
            foreach (['marketplace_product_id', 'item_source', 'variation_id'] as $col) {
                if (Schema::hasColumn('order_items', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE order_items ALTER COLUMN product_id SET NOT NULL');
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'source')) {
                $table->dropColumn('source');
            }
        });
    }

    private function listForeignKeys(string $table): array
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            $db = Schema::getConnection()->getDatabaseName();
            $rows = DB::select(
                'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
                [$db, $table]
            );
            return array_map(fn ($r) => $r->CONSTRAINT_NAME, $rows);
        }

        return [];
    }
};
