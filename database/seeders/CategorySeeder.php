<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Category 1'],
            ['name' => 'Category 2'],
            ['name' => 'Category 3'],
            ['name' => 'Category 4'],
            ['name' => 'Category 5'],
        ];

        foreach ($data as $item) {
            Category::updateOrCreate(
                ['name' => $item['name']],
                [
                    'name'      => $item['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
