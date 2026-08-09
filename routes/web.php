<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('transactions', TransactionController::class)->except(['show']);

Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

Route::get('budgets', [BudgetController::class, 'index'])->name('budgets.index');
Route::post('budgets/monthly', [BudgetController::class, 'storeMonthly'])->name('budgets.monthly.store');
Route::post('budgets/weekly', [BudgetController::class, 'storeWeekly'])->name('budgets.weekly.store');
