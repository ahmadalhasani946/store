<?php

namespace Database\Seeders;

use App\Models\OptionVariantProduct;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produts = Product::all();

        OrderProduct::factory()->has(OptionVariantProductOrderProduct::factory()->count(2))->create();
    }
}
