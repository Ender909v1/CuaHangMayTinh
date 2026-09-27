<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_histories')) {
            return;
        }

        Schema::table('product_histories', function (Blueprint $table) {
            // Inventory edits: the stock number before/after, plus the note the admin had to write.
            if (! Schema::hasColumn('product_histories', 'old_stock_qty')) {
                $table->integer('old_stock_qty')->nullable()->after('action');
            }
            if (! Schema::hasColumn('product_histories', 'new_stock_qty')) {
                $table->integer('new_stock_qty')->nullable()->after('old_stock_qty');
            }
            if (! Schema::hasColumn('product_histories', 'note')) {
                $table->text('note')->nullable()->after('details');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_histories')) {
            return;
        }

        Schema::table('product_histories', function (Blueprint $table) {
            foreach (['old_stock_qty', 'new_stock_qty', 'note'] as $column) {
                if (Schema::hasColumn('product_histories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
