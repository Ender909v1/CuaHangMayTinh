<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parents = [
            'Laptop',
            'Desktop PC',
            'Monitor',
            'Components',
            'Peripheral',
            'Storage',
            'Accessories',
        ];

        $parentIds = [];

        foreach ($parents as $parent) {
            $parentIds[] = DB::table('categories')->insertGetId([
                'name' => $parent,
                'parent_id' => null,
            ]);
        }

        $children = [
            ['Gaming Laptop', $parentIds[0]],
            ['Business Laptop', $parentIds[0]],
            ['Creator PC', $parentIds[1]],
            ['Gaming PC', $parentIds[1]],
            ['4K Monitor', $parentIds[2]],
            ['Gaming Monitor', $parentIds[2]],
            ['CPU', $parentIds[3]],
            ['GPU', $parentIds[3]],
            ['RAM', $parentIds[3]],
            ['Keyboard', $parentIds[4]],
            ['Mouse', $parentIds[4]],
            ['Headset', $parentIds[4]],
            ['SSD', $parentIds[5]],
            ['HDD', $parentIds[5]],
            ['USB', $parentIds[6]],
            ['Cables', $parentIds[6]],
        ];

        foreach ($children as [$name, $parentId]) {
            DB::table('categories')->insert([
                'name' => $name,
                'parent_id' => $parentId,
            ]);
        }
    }
}
