<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttr;
use App\Models\ProductAttrItem;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        ProductAttr::truncate();
        ProductAttrItem::truncate();
        ProductItem::truncate();

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 3; $i++) {
            Product::create([
                'slug' => Str::random(5),
                'name' => $faker->name,
                'brand' => $faker->name,
                'cost' => '500000',
                'quatity' => '50',
                'discription' => $faker->paragraph,
                'use' => $faker->paragraph
            ]);
        }

        ProductAttr::create([
            'product_id' => 2,
            'name' => 'Màu sắc',
            'slug' => 'mau-sac'
        ]);

        ProductAttr::create([
            'product_id' => 2,
            'name' => 'Dung lượng',
            'slug' => 'dung-luong'
        ]);

        ProductAttr::create([
            'product_id' => 3,
            'name' => 'Dung lượng',
            'slug' => 'dung-luong'
        ]);

        ProductAttrItem::create([
            'product_attr_id' => 1,
            'name' => 'Đỏ',
            'slug' => 'do'
        ]);

        ProductAttrItem::create([
            'product_attr_id' => 1,
            'name' => 'Đen',
            'slug' => 'den'
        ]);

        ProductAttrItem::create([
            'product_attr_id' => 2,
            'name' => '100ml',
            'slug' => '100ml'
        ]);

        ProductAttrItem::create([
            'product_attr_id' => 2,
            'name' => '150ml',
            'slug' => '150ml'
        ]);

        ProductAttrItem::create([
            'product_attr_id' => 3,
            'name' => '100ml',
            'slug' => '100ml'
        ]);

        ProductAttrItem::create([
            'product_attr_id' => 3,
            'name' => '150ml',
            'slug' => '150ml'
        ]);

        ProductItem::create([
            'product_id' => 2,
            'main_attr_id' => 1,
            'sub_attr_id' => 3,
            'cost' => 100000,
            'quatity' => 5
        ]);

        ProductItem::create([
            'product_id' => 2,
            'main_attr_id' => 1,
            'sub_attr_id' => 4,
            'cost' => 100000,
            'quatity' => 5
        ]);

        ProductItem::create([
            'product_id' => 2,
            'main_attr_id' => 2,
            'sub_attr_id' => 3,
            'cost' => 100000,
            'quatity' => 5
        ]);

        ProductItem::create([
            'product_id' => 2,
            'main_attr_id' => 2,
            'sub_attr_id' => 4,
            'cost' => 100000,
            'quatity' => 5
        ]);

        ProductItem::create([
            'product_id' => 3,
            'main_attr_id' => 5,
            'cost' => 100000,
            'quatity' => 5
        ]);

        ProductItem::create([
            'product_id' => 3,
            'main_attr_id' => 6,
            'cost' => 100000,
            'quatity' => 5
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
