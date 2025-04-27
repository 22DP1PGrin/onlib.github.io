<?php

namespace Database\Seeders;

use App\Models\ClassicBook;
use Illuminate\Database\Seeder;

class ClassicBooksSeeder extends Seeder
{
    public function run()
    {
        $books = [
            [
                'name' => 'Mērnieku laiki',
                'description' => 'Pirmais latviešu romāns par divu mērnieku - apcirtņa Indrāņa un izglītota Kalna Mārtiņa - piedzīvojumiem Vidzemes laukos.
                Grāmata atspoguļo 19.gs. vidus latviešu sabiedrības sadalījumu un morāles kritiku.',
                'age_limit' => '12+',
                'Author_name' => 'Reinis un Matīss',
                'Author_surname' => 'Kaudzīte',
                'Year_publication' => 1879,
            ],

            [
                'name' => 'Dvēseļu putenis',
                'description' => 'Šis vēsturiskais romāns, kas pirmo reizi tika izdots 1933. gadā, stāsta par latviešu strēlnieku gaitām Pirmajā pasaules karā un neatkarības cīņās.',
                'age_limit' => '12+',
                'Author_name' => 'Aleksandrs',
                'Author_surname' => 'Grīns',
                'Year_publication' => 1933,
            ],
            [
                'name' => 'Pazudušais dēls',
                'description' => 'Romāns par ģimenes attiecībām un sabiedrības spiedienu, kas ietekmē cilvēka dzīves ceļu. Darbs atklāj iekšējos konfliktus un meklējumus pēc identitātes.',
                'age_limit' => '12+',
                'Author_name' => 'Rūdolfs',
                'Author_surname' => 'Blaumanis',
                'Year_publication' => 1903,
            ],
            [
                'name' => 'Klusie ciemiņi',
                'description' => 'Stāsts par cilvēku attiecībām un noslēpumiem, kas atklājas nelielā lauku ciemā. Darbs pievērš uzmanību cilvēku iekšējām pasaulēm un sabiedrības normām.',
                'age_limit' => '12+',
                'Author_name' => 'Aleksandrs',
                'Author_surname' => 'Grīns',
                'Year_publication' => 1934,
            ],

            [
                'name' => 'Baltā grāmata',
                'description' => 'Romāns par cilvēka dzīves ceļu, meklējumiem pēc patiesības un iekšējās brīvības. Darbs pievērš uzmanību garīgajām vērtībām un cilvēka attiecībām ar pasauli.',
                'age_limit' => '12+',
                'Author_name' => 'Jānis',
                'Author_surname' => 'Jaunsudrabiņš',
                'Year_publication' => 1928,
            ],
            [
                'name' => 'Zemdegas',
                'description' => 'Romāns par cilvēka dzīves ceļu, meklējumiem pēc identitātes un vietas pasaulē. Darbs pievērš uzmanību cilvēka attiecībām ar dabu un sabiedrību.',
                'age_limit' => '12+',
                'Author_name' => 'Regīna',
                'Author_surname' => 'Ezera',
                'Year_publication' => 1967,
            ],
            [
                'name' => 'Ziemas pasakas',
                'description' => 'Dzejoļu krājums, kas atspoguļo ziemas ainavas un cilvēka attiecības ar dabu. Darbs rakstīts ar lirisku un filozofisku pieeju.',
                'age_limit' => '12+',
                'Author_name' => 'Kārlis',
                'Author_surname' => 'Skalbe',
                'Year_publication' => 1905,
            ],

        ];

        $allBooks = array_merge($books);

        foreach ($allBooks as $book) {
            ClassicBook::create($book);
        }
    }
}
