<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Savings;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        
        // ===== STATISTIK UTAMA =====
        // Pemasukan Bulan Ini
        $currentMonthIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');
        
        // Pengeluaran Bulan Ini
        $currentMonthExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');
        
        // Saldo (Pemasukan - Pengeluaran)
        $currentMonthBalance = $currentMonthIncome - $currentMonthExpense;
        
        // Target Tabungan (Total saved amount)
        $totalSavings = Savings::where('user_id', $userId)->sum('saved_amount');
        $totalSavingsTarget = Savings::where('user_id', $userId)->sum('target_amount');
        $savingsPercentage = $totalSavingsTarget > 0 ? ($totalSavings / $totalSavingsTarget) * 100 : 0;
        
        // ===== PERBANDINGAN DENGAN BULAN LALU =====
        $lastMonth = $now->copy()->subMonth();
        
        $lastMonthIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereMonth('transaction_date', $lastMonth->month)
            ->whereYear('transaction_date', $lastMonth->year)
            ->sum('amount');
        
        $lastMonthExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereMonth('transaction_date', $lastMonth->month)
            ->whereYear('transaction_date', $lastMonth->year)
            ->sum('amount');
        
        // Hitung persentase perubahan
        $incomeChange = $this->calculatePercentageChange($currentMonthIncome, $lastMonthIncome);
        $expenseChange = $this->calculatePercentageChange($currentMonthExpense, $lastMonthExpense);
        $lastMonthBalance = $lastMonthIncome - $lastMonthExpense;
        $balanceChange = $this->calculatePercentageChange($currentMonthBalance, $lastMonthBalance);
        
        // ===== DATA UNTUK GRAFIK 6 BULAN TERAKHIR =====
        $financialChart = $this->getFinancialChartData($userId, 6);
        
        // ===== KATEGORI PENGELUARAN BULAN INI =====
        $expenseCategories = $this->getExpenseCategories($userId);
        
        // ===== TRANSAKSI TERBARU =====
        $recentTransactions = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // ===== BUDGET SUMMARY =====
        $budgetSummary = $this->getBudgetSummary($userId);
        
        // ===== SAVINGS PROGRESS =====
        $savingsProgress = $this->getSavingsProgress($userId);
        
        return view('dashboard', compact(
            'currentMonthIncome',
            'currentMonthExpense',
            'currentMonthBalance',
            'totalSavings',
            'totalSavingsTarget',
            'savingsPercentage',
            'incomeChange',
            'expenseChange',
            'balanceChange',
            'financialChart',
            'expenseCategories',
            'recentTransactions',
            'budgetSummary',
            'savingsProgress'
        ));
    }
    
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        $change = (($current - $previous) / $previous) * 100;
        return round($change, 1);
    }
    
    private function getFinancialChartData($userId, $months = 6)
    {
        $data = [
            'labels' => [],
            'income' => [],
            'expense' => [],
            'balance' => []
        ];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M');
            
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
            
            $data['labels'][] = $monthName;
            $data['income'][] = (int)$income;
            $data['expense'][] = (int)$expense;
            $data['balance'][] = (int)($income - $expense);
        }
        
        return $data;
    }
    
    private function getExpenseCategories($userId)
    {
        $currentMonth = Carbon::now();
        
        $categories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->get();
        
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereMonth('transaction_date', $currentMonth->month)
            ->whereYear('transaction_date', $currentMonth->year)
            ->sum('amount');
        
        $categoryData = [];
        
        foreach ($categories as $category) {
            $amount = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->where('transaction_type', 'expense')
                ->whereMonth('transaction_date', $currentMonth->month)
                ->whereYear('transaction_date', $currentMonth->year)
                ->sum('amount');
            
            if ($amount > 0) {
                $percentage = $totalExpense > 0 ? round(($amount / $totalExpense) * 100, 1) : 0;
                
                $categoryData[] = [
                    'name' => $category->category_name,
                    'amount' => $amount,
                    'percentage' => $percentage,
                    'color' => $this->getCategoryColor($category->category_name)
                ];
            }
        }
        
        // Sort by amount descending
        usort($categoryData, function($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        
        // Limit to top 5 categories
        $categoryData = array_slice($categoryData, 0, 5);
        
        // Calculate "Lainnya" if there are more categories
        $totalShown = array_sum(array_column($categoryData, 'amount'));
        $othersAmount = $totalExpense - $totalShown;
        
        if ($othersAmount > 0) {
            $othersPercentage = $totalExpense > 0 ? round(($othersAmount / $totalExpense) * 100, 1) : 0;
            $categoryData[] = [
                'name' => 'Lainnya',
                'amount' => $othersAmount,
                'percentage' => $othersPercentage,
                'color' => '#8B5CF6' // Purple
            ];
        }
        
        return $categoryData;
    }
    
    private function getCategoryColor($categoryName)
    {
        $colors = [
            'Makan & Minum' => '#EF4444', // red
            'Transportasi' => '#3B82F6', // blue
            'Tagihan' => '#10B981', // green
            'Hiburan' => '#F59E0B', // yellow
            'Belanja' => '#EC4899', // pink
            'Kesehatan' => '#06B6D4', // cyan
            'Pendidikan' => '#8B5CF6', // purple
            'Gaji' => '#84CC16', // lime
            'Usaha' => '#F97316', // orange
            'Investasi' => '#14B8A6', // teal
        ];
        
        foreach ($colors as $key => $color) {
            if (stripos($categoryName, $key) !== false) {
                return $color;
            }
        }
        
        // Default colors if not found
        $defaultColors = ['#EF4444', '#3B82F6', '#10B981', '#F59E0B', '#EC4899', '#06B6D4', '#8B5CF6'];
        return $defaultColors[array_rand($defaultColors)];
    }
    
    private function getBudgetSummary($userId)
    {
        // PERBAIKAN: Hapus where('status', 'active') karena tidak ada di tabel budgets
        $budgets = Budget::where('user_id', $userId)->get();
        
        $summary = [
            'total_budget' => 0,
            'total_spent' => 0,
            'budgets' => [],
            'overall_percentage' => 0
        ];
        
        foreach ($budgets as $budget) {
            $spent = Transaction::where('user_id', $userId)
                ->where('budget_id', $budget->id)
                ->where('transaction_type', 'expense')
                ->sum('amount');
            
            $remaining = max(0, $budget->amount - $spent);
            $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
            
            $summary['budgets'][] = [
                'name' => $budget->category_name,
                'budget' => $budget->amount,
                'spent' => $spent,
                'remaining' => $remaining,
                'percentage' => round($percentage, 1),
                'status' => $percentage >= 100 ? 'exceeded' : ($percentage >= 80 ? 'warning' : 'good')
            ];
            
            $summary['total_budget'] += $budget->amount;
            $summary['total_spent'] += $spent;
        }
        
        if ($summary['total_budget'] > 0) {
            $summary['overall_percentage'] = round(($summary['total_spent'] / $summary['total_budget']) * 100, 1);
        }
        
        return $summary;
    }
    
    private function getSavingsProgress($userId)
    {
        // PERBAIKAN: Cek apakah kolom status ada di tabel savings
        // Jika tidak ada, hapus where('status', 'active')
        try {
            $savings = Savings::where('user_id', $userId)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            // Jika error karena kolom status tidak ada, ambil semua tanpa filter
            $savings = Savings::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        }
        
        $progress = [];
        
        foreach ($savings as $saving) {
            $percentage = $saving->target_amount > 0 
                ? ($saving->saved_amount / $saving->target_amount) * 100 
                : 0;
            
            $daysLeft = $saving->end_date ? $saving->end_date->diffInDays(Carbon::now()) : 0;
            
            $progress[] = [
                'name' => $saving->target_name,
                'saved' => $saving->saved_amount,
                'target' => $saving->target_amount,
                'percentage' => round(min(100, $percentage), 1),
                'days_left' => $daysLeft,
                'status' => $percentage >= 100 ? 'completed' : 'active'
            ];
        }
        
        return $progress;
    }
    
    public function getQuickStats(Request $request)
    {
        $userId = Auth::id();
        $type = $request->get('type', 'monthly');
        
        $now = Carbon::now();
        $data = [];
        
        if ($type === 'weekly') {
            // Data minggu ini
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
            
            $data['income'] = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            $data['expense'] = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
                
            $data['period'] = 'Minggu Ini';
            
        } elseif ($type === 'yearly') {
            // Data tahun ini
            $data['income'] = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereYear('transaction_date', $now->year)
                ->sum('amount');
            
            $data['expense'] = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereYear('transaction_date', $now->year)
                ->sum('amount');
                
            $data['period'] = 'Tahun Ini';
            
        } else {
            // Data bulan ini (default)
            $data['income'] = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereMonth('transaction_date', $now->month)
                ->whereYear('transaction_date', $now->year)
                ->sum('amount');
            
            $data['expense'] = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'expense')
                ->whereMonth('transaction_date', $now->month)
                ->whereYear('transaction_date', $now->year)
                ->sum('amount');
                
            $data['period'] = 'Bulan Ini';
        }
        
        $data['balance'] = $data['income'] - $data['expense'];
        
        return response()->json($data);
    }
    
    public function getChartData(Request $request)
    {
        $userId = Auth::id();
        $period = $request->get('period', '6months');
        
        $months = 6;
        if ($period === '1year') {
            $months = 12;
        } elseif ($period === '3months') {
            $months = 3;
        }
        
        $chartData = $this->getFinancialChartData($userId, $months);
        
        return response()->json([
            'labels' => $chartData['labels'],
            'income' => $chartData['income'],
            'expense' => $chartData['expense'],
            'balance' => $chartData['balance']
        ]);
    }
}