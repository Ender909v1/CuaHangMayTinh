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
        Schema::disableForeignKeyConstraints();

        // Drop all tables in reverse dependency order
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('warranty_history');
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('product_views');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('users');

        // ==================== users ====================
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('role')->default('customer'); // customer, admin, staff, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        // ==================== brands ====================
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
        });

        // ==================== categories ====================
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
        });

        // ==================== products ====================
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->unsignedInteger('stock_qty')->default(0);
            $table->string('type'); // e.g. physical, digital, service
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // ==================== product_images ====================
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_url');
            $table->boolean('is_primary')->default(false);
        });

        // ==================== product_specifications ====================
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('spec_name');
            $table->string('spec_value');
        });

        // ==================== product_views ====================
        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // nullable for guests
            $table->string('session_id')->nullable();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent();
        });

        // ==================== reviews ====================
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // ==================== wishlists ====================
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['user_id', 'product_id']);
        });

        // ==================== cart_items ====================
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['user_id', 'product_id']);
        });

        // ==================== orders ====================
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('transaction_id')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->string('shipping_address');
        });

        // ==================== order_items ====================
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
        });

        // ==================== warranties ====================
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active'); // active, expired, claimed, void
        });

        // ==================== warranty_history ====================
        Schema::create('warranty_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warranty_id')->constrained()->cascadeOnDelete();
            $table->timestamp('action_date')->useCurrent();
            $table->text('description');
            $table->string('performed_by');
        });

        // ==================== support_tickets ====================
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('open'); // open, in_progress, resolved, closed
            $table->string('type'); // general, warranty, technical, etc.
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
        });

        // ==================== ticket_replies ====================
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();
        });

        // ==================== ai_conversations ====================
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->text('message');
            $table->text('response')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('warranty_history');
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('product_views');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('users');

        Schema::enableForeignKeyConstraints();
    }
};
