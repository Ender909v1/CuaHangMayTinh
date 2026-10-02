<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrderBillCodeMigrationTest extends TestCase
{
    public function test_rollback_drops_bill_code_after_sqlite_table_rebuild(): void
    {
        Schema::create('orders_new', function (Blueprint $table) {
            $table->id();
            $table->string('bill_code', 20)->unique();
        });
        Schema::rename('orders_new', 'orders');

        $migration = require base_path('database/migrations/2026_09_27_000001_add_bill_code_to_orders.php');
        $migration->down();

        $this->assertFalse(Schema::hasColumn('orders', 'bill_code'));
    }
}
