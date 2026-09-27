<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            }
            // Tracks whether the buyer has seen the latest status ("!" alert clears on view).
            if (! Schema::hasColumn('orders', 'user_seen_at')) {
                $table->timestamp('user_seen_at')->nullable()->after('email_sent_at');
            }
        });

        // Backfill: existing rows get updated_at so "updated" detection works.
        if (Schema::hasColumn('orders', 'updated_at') && Schema::hasColumn('orders', 'order_date')) {
            DB::table('orders')->whereNull('updated_at')->update(['updated_at' => DB::raw('order_date')]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'user_seen_at')) {
                $table->dropColumn('user_seen_at');
            }
        });
    }
};
