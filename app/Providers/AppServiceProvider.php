<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        // Data untuk modal quick-add transaksi (muncul di semua halaman).
        View::composer('layouts.app', function ($view) {
            $view->with('quickAddWallets', Wallet::allWithBalance());
            $view->with('quickAddCategories', Category::orderBy('type')->orderBy('name')->get());
        });
    }
}
