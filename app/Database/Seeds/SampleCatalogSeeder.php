<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SampleProductsSeeder::class);
        $this->call(SampleSuppliersSeeder::class);
    }
}
