<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'ASUS',
            'Dell',
            'HP',
            'Lenovo',
            'Acer',
            'MSI',
            'Apple',
            'Samsung',
            'Logitech',
            'Corsair',
            'Kingston',
            'Intel',
            'AMD',
            'Gigabyte',
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand,
                'description' => 'Computer hardware and accessories brand for modern work and gaming setups.',
                'logo_url' => 'https://placehold.co/200x200/111827/ffffff?text='.urlencode($brand),
            ]);
        }
    }
}
