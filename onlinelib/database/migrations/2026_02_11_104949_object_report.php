<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    // Izpilda migrācijas
    public function up(): void
    {
        Schema::create('object_report', function (Blueprint $table) {
            $table->id();
            $table->enum('subject', ['Maldinošs vai kaitīgs saturs', 'Noteikumu pārkāpums', 'Spams vai reklāma', 'Naida runa vai aizskarošs saturs',
                'Zemas kvalitātes saturs', 'Krāpnieciska vai maldinoša darbība', 'Naida runa vai diskriminējoša uzvedība', 'Citu lietotāju aizskaršana'])->default('Noteikumu pārkāpums');
            $table->text('problem');
            $table->unsignedBigInteger('reporter_user_id');
            $table->unsignedBigInteger('user_book_id')->nullable();;
            $table->unsignedBigInteger('classic_book_id')->nullable();;
            $table->unsignedBigInteger('reported_user_id')->nullable();;
            $table->timestamps();

            $table->foreign('reporter_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_book_id')->references('id')->on('user_books')->onDelete('cascade');
            $table->foreign('classic_book_id')->references('id')->on('classic_book')->onDelete('cascade');
            $table->foreign('reported_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    // Apgriezt migrācijas
    public function down(): void
    {
        Schema::dropIfExists('object_report');
    }
};
