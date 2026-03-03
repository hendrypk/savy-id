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
            $table->dropForeign(['budget_allocation_id']);
            $table->dropForeign(['saving_id']);
            $table->dropForeign(['loan_id']);
            $table->dropColumn(['budget_allocation_id', 'saving_id', 'loan_id']);
            $table->nullableMorphs('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropMorphs('reference');
            $table->foreignId('budget_allocation_id')->nullable()->constrained();
            $table->foreignId('saving_id')->nullable()->constrained();
            $table->foreignId('loan_id')->nullable()->constrained();
        });
    }
};
