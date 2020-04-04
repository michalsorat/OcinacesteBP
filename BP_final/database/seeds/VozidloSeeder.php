<?php

use App\Models\Vozidlo;
use Illuminate\Database\Seeder;

class VozidloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vozidla = [
            [
                'oznacenie' => 'Default vozidlo',
                'SPZ' => '-',
                'pocet_najazdenych_km' => '0',
                'poznamka' => '-'
            ],
            [
                'oznacenie' => 'Tatra',
                'SPZ' => 'TT564HS',
                'pocet_najazdenych_km' => '60000',
                'poznamka' => 'servis posledny Jun 2019'
            ],
            [
                'oznacenie' => 'Tatra',
                'SPZ' => 'TT783OP',
                'pocet_najazdenych_km' => '30000',
                'poznamka' => 'servis posledny Jan 2015'
            ]
        ];

        foreach ($vozidla as $vozidlo) {
            Vozidlo::create(array(
                'oznacenie' => $vozidlo['oznacenie'],
                'SPZ' => $vozidlo['SPZ'],
                'pocet_najazdenych_km' => $vozidlo['pocet_najazdenych_km'],
                'poznamka' => $vozidlo['poznamka'],
            ));
        }
    }
}
