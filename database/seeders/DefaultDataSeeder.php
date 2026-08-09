<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
{
    /**
     * Seed default categories & wallets untuk semua user (atau user pertama jika tak ada).
     */
    public function run(): void
    {
        $user = User::orderBy('id')->first();

        if ($user) {
            $this->seedFor($user);
        }
    }

    /**
     * Buat kategori & dompet bawaan untuk satu user (dipakai saat registrasi).
     */
    public static function seedFor(User $user): void
    {
        $expenseCategories = [
            'Makanan' => '🍜',
            'Transportasi' => '🚗',
            'Tagihan' => '🧾',
            'Jajan' => '🍿',
            'Kebutuhan' => '🛒',
            'Lain-lain' => '📦',
        ];
        foreach ($expenseCategories as $name => $icon) {
            Category::updateOrCreate(
                ['user_id' => $user->id, 'name' => $name, 'type' => 'expense'],
                ['icon' => $icon]
            );
        }

        Category::updateOrCreate(['user_id' => $user->id, 'name' => 'Gaji/Kiriman', 'type' => 'income'], ['icon' => '💰']);
        Category::updateOrCreate(['user_id' => $user->id, 'name' => 'Lain-lain', 'type' => 'income'], ['icon' => '💵']);

        Wallet::firstOrCreate(['user_id' => $user->id, 'name' => 'Cash'], ['type' => 'cash', 'starting_balance' => 0]);
        Wallet::firstOrCreate(['user_id' => $user->id, 'name' => 'Bank'], ['type' => 'bank', 'starting_balance' => 0]);
        Wallet::firstOrCreate(['user_id' => $user->id, 'name' => 'E-Wallet'], ['type' => 'ewallet', 'starting_balance' => 0]);
    }
}
