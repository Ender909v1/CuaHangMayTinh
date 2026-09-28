<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Customer reviews can be answered by an admin from the Reviews tab, so each review
 * needs a place to keep that response and when it was written.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('admin_response')->nullable()->after('comment');
            $table->timestamp('admin_responded_at')->nullable()->after('admin_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['admin_response', 'admin_responded_at']);
        });
    }
};
