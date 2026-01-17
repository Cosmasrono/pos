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
            // Drop foreign key first (if it exists)
            $table->dropForeign(['user_id']);
            
            // Drop the old unique constraint
            $table->dropUnique(['user_id', 'product_id']);
            
            // Make user_id nullable
            $table->foreignId('user_id')->nullable()->change();
            
            // Add session_id column
            $table->string('session_id')->nullable()->after('user_id');
            
            // Re-add the foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
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
            // Drop foreign key
            $table->dropForeign(['user_id']);
            
            // Drop indexes
            $table->dropIndex(['session_id', 'product_id']);
            
            // Remove session_id column
            $table->dropColumn('session_id');
            
            // Make user_id not nullable
            $table->foreignId('user_id')->nullable(false)->change();
            
            // Re-add unique constraint
            $table->unique(['user_id', 'product_id']);
            
            // Re-add foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};