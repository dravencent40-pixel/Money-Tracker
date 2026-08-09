<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        $expenseCategories = ['Makanan', 'Transportasi', 'Tagihan', 'Jajan', 'Kebutuhan', 'Lain-lain'];
        foreach ($expenseCategories as $name) {
            Category::firstOrCreate(['name' => $name, 'type' => 'expense']);
        }

        Category::firstOrCreate(['name' => 'Gaji/Kiriman', 'type' => 'income']);
        Category::firstOrCreate(['name' => 'Lain-lain', 'type' => 'income']);

        Wallet::firstOrCreate(['name' => 'Cash'], ['type' => 'cash', 'starting_balance' => 0]);
        Wallet::firstOrCreate(['name' => 'Bank'], ['type' => 'bank', 'starting_balance' => 0]);
        Wallet::firstOrCreate(['name' => 'E-Wallet'], ['type' => 'ewallet', 'starting_balance' => 0]);
    }
}
