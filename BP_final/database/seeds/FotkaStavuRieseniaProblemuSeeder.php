<?php

use App\Models\FotkaStavuRieseniaProblemu;
use Illuminate\Database\Seeder;

class FotkaStavuRieseniaProblemuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fotky = [
            [
                'cesta_k_suboru' => 'nepriradena fotka',
            ],
            [
                'cesta_k_suboru' => 'C:\file1\file2\fotka1.png',
            ],
            [
                'cesta_k_suboru' => 'C:\file1\file2\fotka2.png',
            ],
            [
                'cesta_k_suboru' => 'C:\file1\file2\fotka3.png',
            ]
        ];

        foreach ($fotky as $fotka_stavu_riesenia_problemu) {
            FotkaStavuRieseniaProblemu::create(array(
                'cesta_k_suboru' => $fotka_stavu_riesenia_problemu['cesta_k_suboru'],
            ));
        }
    }
}
