<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GenreSeeder::class,
            ClassicBooksSeeder::class,
            ClassicBookGenresSeeder::class,
            ClassicBooksChapterSeeder::class,
            BookmarksType::class,
            UsersSeeder::class,
        ]);
    }

}
