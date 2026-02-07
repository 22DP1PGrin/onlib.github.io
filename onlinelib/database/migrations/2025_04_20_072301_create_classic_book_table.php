<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
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
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();
        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('classic_book');
    }
};
