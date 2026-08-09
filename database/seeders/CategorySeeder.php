<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = ['Makan', 'Transport', 'Jajan', 'Kebutuhan', 'Makeup', 'Lain-lain'];

        foreach ($defaults as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
