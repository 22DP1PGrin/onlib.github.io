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

        Schema::create('book_chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('name', 255); // Название главы
            $table->longText('content'); // Содержание главы (LONGTEXT)
            $table->unsignedInteger('order')->default(0); // Порядок глав
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('user_books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_chapters');
        Schema::dropIfExists('user_books');
    }
};
