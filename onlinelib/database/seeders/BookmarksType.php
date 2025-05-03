<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookmarksType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bookmark_types')->insert([

            ['name' => 'Izlasīts'],
            ['name' => 'Lasu'],
            ['name' => 'Pamests'],
            ['name' => 'Plānots'],

        ]);
    }
}
