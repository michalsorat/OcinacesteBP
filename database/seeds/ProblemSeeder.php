<?php

use App\Models\Problem;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $problemy = [
            [
                'poloha' => '48.3767994, 17.5835082',
                'address' => 'Čajkovského 4, Trnava',
                'popis_problemu' => 'Diera v ceste',
                'priorita_id' => '1',
                'pouzivatel_id' => '2',
                'kategoria_problemu_id' => '1',
                'stav_problemu_id' => '1',
                'isBump' => false,
            ]
        ];

        foreach ($problemy as $problem) {
            Problem::create(array(
                'poloha' => $problem['poloha'],
                'address' => $problem['address'],
                'popis_problemu' => $problem['popis_problemu'],
                'priorita_id' => $problem['priorita_id'],
                'pouzivatel_id' => $problem['pouzivatel_id'],
                'kategoria_problemu_id' => $problem['kategoria_problemu_id'],
                'stav_problemu_id' => $problem['stav_problemu_id'],
                'isBump' => $problem['isBump'],
            ));
        }
    }
}
