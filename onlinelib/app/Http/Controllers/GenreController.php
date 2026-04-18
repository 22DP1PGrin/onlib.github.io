<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Genre;

// Kontrolieris, kas apstrādā ar žanriem saistītās darbības
class GenreController extends Controller
{

    // Metode, kas atgriež visus žanrus un parāda tos jaunas grāmatas veidošanas lapā
    public function index()
    {
        // Iegūst visus žanrus no datu bāzes
        $genres = Genre::all();

        // Ja žanru saraksts ir tukšs, izmet izņēmumu
        if ($genres->isEmpty()) {
            throw new \Exception("Datu bāzē nav žanru! Palaidiet seeder.");
        }

        // Atgriežam skatījumu ar visiem žanriem
        return Inertia::render('Writing/NewStory', [
            'genres' => $genres
        ]);
    }

}
