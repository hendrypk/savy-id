<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Correct MariaDB syntax to disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // 2. Update the ENUM using native MariaDB syntax
        // This is much safer than dropping and recreating the entire table
        DB::statement("ALTER TABLE loans MODIFY COLUMN loan_type ENUM(
            'credit_card', 
            'pinjol', 
            'personal', 
            'bank', 
            'kasbon_kantor', 
            'other'
        ) NOT NULL");

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        // Revert to original enum values if needed
        DB::statement("ALTER TABLE loans MODIFY COLUMN loan_type ENUM(
            'credit_card', 
            'personal', 
            'other'
        ) NOT NULL");

        Schema::enableForeignKeyConstraints();
    }
};