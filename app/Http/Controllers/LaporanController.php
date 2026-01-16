<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Savings;
use App\Models\Budget;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        
        // Data untuk bulan ini
        $startOfMonth = $now->startOfMonth();
        $endOfMonth = $now->endOfMonth();
        
        // Hitung total pemasukan dan pengeluaran
        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
            
        $totalExpense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
            
        $totalSavings = Savings::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('saved_amount');
            
        $netBalance = $totalIncome - $totalExpense;
        
        // Data untuk chart 6 bulan terakhir
        $months = [];
        $incomes = [];
        $expenses = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $months[] = $month->format('M');
            
            $monthStart = $month->startOfMonth();
            $monthEnd = $month->endOfMonth();
            
            $monthIncome = Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');
                
            $monthExpense = Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');
                
            $incomes[] = $monthIncome / 1000000; // Convert to millions
            $expenses[] = $monthExpense / 1000000; // Convert to millions
        }
        
        // Transaksi terbaru
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        
        // Analisis anggaran
        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $now->format('Y-m'))
            ->get();
            
        // Distribusi pengeluaran berdasarkan kategori
        $expenseByCategory = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($transactions) {
                return $transactions->sum('amount');
            });

        return view('laporan', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalSavings' => $totalSavings,
            'netBalance' => $netBalance,
            'months' => $months,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'recentTransactions' => $recentTransactions,
            'budgets' => $budgets,
            'expenseByCategory' => $expenseByCategory,
        ]);
    }
    
    public function export(Request $request)
    {
        $type = $request->type; // pdf, excel, csv
        $period = $request->period; // month, year, custom
        
        // Implement export logic here
        // This would generate and download the report
        
        return back()->with('success', 'Laporan berhasil diexport!');
    }
}