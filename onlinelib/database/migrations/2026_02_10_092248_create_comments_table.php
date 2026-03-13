<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_book_id')->nullable();
            $table->unsignedBigInteger('classic_book_id')->nullable();
            $table->unsignedBigInteger('comment_parent_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_book_id')->references('id')->on('user_books')->onDelete('cascade');
            $table->foreign('classic_book_id')->references('id')->on('classic_book')->onDelete('cascade');
            $table->foreign('comment_parent_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
