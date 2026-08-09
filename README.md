# Money Tracker

App sederhana buat tracking pemasukan/pengeluaran + budget bulanan & mingguan per kategori. Tanpa login, karena cuma dipakai sendiri.

## Cara Install

1. Buat project Laravel baru:
   ```
   composer create-project laravel/laravel money-tracker
   cd money-tracker
   ```

2. Salin (timpa) folder-folder ini dari hasil unduhan ke dalam project Laravel yang baru dibuat:
   - `app/Models/`
   - `app/Http/Controllers/`
   - `database/migrations/` (tambahkan file migration ini, jangan hapus yang bawaan Laravel seperti `users` table)
   - `database/seeders/CategorySeeder.php`
   - `routes/web.php` (timpa yang lama)
   - `resources/views/` (tambahkan folder `dashboard`, `transactions`, `categories`, `budgets`, dan timpa `layouts/app.blade.php`)

3. Atur koneksi PostgreSQL di file `.env`:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=money_tracker
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```
   Pastikan sudah bikin database `money_tracker` di PostgreSQL dulu.

4. Jalankan migration & seeder kategori default:
   ```
   php artisan migrate
   php artisan db:seed --class=CategorySeeder
   ```
   (Kalau mau, tambahkan `CategorySeeder::class` ke dalam `database/seeders/DatabaseSeeder.php` biar bisa `php artisan migrate --seed`.)

5. Jalankan server:
   ```
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000`.

## Alur Pakai

1. Buka **Kategori** → pastikan 6 kategori default sudah ada (Makan, Transport, Jajan, Kebutuhan, Makeup, Lain-lain), bisa tambah kategori baru kapan saja.
2. Buka **Budget** → isi budget bulanan (pilih bulannya) dan budget mingguan (isi tanggal Senin minggu yang mau diatur). Boleh kosongin kalau belum mau nentuin budget untuk kategori tertentu.
3. Tambah transaksi pemasukan/pengeluaran lewat **Transaksi**.
4. Lihat progressnya di **Dashboard** — otomatis menghitung total terpakai vs budget per kategori, untuk bulan berjalan dan minggu berjalan.

## Catatan

- Budget mingguan **tidak** otomatis dibagi dari budget bulanan — memang sengaja dipisah biar bisa diatur manual sesuai kebutuhan tiap minggu.
- Kalau kategori dihapus, transaksi lama yang pakai kategori itu jadi "-" (bukan ikut kehapus).
- UI sengaja dibikin polos dulu, gampang dipercantik belakangan (tinggal edit `resources/views/layouts/app.blade.php` buat ganti style).
