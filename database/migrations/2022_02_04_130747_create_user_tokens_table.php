<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTokensTable extends Migration
{
    public function up()
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('token_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 24, 12);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tokens');
    }
}
