<?php

use Illuminate\Database\Seeder;
use App\User;

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
                'id' => 0,
                'name' => 'Nezaregistrovany obcan',
                'email' => '-',
                'password' => '-',
                'rola_id' => 2
            ]
        ];

        foreach ($users as $user) {
            User::create(array(
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'rola_id' => $user['rola_id']
            ));
        }
    }
}
