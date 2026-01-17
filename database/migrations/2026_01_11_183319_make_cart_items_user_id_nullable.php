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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('session_id')->nullable()->after('user_id');
            
            // Drop the old unique constraint as user_id can now be null
            $table->dropUnique(['user_id', 'product_id']);
            
            // Add indexes for performance
            $table->index(['session_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable(false)->change();
            $table->unique(['user_id', 'product_id']);
            $table->dropColumn('session_id');
        });
    }
};
