<?php

use App\Models\PriradenyZamestnanec;
use Illuminate\Database\Seeder;

class PriradenyZamestnanecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priradene = [
            [
                'zamestnanec_id' => 3,
            ],
            [
                'zamestnanec_id' => 4,
            ]
        ];

        foreach ($priradene as $priradeny_zamestnanec) {
            PriradenyZamestnanec::create(array(
                'zamestnanec_id' => $priradeny_zamestnanec['zamestnanec_id']
            ));
        }
    }
}
