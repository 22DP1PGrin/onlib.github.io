<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('user_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 255);
            $table->text('description');
            $table->enum('age_limit', ['0+', '6+', '12+', '16+', '18+'])->default('0+');
            $table->enum('status', ['Procesā', 'Pabeigts', 'Pamests'])->default('Procesā');
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('user_books');



    }
};
