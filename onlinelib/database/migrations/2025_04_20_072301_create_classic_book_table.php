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
        Schema::create('classic_book', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description');
            $table->enum('age_limit', ['0+', '6+', '12+', '16+', '18+'])->default('0+');
            $table->string('Author_name', 255);
            $table->string('Author_surname', 255);
            $table->integer('Year_publication');
            $table->timestamps();
        });

        Schema::create('classic_book_genre', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('genre_id');

            $table->foreign('book_id')->references('id')->on('classic_book')->onDelete('cascade');

            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');

        });

        Schema::create('classic_book_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('name', 255);
            $table->longText('content');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('classic_book')->onDelete('cascade');
        });

        Schema::create('Classic_books_ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('grade');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('classic_book')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Classic_books_ratings');
        Schema::dropIfExists('classic_book_genre');
        Schema::dropIfExists('classic_book_chapters');
        Schema::dropIfExists('classic_book');
    }
};
