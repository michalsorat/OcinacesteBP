<?php

use App\Rola;
use Illuminate\Database\Seeder;

class RolaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roly = [
            [
                'nazov' => 'Zaregistrovaný občan',
            ],
            [
                'nazov' => 'Nezaregistrovaný občan',
            ],
            [
                'nazov' => 'Administrátor',
            ],
            [
                'nazov' => 'Zamestnanec',
            ],
            [
                'nazov' => 'Manažér'
            ]
        ];

        foreach ($roly as $rola) {
            Rola::create(array(
                'nazov' => $rola['nazov']
            ));
        }
    }
}
