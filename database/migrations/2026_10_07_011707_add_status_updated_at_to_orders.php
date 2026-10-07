<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (! Schema::hasColumn('orders', 'status_updated_at')) {
            Schema::table('orders', function (Blueprint $table) {
                // When the current status took effect (admin delivered/cancelled/..., customer cancelled).
                $table->timestamp('status_updated_at')->nullable()->after('status');
            });
        }

        // Backfill: existing rows fall back to the last order update, then the order date.
        if (Schema::hasColumn('orders', 'status_updated_at') && Schema::hasColumn('orders', 'order_date')) {
            $fallback = Schema::hasColumn('orders', 'updated_at') ? 'COALESCE(updated_at, order_date)' : 'order_date';

            DB::table('orders')->whereNull('status_updated_at')->update([
                'status_updated_at' => DB::raw($fallback),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('orders') || ! Schema::hasColumn('orders', 'status_updated_at')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_updated_at');
        });
    }
};
