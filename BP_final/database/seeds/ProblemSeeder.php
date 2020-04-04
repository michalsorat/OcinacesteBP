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
                'poloha' => '45.333, 30.888',
                'popis_problemu' => 'vymol jak hovado',
                'priorita_id' => '1',
                'cesta_id' => '1',
                'pouzivatel_id' => '1',
                'stav_riesenia_problemu_id' => '1',
                'kategoria_problemu_id' => '1',
                'stav_problemu_id' => '1',
                'popis_stavu_riesenia_problemu_id' => '1',
                'priradeny_zamestnanec_id' => '1',
                'priradene_vozidlo_id' => '1',
            ]
        ];

        foreach ($problemy as $problem) {
            Problem::create(array(
                'poloha' => $problem['poloha'],
                'popis_problemu' => $problem['popis_problemu'],
                'priorita_id' => $problem['priorita_id'],
                'cesta_id' => $problem['cesta_id'],
                'pouzivatel_id' => $problem['pouzivatel_id'],
                'stav_riesenia_problemu_id' => $problem['stav_riesenia_problemu_id'],
                'kategoria_problemu_id' => $problem['kategoria_problemu_id'],
                'stav_problemu_id' => $problem['stav_problemu_id'],
                'popis_stavu_riesenia_problemu_id' => $problem['popis_stavu_riesenia_problemu_id'],
                'priradeny_zamestnanec_id' => $problem['priradeny_zamestnanec_id'],
                'priradene_vozidlo_id' => $problem['priradene_vozidlo_id'],
            ));
        }
    }
}
