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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');

            // --- OPTIONAL RELATIONSHIPS (The Links) ---
            
            // Link to a specific Budget Category (e.g., Food, Transport)
            $table->foreignId('budget_allocation_id')->nullable()->constrained()->onDelete('set null');
            
            // Link to a Savings Goal (e.g., Emergency Fund, New Phone)
            $table->foreignId('saving_id')->nullable()->constrained()->onDelete('set null');
            
            // Link to a Loan/Debt (e.g., Kredivo, Borrowing from a friend)
            $table->foreignId('loan_id')->nullable()->constrained()->onDelete('set null');

            // --- TRANSACTION DETAILS ---
            
            $table->string('description');
            $table->decimal('amount', 15, 2);
            
            /**
             * income: External money coming in (Salary, Freelance)
             * expense: General spending (not necessarily tied to a budget)
             * budget_spending: Spending that deducts from a specific Budget limit
             * saving: Money moved from a Wallet to a Savings Goal
             * loan_repayment: Paying back a debt (deducts from Loan balance)
             * loan_disbursement: Receiving borrowed money (adds to Wallet & Loan balance)
             */
            $table->enum('type', [
                'income', 
                'expense', 
                'budget_spending', 
                'saving', 
                'loan_repayment', 
                'loan_disbursement'
            ]);

            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
