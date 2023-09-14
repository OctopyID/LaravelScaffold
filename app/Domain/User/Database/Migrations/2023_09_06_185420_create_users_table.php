<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->string('name', 60);
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('users');
    }
};
