<?php

use Illuminate\Database\Seeder;

class WallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        
        for ($i = 0; $i < 10; $i++) {
            \App\Wall::create([
                'user_id' => 1,
                'text' => $faker->text(200)
            ]);
        }
    }
}
