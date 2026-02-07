<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('story_block', function (Blueprint $table) {
            $table->id();
            $table->enum('subject', ['Maldinošs vai kaitīgs saturs', 'Noteikumu pārkāpums', 'Spams vai reklāma', 'Naida runa vai aizskarošs saturs', 'Zemas kvalitātes saturs', 'Sūdzības no lietotājiem'])->default('Sūdzības no lietotājiem');
            $table->text('problem');
            $table->unsignedBigInteger('user_book_id');
            $table->timestamps();

            $table->foreign('user_book_id')->references('id')->on('user_books')->onDelete('cascade');
        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('story_block');
    }
};
