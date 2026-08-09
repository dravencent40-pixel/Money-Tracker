# Money Tracker (v2 — Full Spec)

Personal Money Tracker / Budget Planner. Laravel + Blade + Tailwind (CDN) + PostgreSQL. Tanpa login.

## Struktur

- **Migrations:** `categories`, `wallets`, `transactions`, `budgets` — semua nominal pakai `decimal(15,2)`.
- **Models:** `Category`, `Wallet`, `Transaction`, `Budget` dengan relasi & scope (`inMonth`, `income`, `expense`).
- **Controllers:** `DashboardController`, `TransactionController`, `CategoryController`, `WalletController`, `BudgetController`, `ReportController`.
- **Views:** Tailwind via CDN (`cdn.tailwindcss.com`) — tanpa build step, langsung jalan.
- **Grafik:** Chart.js via CDN, dipakai di dashboard untuk pie chart pengeluaran per kategori.

## Cara Install

1. Buat project Laravel baru:
   ```
   composer create-project laravel/laravel money-tracker
   cd money-tracker
   ```

2. Salin/timpa folder-folder berikut dari hasil unduhan ke project barunya:
   - `app/Models/`
   - `app/Http/Controllers/`
   - `database/migrations/` (tambahkan, jangan hapus migration bawaan seperti `users`)
   - `database/seeders/DefaultDataSeeder.php`
   - `routes/web.php` (timpa)
   - `resources/views/` (timpa seluruhnya)

3. Set `.env` ke PostgreSQL:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=money_tracker
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

4. Migrate & seed:
   ```
   php artisan migrate
   php artisan db:seed --class=DefaultDataSeeder
   ```

5. Jalankan:
   ```
   php artisan serve
   ```

## Catatan Desain

- **Kategori punya tipe** (income/expense) — form transaksi otomatis filter kategori sesuai tipe yang dipilih (JS di `transactions/_form.blade.php`).
- **Dompet** (`wallets`) tidak bisa dihapus kalau masih ada transaksinya (`WalletController::destroy`) — proteksi biar histori keuangan gak hilang gak sengaja.
- **Saldo dompet** dihitung on-the-fly dari `starting_balance + income - expense` (accessor `getCurrentBalanceAttribute` di model `Wallet`), bukan kolom tersimpan — jadi selalu akurat tanpa perlu sinkronisasi manual.
- **Budget alert**: warna progress bar berubah — hijau (aman), kuning (≥80% dari limit), merah (lewat limit) — logikanya di `DashboardController::index`.
- Semua halaman pakai Tailwind CDN (bukan build via Vite) supaya deploy-nya simpel tanpa perlu `npm run build`. Kalau nanti mau UI lebih custom/production-grade, bisa migrasi ke Tailwind via Vite.
