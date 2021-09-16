<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OptionVariantProductOrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OptionVariantProductOrderProduct::factory()->create();
    }
}
