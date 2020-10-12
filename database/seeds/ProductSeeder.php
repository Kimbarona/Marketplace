<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
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
            DB::table('products')->insert([
                'store_code' => $faker->unique()->randomNumber,
                'product_code' => $faker->unique()->randomNumber,
                'category_name' => $faker->word,
                'product_name' => $faker->name,
                'produc_image' => $faker->imageUrl($width = 640, $height = 480),
                'product_price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL)

            ]);
        }

    }
}
