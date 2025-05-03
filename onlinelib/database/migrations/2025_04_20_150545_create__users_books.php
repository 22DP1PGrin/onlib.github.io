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
        Schema::create('user_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 255);
            $table->text('description');
            $table->enum('age_limit', ['0+', '6+', '12+', '16+', '18+'])->default('0+');
            $table->enum('status', ['Procesā', 'Pabeigts', 'Pamests'])->default('Procesā');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::create('user_book_genre', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('genre_id');

            $table->foreign('book_id')->references('id')->on('user_books')->onDelete('cascade');

            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');

        });

        Schema::create('user_book_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('name', 255);
            $table->longText('content');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('user_books')->onDelete('cascade');
        });

        Schema::create('User_books_ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('grade');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('user_books')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('User_books_ratings');
        Schema::dropIfExists('user_book_genre');
        Schema::dropIfExists('user_book_chapters');
        Schema::dropIfExists('user_books');



    }
};
