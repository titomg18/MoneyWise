<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan budget_id untuk menghubungkan transaksi dengan anggaran
            $table->unsignedBigInteger('budget_id')->nullable()->after('category_id');
            
            $table->foreign('budget_id')
                  ->references('id')
                  ->on('budgets')
                  ->onDelete('set null');
        });
        
        // Update spent_amount di budgets untuk data yang sudah ada
        DB::statement('
            UPDATE budgets b
            SET spent_amount = COALESCE((
                SELECT SUM(t.amount)
                FROM transactions t
                WHERE t.budget_id = b.id
                AND t.transaction_type = "expense"
            ), 0)
        ');
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['budget_id']);
            $table->dropColumn('budget_id');
        });
    }
};