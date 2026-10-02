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

        if (! Schema::hasColumn('orders', 'bill_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('bill_code', 20)->nullable()->after('id');
            });

            // Backfill existing rows with unique codes (no repeat).
            foreach (DB::table('orders')->select('id')->orderBy('id')->get() as $order) {
                DB::table('orders')->where('id', $order->id)->update([
                    'bill_code' => $this->generateUniqueCode(),
                ]);
            }

            // MySQL: make column NOT NULL + unique after backfill.
            // SQLite (tests): add unique index only.
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE `orders` MODIFY `bill_code` VARCHAR(20) NOT NULL');
            }
            Schema::table('orders', function (Blueprint $table) {
                $table->unique('bill_code');
            });
        }

        // Allow guest checkout: orders without a logged-in user.
        if (Schema::hasColumn('orders', 'user_id')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                // user_id is a constrained FK; drop FK, make nullable, re-add.
                $this->dropForeignKeyIfExists('orders', 'orders_user_id_foreign');
                DB::statement('ALTER TABLE `orders` MODIFY `user_id` BIGINT UNSIGNED NULL');
                Schema::table('orders', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                });
            } elseif ($driver === 'sqlite') {
                // SQLite cannot MODIFY columns; rebuild table preserving data.
                $this->rebuildOrdersTableForSqlite();
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (Schema::hasColumn('orders', 'bill_code')) {
            foreach (Schema::getIndexes('orders') as $index) {
                if (! in_array('bill_code', $index['columns'], true)) {
                    continue;
                }

                Schema::table('orders', function (Blueprint $table) use ($index) {
                    $table->dropIndex($index['name']);
                });
            }

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('bill_code');
            });
        }
    }

    private function generateUniqueCode(): string
    {
        do {
            // e.g. BILL-8K2QX7M4 — short, readable, random, no repeat (unique index guards).
            $code = 'BILL-'.strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));
        } while (DB::table('orders')->where('bill_code', $code)->exists());

        return $code;
    }

    private function dropForeignKeyIfExists(string $table, string $key): void
    {
        $exists = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$table, $key]
        );

        if ($exists !== null) {
            Schema::table($table, function (Blueprint $table) use ($key) {
                $table->dropForeign($key);
            });
        }
    }

    private function rebuildOrdersTableForSqlite(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('orders_new', function (Blueprint $table) {
            $table->id();
            $table->string('bill_code', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->string('shipping_address');
        });

        DB::statement('INSERT INTO orders_new (id, bill_code, user_id, order_date, total_amount, status, payment_method, payment_status, transaction_id, email_sent, email_sent_at, shipping_address) SELECT id, bill_code, user_id, order_date, total_amount, status, payment_method, payment_status, transaction_id, email_sent, email_sent_at, shipping_address FROM orders');

        Schema::drop('orders');
        Schema::rename('orders_new', 'orders');

        Schema::enableForeignKeyConstraints();
    }
};
