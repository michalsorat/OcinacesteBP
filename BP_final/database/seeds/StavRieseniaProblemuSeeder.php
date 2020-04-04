<?php

use App\Models\StavRieseniaProblemu;
use Illuminate\Database\Seeder;

class StavRieseniaProblemuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stavy = [
            [
                'typ_stavu_riesenia_problemu_id' => 1,
            ],
            [
                'typ_stavu_riesenia_problemu_id' => 2,
            ],
            [
                'typ_stavu_riesenia_problemu_id' => 3,
            ],
            [
                'typ_stavu_riesenia_problemu_id' => 4,
            ]
        ];

        foreach ($stavy as $stav_riesenia_problemu) {
            StavRieseniaProblemu::create(array(
                'typ_stavu_riesenia_problemu_id' => $stav_riesenia_problemu['typ_stavu_riesenia_problemu_id']
            ));
        }
    }
}
