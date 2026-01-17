<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassicBookGenresSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('book_genres')->insert([

            ['classic_book_id' => 1, 'genre_id' => 10],
            ['classic_book_id' => 1, 'genre_id' => 7],
            ['classic_book_id' => 1, 'genre_id' => 17],

            ['classic_book_id' => 2, 'genre_id' => 10],
            ['classic_book_id' => 2, 'genre_id' => 7],
            ['classic_book_id' => 2, 'genre_id' => 9],

            ['classic_book_id' => 3, 'genre_id' => 7],
            ['classic_book_id' => 3, 'genre_id' => 13],
            ['classic_book_id' => 3, 'genre_id' => 24],

            ['classic_book_id' => 4, 'genre_id' => 7],
            ['classic_book_id' => 4, 'genre_id' => 11],
            ['classic_book_id' => 4, 'genre_id' => 17],

            ['classic_book_id' => 5, 'genre_id' => 7],
            ['classic_book_id' => 5, 'genre_id' => 24],
            ['classic_book_id' => 5, 'genre_id' => 17],

            ['classic_book_id' => 6, 'genre_id' => 7],
            ['classic_book_id' => 6, 'genre_id' => 24],
            ['classic_book_id' => 6, 'genre_id' => 17],

            ['classic_book_id' => 7, 'genre_id' => 26],
            ['classic_book_id' => 7, 'genre_id' => 24],
            ['classic_book_id' => 7, 'genre_id' => 16],
        ]);
    }
}
