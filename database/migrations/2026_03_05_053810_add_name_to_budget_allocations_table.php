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
    // 1. Tambah kolom name jika belum ada
    if (!Schema::hasColumn('budget_allocations', 'name')) {
        Schema::table('budget_allocations', function (Blueprint $table) {
            $table->string('name')->after('uuid')->nullable();
        });
    }

    // 2. Update data lama menggunakan Row Number per loan_id
    // Query ini akan memberikan nomor 1, 2, 3... berdasarkan urutan bulan (month_year)
    DB::statement("
        UPDATE budget_allocations 
        JOIN (
            SELECT 
                ba.id, 
                cat.name as cat_name,
                ROW_NUMBER() OVER (PARTITION BY ba.loan_id ORDER BY ba.month_year ASC) as row_num
            FROM budget_allocations ba
            JOIN transaction_categories cat ON ba.transaction_category_id = cat.id
            WHERE ba.loan_id IS NOT NULL
        ) AS ranked ON budget_allocations.id = ranked.id
        SET budget_allocations.name = CONCAT(ranked.cat_name, ' ', ranked.row_num)
    ");

    // 3. Update data yang BUKAN loan (biasa)
    DB::table('budget_allocations')
        ->join('transaction_categories', 'budget_allocations.transaction_category_id', '=', 'transaction_categories.id')
        ->whereNull('loan_id')
        ->whereNull('budget_allocations.name')
        ->update([
            'budget_allocations.name' => DB::raw('transaction_categories.name')
        ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_allocations', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
