# CashFlow — Personal Money Tracker

Aplikasi pencatat keuangan pribadi (personal finance / budget planner) berbasis **Laravel + Blade + PostgreSQL**.

## Fitur

- **Auth**: register & login pakai session bawaan Laravel (bukan Breeze/Jetstream); semua route aplikasi dilindungi `auth`.
- **Dashboard**: ringkasan budget (aman / warning / over / belum diset), top kategori pengeluaran, donut chart pengeluaran per kategori, komposisi saldo per dompet, tren 5 bulan, estimasi runway, dan transaksi terbaru.
- **Transaksi**: tambah/ubah/hapus, pencarian + filter (bulan, tipe, kategori, dompet), summary income/expense/net, quick-add dari modal global.
- **Kategori**: tipe income/expense + pilihan ikon (emoji).
- **Dompet**: saldo dihitung on-the-fly dari `starting_balance + income - expense`; proteksi tidak bisa dihapus jika masih punya transaksi; bisa pilih ikon & warna.
- **Budget**: limit per kategori per bulan, progress spend, tombol salin budget dari bulan sebelumnya.
- **Laporan**: net balance, savings rate, breakdown per kategori, dan ekspor CSV.
- **Mobile-first**: sidebar di desktop, bottom navigation di mobile.

## Tech Stack

- **Backend**: Laravel (PHP), PostgreSQL
- **Frontend**: Blade + Tailwind CSS v4 via Vite, Chart.js, font Outfit & JetBrains Mono (Google Fonts CDN)
- **Locale**: Indonesia (`id`), timezone `Asia/Jakarta`

## Struktur

- **Migrations**: `categories`, `wallets`, `transactions`, `budgets` (+ `users` bawaan) — semua nominal `decimal(15,2)`.
- **Models**: `Category`, `Wallet`, `Transaction`, `Budget` dengan relasi & scope (`inMonth`, `income`, `expense`); `Wallet::allWithBalance()` untuk query bebas N+1.
- **Controllers**: `DashboardController`, `TransactionController`, `CategoryController`, `WalletController`, `BudgetController`, `ReportController`, `Auth/*`.
- **Views**: `resources/views/` — layouts (`app` dengan sidebar/bottom-nav), komponen Blade (`icon`, `flash`, `empty-state`, `month-picker`, `page-header`, `stat-card`).
- **CSS**: `resources/css/app.css` — design token (ink/cash/cost) + komponen reusable (`.btn*`, `.card`, `.input`, `.money`, `.badge*`).
- **Seeder**: `DefaultDataSeeder` untuk kategori & dompet awal.

## Cara Install (Local)

1. Clone repo & install dependencies:

   ```bash
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   ```

2. Set `.env` ke PostgreSQL:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=money_tracker
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

3. Migrate & seed:

   ```bash
   php artisan migrate
   php artisan db:seed --class=DefaultDataSeeder
   ```

4. Jalankan (2 terminal):

   ```bash
   npm run dev       # terminal 1 — Vite dev server
   php artisan serve # terminal 2 — Laravel
   ```

## Build & Test

```bash
npm run build          # produksi: Tailwind + JS ke public/build
php artisan test       # feature tests
vendor/bin/pint        # code style
php artisan view:cache # pre-compile blade (opsional)
```

> Catatan: `public/build` ada di `.gitignore` — di server production build harus dijalankan saat deploy (lihat di bawah).

## Deploy ke Railway

- **Build command**: `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
- **Start command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
- Post-deploy: `php artisan migrate --force && php artisan db:seed --class=DefaultDataSeeder --force`
- Tambahkan plugin PostgreSQL & attach volume ke `storage` jika perlu persistensi file.

## Catatan Desain

- **Data per user**: semua tabel data (`categories`, `wallets`, `transactions`, `budgets`) punya kolom `user_id`; model memakai global scope `BelongsToUser` sehingga setiap akun hanya melihat datanya sendiri. Saat registrasi, akun baru otomatis diberi kategori & dompet bawaan dengan saldo mulai dari 0.
- **Kategori punya tipe** (income/expense) — form transaksi otomatis memfilter kategori sesuai tipe yang dipilih.
- **Saldo dompet** dihitung on-the-fly dari transaksi, bukan kolom tersimpan — selalu akurat tanpa sinkronisasi manual.
- **Budget alert**: progress bar berubah warna — hijau (aman), kuning (≥80% limit), merah (over budget).
- **Perubahan DB** harus *additive migration* (menambah kolom/index) agar aman di-deploy ke produksi tanpa downtime.
