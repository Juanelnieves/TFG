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
        Schema::create('pools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('total_liquidity',  15,  2);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('token1_id');
            $table->unsignedBigInteger('token2_id');
            $table->timestamps();

            // Añade las restricciones de clave externa
            $table->foreign('token1_id')->references('id')->on('tokens')->onDelete('cascade');
            $table->foreign('token2_id')->references('id')->on('tokens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pools');
    }
};
