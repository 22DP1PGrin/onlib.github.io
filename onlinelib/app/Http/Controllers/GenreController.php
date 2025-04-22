<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Genre;

class GenreController extends Controller
{

    // Metode, kas atgriež visus žanrus un parāda tos jaunas grāmatas veidošanas lapā
    public function index()
    {
        // Iegūstam visus žanrus no datu bāzes
        $genres = \App\Models\Genre::all();

        // Ja žanru saraksts ir tukšs, izmetam izņēmumu
        if ($genres->isEmpty()) {
            throw new \Exception("Datu bāzē nav žanru! Palaidiet seeder.");
        }

        // Atgriežam skatījumu (Inertia) ar visiem žanriem
        return Inertia::render('Writing/NewStory', [
            'genres' => $genres
        ]);
    }

}
