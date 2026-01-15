<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;

// Halaman pertama (root) arahkan ke login
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Route untuk transaksi (harus login)
Route::middleware('auth')->group(function () {
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class)->only(['create', 'store']);

    // Route untuk anggaran - menggunakan resource routes
    Route::resource('budget', BudgetController::class)->except(['show']);
    
    // Route tambahan untuk mendapatkan data anggaran dalam format JSON
    Route::get('/budget/data/json', [BudgetController::class, 'getBudgetData'])->name('budget.data');

        // Target Tabungan
    Route::get('/tabungan', function () {
        return view('tabungan');
    })->name('tabungan.index');
});