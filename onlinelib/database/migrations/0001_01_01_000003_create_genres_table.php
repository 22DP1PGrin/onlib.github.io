<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->timestamps();
        });

    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('classic_book_genre');
        Schema::dropIfExists('user_book_genre');
        Schema::dropIfExists('genres');

    }
};
