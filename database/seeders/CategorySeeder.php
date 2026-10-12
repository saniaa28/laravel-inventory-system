<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Electronics', 'Office Supplies', 'Accessories', 'Furniture'] as $name) {
            Category::create(['name' => $name]);
        }
    }
}
