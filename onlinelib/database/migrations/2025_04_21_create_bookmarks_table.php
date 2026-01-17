<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('bookmark_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_book_id')->nullable();
            $table->unsignedBigInteger('classic_book_id')->nullable();
            $table->unsignedBigInteger('bookmark_type_id');
            $table->timestamps();

            $table->foreign('user_book_id')->references('id')->on('user_books')->onDelete('cascade');
            $table->foreign('classic_book_id')->references('id')->on('classic_book')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bookmark_type_id')->references('id')->on('bookmark_types')->onDelete('cascade');
        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('bookmark_types');
    }
};
