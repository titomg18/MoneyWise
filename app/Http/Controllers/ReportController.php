<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Menampilkan halaman laporan utama
    public function index(Request $request)
    {
        $userId = Auth::id();
        $period = $request->get('periode', 'bulan_ini');
        
        // Tentukan rentang tanggal berdasarkan periode
        $dateRange = $this->getDateRange($period, $request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        
        // ===== STATISTIK UTAMA =====
        // Total Pemasukan (Income)
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Total Pengeluaran (Expense)
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Saldo Bersih
        $netBalance = $totalIncome - $totalExpense;
        
        // Total Tabungan
        $totalSavings = Savings::where('user_id', $userId)->sum('saved_amount');
        
        // Hitung persentase perubahan
        $incomePercentage = $this->calculatePercentageChange($userId, $startDate, $endDate, 'income');
        $expensePercentage = $this->calculatePercentageChange($userId, $startDate, $endDate, 'expense');
        $balancePercentage = $this->calculatePercentageChange($userId, $startDate, $endDate, 'balance');
        $savingsPercentage = $this->calculateSavingsPercentage($userId);
        
        // ===== DATA UNTUK GRAFIK =====
        // Trend keuangan 6 bulan terakhir
        $financialTrend = $this->getFinancialTrend($userId);
        
        // Distribusi pengeluaran per kategori
        $expenseDistribution = $this->getExpenseDistribution($userId, $startDate, $endDate);
        
        // ===== TRANSAKSI TERBARU =====
        $recentTransactions = Transaction::where('user_id', $userId)
            ->with(['category'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();
        
        // ===== ANALISIS BUDGET =====
        $budgetAnalysis = $this->getBudgetAnalysis($userId, $startDate, $endDate);
        
        // ===== ANALISIS TARGET TABUNGAN =====
        $savingsTargets = $this->getSavingsTargetAnalysis($userId);
        
        // ===== STATISTIK LAINNYA =====
        $avgIncome = $totalIncome > 0 ? $totalIncome / $this->getDaysInPeriod($startDate, $endDate) : 0;
        $avgExpense = $totalExpense > 0 ? $totalExpense / $this->getDaysInPeriod($startDate, $endDate) : 0;
        $savingsRatio = $totalIncome > 0 ? ($totalSavings / $totalIncome) * 100 : 0;
        
        return view('laporan', compact(
            'totalIncome',
            'totalExpense',
            'netBalance',
            'totalSavings',
            'incomePercentage',
            'expensePercentage',
            'balancePercentage',
            'savingsPercentage',
            'financialTrend',
            'expenseDistribution',
            'recentTransactions',
            'budgetAnalysis',
            'savingsTargets',
            'avgIncome',
            'avgExpense',
            'savingsRatio',
            'period',
            'startDate',
            'endDate'
        ));
    }
    
    // Fungsi untuk mendapatkan rentang tanggal berdasarkan periode
    private function getDateRange($period, $request)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'minggu_ini':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'bulan_lalu':
                $lastMonth = $now->copy()->subMonth();
                return [
                    'start' => $lastMonth->copy()->startOfMonth(),
                    'end' => $lastMonth->copy()->endOfMonth()
                ];
            case 'tahun_ini':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'custom':
                return [
                    'start' => Carbon::parse($request->get('start_date')),
                    'end' => Carbon::parse($request->get('end_date'))
                ];
            case 'bulan_ini':
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }
    
    // Hitung persentase perubahan dibanding periode sebelumnya
    private function calculatePercentageChange($userId, $startDate, $endDate, $type)
    {
        // Hitung hari dalam periode
        $daysInPeriod = $endDate->diffInDays($startDate) + 1;
        
        // Tanggal periode sebelumnya
        $prevStart = $startDate->copy()->subDays($daysInPeriod);
        $prevEnd = $startDate->copy()->subDay();
        
        // Query berdasarkan tipe
        if ($type === 'income') {
            $currentValue = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            $prevValue = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereBetween('transaction_date', [$prevStart, $prevEnd])
                ->sum('amount');
        } elseif ($type === 'expense') {
            $currentValue = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            $prevValue = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$prevStart, $prevEnd])
                ->sum('amount');
        } else { // balance
            $currentIncome = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            $currentExpense = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            $currentValue = $currentIncome - $currentExpense;
            
            $prevIncome = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereBetween('transaction_date', [$prevStart, $prevEnd])
                ->sum('amount');
            
            $prevExpense = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$prevStart, $prevEnd])
                ->sum('amount');
            
            $prevValue = $prevIncome - $prevExpense;
        }
        
        if ($prevValue == 0) {
            return $currentValue > 0 ? 100 : 0;
        }
        
        $percentage = (($currentValue - $prevValue) / $prevValue) * 100;
        return round($percentage, 2);
    }
    
    // Hitung persentase tabungan dari total penghasilan tahunan
    private function calculateSavingsPercentage($userId)
    {
        $currentYear = Carbon::now()->year;
        
        $yearlyIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');
        
        $totalSavings = Savings::where('user_id', $userId)->sum('saved_amount');
        
        if ($yearlyIncome == 0) return 0;
        
        return round(($totalSavings / $yearlyIncome) * 100, 2);
    }
    
    // Dapatkan data trend keuangan 6 bulan terakhir
    private function getFinancialTrend($userId)
    {
        $trend = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            
            $income = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');
            
            $expense = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');
            
            $trend[] = [
                'month' => $monthName,
                'income' => (int)$income,
                'expense' => (int)$expense,
                'balance' => (int)($income - $expense)
            ];
        }
        
        return $trend;
    }
    
    // Dapatkan distribusi pengeluaran per kategori
    private function getExpenseDistribution($userId, $startDate, $endDate)
    {
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        $distribution = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($items) use ($totalExpense) {
                $sum = $items->sum('amount');
                return [
                    'category' => $items->first()->category->category_name,
                    'amount' => (int)$sum,
                    'percentage' => $totalExpense > 0 ? round(($sum / $totalExpense) * 100, 2) : 0
                ];
            })
            ->sortByDesc('amount')
            ->values()
            ->take(5);
        
        return $distribution;
    }
    
    // Analisis pengeluaran vs budget
    private function getBudgetAnalysis($userId, $startDate, $endDate)
    {
        return Budget::where('user_id', $userId)
            ->with(['transactions' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate])
                  ->where('transaction_type', 'expense');
            }])
            ->get()
            ->map(function ($budget) {
                $spent = $budget->transactions->sum('amount');
                $remaining = $budget->amount - $spent;
                $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
                
                return [
                    'category' => $budget->category_name,
                    'budget' => (int)$budget->amount,
                    'spent' => (int)$spent,
                    'remaining' => (int)$remaining,
                    'percentage' => round(min(100, $percentage), 2),
                    'status' => $percentage > 100 ? 'exceeded' : ($percentage > 80 ? 'warning' : 'good')
                ];
            });
    }
    
    // Analisis target tabungan
    private function getSavingsTargetAnalysis($userId)
    {
        return Savings::where('user_id', $userId)
            ->where('status', 'active')
            ->get()
            ->map(function ($saving) {
                $percentage = $saving->target_amount > 0 
                    ? ($saving->saved_amount / $saving->target_amount) * 100 
                    : 0;
                
                $daysLeft = $saving->end_date ? $saving->end_date->diffInDays(Carbon::now()) : 0;
                
                return [
                    'target_name' => $saving->target_name,
                    'saved_amount' => (int)$saving->saved_amount,
                    'target_amount' => (int)$saving->target_amount,
                    'percentage' => round(min(100, $percentage), 2),
                    'days_left' => $daysLeft,
                    'status' => $percentage >= 100 ? 'achieved' : 'in_progress'
                ];
            });
    }
    
    // Hitung jumlah hari dalam periode
    private function getDaysInPeriod($startDate, $endDate)
    {
        $days = $endDate->diffInDays($startDate) + 1;
        return $days > 0 ? $days : 1;
    }
    
    // Export laporan (CSV/PDF)
    public function export(Request $request)
    {
        $period = $request->get('periode', 'bulan_ini');
        $dateRange = $this->getDateRange($period, $request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        
        $userId = Auth::id();
        
        // Ambil data
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->with('category')
            ->get();
        
        // Generate CSV
        $fileName = 'Laporan_Keuangan_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function () use ($totalIncome, $totalExpense, $transactions) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['LAPORAN KEUANGAN']);
            fputcsv($file, ['Generated at', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            // Summary
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Pemasukan', 'Rp ' . number_format($totalIncome, 0, ',', '.')]);
            fputcsv($file, ['Total Pengeluaran', 'Rp ' . number_format($totalExpense, 0, ',', '.')]);
            fputcsv($file, ['Saldo Bersih', 'Rp ' . number_format($totalIncome - $totalExpense, 0, ',', '.')]);
            fputcsv($file, []);
            
            // Transactions
            fputcsv($file, ['DAFTAR TRANSAKSI']);
            fputcsv($file, ['Tanggal', 'Deskripsi', 'Kategori', 'Tipe', 'Jumlah']);
            
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_date->format('Y-m-d'),
                    $transaction->description,
                    $transaction->category->category_name,
                    ucfirst($transaction->transaction_type),
                    'Rp ' . number_format($transaction->amount, 0, ',', '.')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
