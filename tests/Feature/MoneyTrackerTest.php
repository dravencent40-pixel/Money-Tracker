<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MoneyTrackerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_user_can_register_and_see_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->get('/')->assertOk()->assertSee('Dashboard');
    }

    public function test_new_user_starts_with_default_data_and_zero_balance(): void
    {
        $response = $this->post('/register', [
            'name' => 'Siti',
            'email' => 'siti@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $userId = User::where('email', 'siti@example.com')->value('id');

        $this->assertSame(3, DB::table('wallets')->where('user_id', $userId)->count());
        $this->assertSame(8, DB::table('categories')->where('user_id', $userId)->count());
        $this->assertSame(0.0, (float) Wallet::forUser($userId)->sum('starting_balance'));
        $this->assertSame(0.0, (float) DB::table('transactions')->where('user_id', $userId)->sum('amount'));
    }

    public function test_user_can_create_a_transaction(): void
    {
        $user = User::factory()->create();
        $wallet = Wallet::create(['user_id' => $user->id, 'name' => 'Cash', 'type' => 'cash', 'starting_balance' => 0]);
        $category = Category::create(['user_id' => $user->id, 'name' => 'Makanan', 'type' => 'expense']);

        $response = $this->actingAs($user)->post('/transactions', [
            'wallet_id' => $wallet->id,
            'type' => 'expense',
            'category_id' => $category->id,
            'amount' => 25000,
            'date' => now()->toDateString(),
            'note' => 'Nasi padang',
        ]);

        $response->assertRedirect('/transactions');
        $this->assertDatabaseHas('transactions', ['user_id' => $user->id, 'amount' => 25000, 'note' => 'Nasi padang']);
    }

    public function test_users_cannot_see_each_others_data(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $walletA = Wallet::create(['user_id' => $userA->id, 'name' => 'Cash A', 'type' => 'cash', 'starting_balance' => 100000]);
        Category::create(['user_id' => $userA->id, 'name' => 'Makanan', 'type' => 'expense']);

        $this->actingAs($userB)
            ->get('/')
            ->assertOk()
            ->assertDontSee('Cash A');

        $this->actingAs($userB)
            ->delete('/wallets/'.$walletA->id)
            ->assertNotFound();

        $this->assertDatabaseHas('wallets', ['id' => $walletA->id, 'user_id' => $userA->id]);
    }

    public function test_report_csv_export_works(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/reports/export')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_wallet_balance_accessor_uses_loaded_sums(): void
    {
        $wallet = Wallet::create(['name' => 'Cash', 'type' => 'cash', 'starting_balance' => 100000]);
        $wallet->transactions()->create(['type' => 'income', 'amount' => 50000, 'date' => now()]);
        $wallet->transactions()->create(['type' => 'expense', 'amount' => 30000, 'date' => now()]);

        $loaded = Wallet::allWithBalance()->first();

        $this->assertSame(120000.0, $loaded->current_balance);
        $this->assertSame(120000.0, $wallet->fresh()->current_balance);
    }
}
