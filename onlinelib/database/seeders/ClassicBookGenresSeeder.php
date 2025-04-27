<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassicBookGenresSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('classic_book_genre')->insert([

            ['book_id' => 1, 'genre_id' => 10],
            ['book_id' => 1, 'genre_id' => 7],
            ['book_id' => 1, 'genre_id' => 17],

            ['book_id' => 2, 'genre_id' => 10],
            ['book_id' => 2, 'genre_id' => 7],
            ['book_id' => 2, 'genre_id' => 9],

            ['book_id' => 3, 'genre_id' => 7],
            ['book_id' => 3, 'genre_id' => 13],
            ['book_id' => 3, 'genre_id' => 24],

            ['book_id' => 4, 'genre_id' => 7],
            ['book_id' => 4, 'genre_id' => 11],
            ['book_id' => 4, 'genre_id' => 17],

            ['book_id' => 5, 'genre_id' => 7],
            ['book_id' => 5, 'genre_id' => 24],
            ['book_id' => 5, 'genre_id' => 17],

            ['book_id' => 6, 'genre_id' => 7],
            ['book_id' => 6, 'genre_id' => 24],
            ['book_id' => 6, 'genre_id' => 17],

            ['book_id' => 7, 'genre_id' => 26],
            ['book_id' => 7, 'genre_id' => 24],
            ['book_id' => 7, 'genre_id' => 16],
        ]);
    }
}
