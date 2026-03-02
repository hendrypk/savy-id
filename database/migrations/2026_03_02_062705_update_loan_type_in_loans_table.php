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
        // 1. Capture existing data
        $oldData = DB::table('loans')->get();

        // 2. Disable Foreign Keys and drop old table
        DB::statement('PRAGMA foreign_keys = OFF');
        Schema::dropIfExists('loans');

        // 3. Recreate table with updated ENUM (CHECK constraint)
        DB::statement("
            CREATE TABLE loans (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
                uuid VARCHAR NOT NULL, 
                user_id INTEGER NOT NULL, 
                name VARCHAR NOT NULL, 
                loan_type VARCHAR CHECK (loan_type IN ('credit_card', 'pinjol', 'personal', 'bank', 'kasbon_kantor', 'other')) NOT NULL, 
                credit_limit NUMERIC NOT NULL, 
                interest_rate_monthly NUMERIC NOT NULL DEFAULT '0', 
                due_date INTEGER NOT NULL, 
                created_at DATETIME, 
                updated_at DATETIME, 
                remaining_amount NUMERIC NOT NULL DEFAULT '0', 
                monthly_installment NUMERIC NOT NULL DEFAULT '0', 
                status VARCHAR NOT NULL DEFAULT 'active', 
                FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        // 4. Re-apply Indexes
        DB::statement('CREATE UNIQUE INDEX loans_uuid_unique ON loans (uuid)');

        // 5. Restore data
        foreach ($oldData as $row) {
            DB::table('loans')->insert((array) $row);
        }

        // 6. Re-enable Foreign Keys
        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
