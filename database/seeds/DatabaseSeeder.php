<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

                $this->call(KategoriaProblemuSeeder::class);
                $this->call(StavProblemuSeeder::class);
                $this->call(TypStavuRieseniaProblemuSeeder::class);
                $this->call(VozidloSeeder::class);
                $this->call(RolaSeeder::class);
                $this->call(PrioritaSeeder::class);
                $this->call(UserSeeder::class);
                $this->call(ProblemSeeder::class);
                $this->call(StavRieseniaProblemuSeeder::class);

    }
}
