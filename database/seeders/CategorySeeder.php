<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology'],
            ['name' => 'Health'],
            ['name' => 'Lifestyle'],
            ['name' => 'Business'],
            ['name' => 'Entertainment']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
