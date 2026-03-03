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
        Schema::table('budget_allocations', function (Blueprint $table) {
            $table->foreignId('loan_id')->nullable()->after('transaction_category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_settled')->default(false)->after('month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_allocations', function (Blueprint $table) {
            $table->dropColumn('loan_id', 'is_settled');
        });
    }
};
