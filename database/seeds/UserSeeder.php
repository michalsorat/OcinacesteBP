<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'System admin',
                'email' => 'system@admin',
                'password' => 'zmenteSIHESLO68',
                'rola_id' => 3
            ],

            [
                'name' => 'Nezaregistrovaný občan',
                'email' => '-',
                'password' => '-',
                'rola_id' => 2
            ]


        ];

        foreach ($users as $user) {
            User::create(array(

                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'rola_id' => $user['rola_id']
            ));
        }
    }
}
