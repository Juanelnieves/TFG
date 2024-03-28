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
        Schema::create('liquiditys', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('pool_id')->references('id')->on('pools');
            $table->foreignId('token_id')->references('id')->on('tokens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liquiditys');
    }
};
