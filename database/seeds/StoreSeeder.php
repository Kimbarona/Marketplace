<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $faker = Faker::create();

    foreach(range(1, 5) as $index){
        DB::table('manage_stores')->insert([
            'store_code' => $faker->unique()->randomNumber,
            'store_name' => $faker->name,
            'store_location' => $faker->address
        ]);
    }

    }
}
