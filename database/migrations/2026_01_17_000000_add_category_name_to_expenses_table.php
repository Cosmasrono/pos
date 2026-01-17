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
            // Drop the index on category_id first
            $table->dropIndex('expenses_category_id_index');
            
            // Add category_name column as nullable
            $table->string('category_name')->nullable()->after('id');
        });
        
        // Make category_id nullable (SQL Server syntax)
        DB::statement('ALTER TABLE expenses ALTER COLUMN category_id BIGINT NULL');
    }

    public function down(): void
    {
        // Make category_id required again (SQL Server syntax)
        DB::statement('ALTER TABLE expenses ALTER COLUMN category_id BIGINT NOT NULL');
        
        Schema::table('expenses', function (Blueprint $table) {
            // Drop category_name column
            $table->dropColumn('category_name');
            
            // Recreate the index on category_id
            $table->index('category_id');
        });
    }
};
