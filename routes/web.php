<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;

// Halaman pertama (root) arahkan ke login
Route::get('/', function () {
    return redirect('/login');
});

// Auth Routes (Public)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/quick-stats', [DashboardController::class, 'getQuickStats'])->name('dashboard.quick-stats');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class)->only(['create', 'store']);
    
    // Budget
    Route::resource('budget', BudgetController::class)->except(['show']);
    Route::get('/budget/data/json', [BudgetController::class, 'getBudgetData'])->name('budget.data');
    
    // Savings
    Route::resource('tabungan', SavingsController::class);
    Route::post('/tabungan/{tabungan}/add-savings', [SavingsController::class, 'addSavings'])->name('tabungan.addSavings');
    
    // Reports
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [ReportController::class, 'export'])->name('laporan.export');
    
    // Analysis
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::get('/analysis/chart-data', [AnalysisController::class, 'getChartData'])->name('analysis.chart.data');
    Route::get('/analysis/export', [AnalysisController::class, 'exportAnalysis'])->name('analysis.export');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});