<?php

use App\Models\FotkaProblemu;
use Illuminate\Database\Seeder;

class FotkaProblemuSeeder extends Seeder
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
                'nazov_suboru' => 'fotka1.png',
                'problem_id' => 1
            ],
            [
                'nazov_suboru' => 'fotka2.png',
                'problem_id' => 1
            ],
            [
                'nazov_suboru' => 'fotka3.png',
                'problem_id' => 1
            ]
        ];

        foreach ($fotky as $fotka_problemu) {
            FotkaProblemu::create(array(
                'nazov_suboru' => $fotka_problemu['nazov_suboru'],
                'problem_id' => $fotka_problemu['problem_id']
            ));
        }
    }
}
