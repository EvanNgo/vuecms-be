<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        $faker = \Faker\Factory::create();

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'product_id' => Str::random(32),
                'name' => $faker->name,
                'brand' => $faker->name,
                'cost' => '500000',
                'quatity' => '50',
                'discription' => $faker->paragraph,
                'use' => $faker->paragraph
            ]);
        }
    }
}
