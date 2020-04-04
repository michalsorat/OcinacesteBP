<?php

use App\Models\PopisStavuRieseniaProblemu;
use Illuminate\Database\Seeder;

class PopisStavuRieseniaProblemuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $popisy = [
            [
                'popis' => 'nepridaredny popis',
                'fotka_riesenia_id' => 1
            ],
            [
                'popis' => 'popis 1',
                'fotka_riesenia_id' => 2
            ],
            [
                'popis' => 'popis 2',
                'fotka_riesenia_id' => 2
            ],
            [
                'popis' => 'popis 3',
                'fotka_riesenia_id' => 2
            ]
        ];

        foreach ($popisy as $popis_stavu_riesenia_poblemu) {
            PopisStavuRieseniaProblemu::create(array(
                'popis' => $popis_stavu_riesenia_poblemu['popis'],
                'fotka_riesenia_id' => $popis_stavu_riesenia_poblemu['fotka_riesenia_id']
            ));
        }
    }
}
