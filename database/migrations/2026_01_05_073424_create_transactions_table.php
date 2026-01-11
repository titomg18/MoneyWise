<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id'); // PK
            $table->unsignedBigInteger('user_id'); // FK ke users
            $table->unsignedBigInteger('category_id'); // FK ke categories
            $table->decimal('amount', 12, 2);
            $table->enum('transaction_type', ['income', 'expense']);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};