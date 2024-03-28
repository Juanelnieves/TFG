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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'token creation',
                'pool creation',
                'swap',
                'add liquidity',
                'withdraw liquidity',
            ]);
            $table->enum('status', [
                'completed',
                'failed',
            ]);
            $table->decimal('amount', 15, 2);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('pool_id')->nullable(); // Añade una columna para el ID de la pool
            $table->foreign('pool_id')->references('id')->on('pools')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
