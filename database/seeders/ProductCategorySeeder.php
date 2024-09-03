<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_categories')->insert([
            'key_identifier' => 'INBOUND',
            'name' => 'Inbound / Masuk',
        ]);

        DB::table('product_categories')->insert([
            'key_identifier' => 'OUTBOUND',
            'name' => 'Outbound / Keluar',
        ]);
    }
}
