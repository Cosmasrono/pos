<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Drop the foreign key and index on category_id first (MySQL requires FK drop before index)
            $table->dropForeign(['category_id']);
            $table->dropIndex('expenses_category_id_index');
            
            // Add category_name column as nullable
            $table->string('category_name')->nullable()->after('id');
            
            // Make category_id nullable (Database-agnostic way)
            $table->unsignedBigInteger('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Drop category_name column
            $table->dropColumn('category_name');
            
            // Make category_id required again
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            
            // Re-add the index and foreign key
            $table->index('category_id');
            $table->foreign('category_id')->references('id')->on('expense_categories')->cascadeOnDelete();
        });
    }
};
