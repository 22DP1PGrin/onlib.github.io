<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('book_genres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classic_book_id')->nullable();
            $table->unsignedBigInteger('user_book_id')->nullable();
            $table->unsignedBigInteger('genre_id');

            $table->foreign('classic_book_id')->references('id')->on('classic_book')->onDelete('cascade');
            $table->foreign('user_book_id')->references('id')->on('user_books')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('book_genres');
    }
};
