<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            ['name' => 'Fantāzija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zinātniskā fantastika', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Romantika', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trilleris', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Piedzīvojumi', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Detektīvs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Drāma', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komēdija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Traģēdija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vēsturiskais', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mistika', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Šausmas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Psiholoģiskais trilleris', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Distopija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Utopija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pārdabiskais', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ikdienas dzīve', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Steampunk', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Postapokalipse', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cyberpunk', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LGBTQ+ ', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biogrāfija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dokumentālais', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Filosofija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fanfiction', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Parodija', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dzeja', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
