<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The dev MySQL database was built from an older schema, so several tables that
 * 2026_09_14_000001_create_store_schema is supposed to create are absent even
 * though that migration is recorded as ran. This migration repairs them safely:
 * every step is guarded with Schema::hasTable so it is a no-op on fresh databases.
 */
return new class extends Migration
{
    public function up(): void
    {
        // product_histories: the old dev database has "product_history" instead.
        if (! Schema::hasTable('product_histories')) {
            if (Schema::hasTable('product_history')) {
                Schema::rename('product_history', 'product_histories');
            } else {
                Schema::create('product_histories', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                    $table->string('action');
                    $table->text('details')->nullable();
                    $table->timestamp('created_at')->useCurrent();
                });
            }
        }

        if (! Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('image_url');
                $table->boolean('is_primary')->default(false);
            });
        }

        if (! Schema::hasTable('product_specifications')) {
            Schema::create('product_specifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('spec_name');
                $table->string('spec_value');
            });
        }

        if (! Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('wishlists')) {
            Schema::create('wishlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->timestamp('added_at')->useCurrent();
                $table->unique(['user_id', 'product_id']);
            });
        }

        if (! Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('quantity')->default(1);
                $table->timestamp('added_at')->useCurrent();
                $table->unique(['user_id', 'product_id']);
            });
        }

        if (! Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->text('message');
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('ai_conversations')) {
            Schema::create('ai_conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->nullable();
                $table->text('message');
                $table->text('response')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_images');

        // Hand product_histories back to the legacy name it had before this migration.
        if (Schema::hasTable('product_histories') && ! Schema::hasTable('product_history')) {
            Schema::rename('product_histories', 'product_history');
        }
    }
};
