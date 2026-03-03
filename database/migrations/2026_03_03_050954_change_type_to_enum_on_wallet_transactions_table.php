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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('type', [
                'income',            // General income
                'expense',           // General expense / Budget spending
                'loan_disbursement', // Loan payout received (Inflow)
                'loan_repayment',    // Paying back a loan (Outflow)
                'saving',            // Moving money to savings (Outflow from wallet)
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }
};
