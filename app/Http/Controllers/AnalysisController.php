<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Savings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $period = $request->get('period', 'monthly'); // monthly, quarterly, yearly
        
        // Tentukan rentang tanggal berdasarkan periode
        $dateRange = $this->getDateRange($period);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        
        // ===== RINGKASAN ANALISIS =====
        $summary = $this->getSummary($userId, $startDate, $endDate);
        
        // ===== TREN KEUANGAN =====
        $financialTrend = $this->getFinancialTrend($userId);
        
        // ===== DISTRIBUSI PENGELUARAN =====
        $expenseDistribution = $this->getExpenseDistribution($userId, $startDate, $endDate);
        
        // ===== PERBANDINGAN BULANAN =====
        $monthlyComparison = $this->getMonthlyComparison($userId);
        
        // ===== ANALISIS KEBIASAAN =====
        $habitsAnalysis = $this->getHabitsAnalysis($userId, $startDate, $endDate);
        
        // ===== ANALISIS DETAIL KATEGORI =====
        $categoryAnalysis = $this->getCategoryAnalysis($userId, $startDate, $endDate);
        
        // ===== REKOMENDASI =====
        $recommendations = $this->getRecommendations($summary, $expenseDistribution, $habitsAnalysis);
        
        return view('analysis', compact(
            'summary',
            'financialTrend',
            'expenseDistribution',
            'monthlyComparison',
            'habitsAnalysis',
            'categoryAnalysis',
            'recommendations',
            'period',
            'startDate',
            'endDate'
        ));
    }
    
    private function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'quarterly':
                return [
                    'start' => $now->copy()->subMonths(3)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'yearly':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'all_time':
                return [
                    'start' => Auth::user()->created_at,
                    'end' => $now->copy()->endOfDay()
                ];
            case 'monthly':
            default:
                return [
                    'start' => $now->copy()->subMonths(6)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }
    
    private function getSummary($userId, $startDate, $endDate)
    {
        // Total Pemasukan & Pengeluaran Periode Ini
        $currentIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
            
        $currentExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Periode Sebelumnya (untuk perbandingan)
        $prevStart = $startDate->copy()->subMonths(6);
        $prevEnd = $startDate->copy()->subDay();
        
        $previousIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$prevStart, $prevEnd])
            ->sum('amount');
            
        $previousExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$prevStart, $prevEnd])
            ->sum('amount');
        
        // Hitung Persentase Perubahan
        $incomeChange = $previousIncome > 0 
            ? (($currentIncome - $previousIncome) / $previousIncome) * 100 
            : ($currentIncome > 0 ? 100 : 0);
            
        $expenseChange = $previousExpense > 0 
            ? (($currentExpense - $previousExpense) / $previousExpense) * 100 
            : ($currentExpense > 0 ? 100 : 0);
        
        // Hitung Rata-rata Pemasukan 6 Bulan Terakhir
        $averageIncome = $this->calculateAverageMonthlyIncome($userId);
        
        // Hitung Pengeluaran Terbesar
        $largestExpenseCategory = $this->getLargestExpenseCategory($userId, $startDate, $endDate);
        
        // Hitung Rasio Penghematan
        $savingsRate = $this->calculateSavingsRate($userId, $currentIncome, $currentExpense);
        
        return [
            'total_income' => $currentIncome,
            'total_expense' => $currentExpense,
            'income_change' => round($incomeChange, 1),
            'expense_change' => round($expenseChange, 1),
            'average_income' => $averageIncome,
            'largest_expense_category' => $largestExpenseCategory,
            'savings_rate' => $savingsRate,
            'net_balance' => $currentIncome - $currentExpense
        ];
    }
    
    private function calculateAverageMonthlyIncome($userId)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        
        $monthlyIncomes = [];
        
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            
            $monthlyIncome = Transaction::where('user_id', $userId)
                ->where('transaction_type', 'income')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');
                
            $monthlyIncomes[] = $monthlyIncome;
        }
        
        return count($monthlyIncomes) > 0 
            ? array_sum($monthlyIncomes) / count($monthlyIncomes) 
            : 0;
    }
    
    private function getLargestExpenseCategory($userId, $startDate, $endDate)
    {
        $categories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->get();
        
        $largestCategory = null;
        $largestAmount = 0;
        $totalExpense = 0;
        
        foreach ($categories as $category) {
            $amount = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
                
            $totalExpense += $amount;
            
            if ($amount > $largestAmount) {
                $largestAmount = $amount;
                $largestCategory = $category;
            }
        }
        
        $percentage = $totalExpense > 0 ? ($largestAmount / $totalExpense) * 100 : 0;
        
        return [
            'name' => $largestCategory ? $largestCategory->category_name : 'Belum ada data',
            'amount' => $largestAmount,
            'percentage' => round($percentage, 1)
        ];
    }
    
    private function calculateSavingsRate($userId, $income, $expense)
    {
        if ($income <= 0) return 0;
        
        // Hitung tabungan yang berhasil dikumpulkan
        $totalSavings = Savings::where('user_id', $userId)
            ->sum('saved_amount');
            
        // Hitung rasio penghematan berdasarkan selisih pemasukan dan pengeluaran
        $savings = max(0, $income - $expense);
        $savingsRate = ($savings / $income) * 100;
        
        // Juga hitung dari tabungan aktual
        $actualSavingsRate = $income > 0 ? ($totalSavings / $income) * 100 : 0;
        
        return round(max($savingsRate, $actualSavingsRate), 1);
    }
    
    private function getFinancialTrend($userId)
    {
        $trendData = [];
        
        for ($i = 5; $i >= 0; $i--) {
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
            
            $trendData[] = [
                'month' => $monthName,
                'income' => (int)$income,
                'expense' => (int)$expense,
                'balance' => (int)($income - $expense)
            ];
        }
        
        return $trendData;
    }
    
    private function getExpenseDistribution($userId, $startDate, $endDate)
    {
        $categories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->get();
        
        $distribution = [];
        $totalExpense = 0;
        
        foreach ($categories as $category) {
            $amount = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            if ($amount > 0) {
                $distribution[] = [
                    'category' => $category->category_name,
                    'amount' => $amount,
                    'color' => $category->color ?? $this->getRandomColor()
                ];
                $totalExpense += $amount;
            }
        }
        
        // Hitung persentase untuk setiap kategori
        foreach ($distribution as &$item) {
            $item['percentage'] = $totalExpense > 0 
                ? round(($item['amount'] / $totalExpense) * 100, 1) 
                : 0;
        }
        
        // Urutkan berdasarkan jumlah terbesar
        usort($distribution, function($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        
        return $distribution;
    }
    
    private function getMonthlyComparison($userId)
    {
        $monthsData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            
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
                
            $monthsData[$month->format('Y-m')] = [
                'income' => $income,
                'expense' => $expense,
                'savings' => $income - $expense,
                'month_name' => $month->translatedFormat('F Y')
            ];
        }
        
        // Cari yang tertinggi
        $highestIncome = collect($monthsData)->sortByDesc('income')->first();
        $highestExpense = collect($monthsData)->sortByDesc('expense')->first();
        $bestSavings = collect($monthsData)->sortByDesc('savings')->first();
        
        return [
            'highest_income' => $highestIncome,
            'highest_expense' => $highestExpense,
            'best_savings' => $bestSavings
        ];
    }
    
    private function getHabitsAnalysis($userId, $startDate, $endDate)
    {
        // 1. Konsistensi Menabung (berapa bulan dengan selisih positif)
        $savingMonths = 0;
        $totalMonths = 6;
        
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            
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
            
            if ($income > $expense) {
                $savingMonths++;
            }
        }
        
        $savingConsistency = ($savingMonths / $totalMonths) * 100;
        
        // 2. Pengeluaran Tidak Terduga
        $otherCategory = Category::where('user_id', $userId)
            ->where('category_name', 'like', '%lain%')
            ->orWhere('category_name', 'like', '%tidak terduga%')
            ->first();
            
        $unexpectedExpenses = 0;
        if ($otherCategory) {
            $unexpectedExpenses = Transaction::where('user_id', $userId)
                ->where('category_id', $otherCategory->id)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
        }
        
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
            
        $unexpectedPercentage = $totalExpense > 0 
            ? ($unexpectedExpenses / $totalExpense) * 100 
            : 0;
        
        // 3. Kepatuhan Anggaran
        $budgetCompliance = $this->calculateBudgetCompliance($userId, $startDate, $endDate);
        
        return [
            'saving_consistency' => round($savingConsistency, 1),
            'unexpected_expenses' => round($unexpectedPercentage, 1),
            'budget_compliance' => round($budgetCompliance, 1)
        ];
    }
    
    private function calculateBudgetCompliance($userId, $startDate, $endDate)
    {
        $budgets = Budget::where('user_id', $userId)->get();
        
        if ($budgets->isEmpty()) {
            return 100; // Jika tidak ada budget, anggap 100% compliance
        }
        
        $compliantBudgets = 0;
        
        foreach ($budgets as $budget) {
            $spent = Transaction::where('user_id', $userId)
                ->where('budget_id', $budget->id)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            // Budget dianggap compliant jika pengeluaran <= 100% dari budget
            $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
            
            if ($percentage <= 100) {
                $compliantBudgets++;
            }
        }
        
        return ($compliantBudgets / $budgets->count()) * 100;
    }
    
    private function getCategoryAnalysis($userId, $startDate, $endDate)
    {
        $categories = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->get();
        
        $analysis = [];
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        
        foreach ($categories as $category) {
            $currentAmount = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->where('transaction_type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            
            if ($currentAmount > 0) {
                // Hitung tren (vs bulan lalu)
                $lastMonth = Carbon::now()->subMonth();
                $previousAmount = Transaction::where('user_id', $userId)
                    ->where('category_id', $category->id)
                    ->where('transaction_type', 'expense')
                    ->whereMonth('transaction_date', $lastMonth->month)
                    ->whereYear('transaction_date', $lastMonth->year)
                    ->sum('amount');
                
                $trend = $previousAmount > 0 
                    ? (($currentAmount - $previousAmount) / $previousAmount) * 100 
                    : ($currentAmount > 0 ? 100 : 0);
                
                // Hitung vs rata-rata 6 bulan
                $monthlyAverages = [];
                for ($i = 0; $i < 6; $i++) {
                    $month = Carbon::now()->subMonths($i);
                    $monthlyAmount = Transaction::where('user_id', $userId)
                        ->where('category_id', $category->id)
                        ->where('transaction_type', 'expense')
                        ->whereMonth('transaction_date', $month->month)
                        ->whereYear('transaction_date', $month->year)
                        ->sum('amount');
                    $monthlyAverages[] = $monthlyAmount;
                }
                
                $averageAmount = array_sum($monthlyAverages) / count($monthlyAverages);
                $vsAverage = $averageAmount > 0 
                    ? (($currentAmount - $averageAmount) / $averageAmount) * 100 
                    : ($currentAmount > 0 ? 100 : 0);
                
                // Rekomendasi
                $recommendation = $this->getCategoryRecommendation($trend, $vsAverage);
                
                $analysis[] = [
                    'category' => $category->category_name,
                    'amount' => $currentAmount,
                    'percentage' => $totalExpense > 0 ? round(($currentAmount / $totalExpense) * 100, 1) : 0,
                    'trend' => round($trend, 1),
                    'vs_average' => round($vsAverage, 1),
                    'recommendation' => $recommendation,
                    'trend_status' => $trend > 10 ? 'increase' : ($trend < -10 ? 'decrease' : 'stable'),
                    'color' => $this->getCategoryColor($category->category_name)
                ];
            }
        }
        
        // Urutkan berdasarkan jumlah terbesar
        usort($analysis, function($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
        
        return $analysis;
    }
    
    private function getCategoryRecommendation($trend, $vsAverage)
    {
        if ($trend > 20 && $vsAverage > 20) {
            return 'Perlu dikurangi segera';
        } elseif ($trend > 10 || $vsAverage > 10) {
            return 'Perlu pengawasan';
        } elseif ($trend < -10 && $vsAverage < -10) {
            return 'Pengeluaran efisien';
        } else {
            return 'Optimal';
        }
    }
    
    private function getCategoryColor($categoryName)
    {
        $colors = [
            'Makan & Minum' => '#EF4444', // red
            'Transportasi' => '#3B82F6', // blue
            'Tagihan' => '#10B981', // green
            'Hiburan' => '#F59E0B', // yellow
            'Belanja' => '#8B5CF6', // purple
            'Kesehatan' => '#EC4899', // pink
            'Pendidikan' => '#06B6D4', // cyan
            'Lainnya' => '#6B7280', // gray
        ];
        
        foreach ($colors as $key => $color) {
            if (stripos($categoryName, $key) !== false) {
                return $color;
            }
        }
        
        return $this->getRandomColor();
    }
    
    private function getRandomColor()
    {
        $colors = [
            '#EF4444', '#F59E0B', '#10B981', '#3B82F6', 
            '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'
        ];
        
        return $colors[array_rand($colors)];
    }
    
    private function getRecommendations($summary, $expenseDistribution, $habitsAnalysis)
    {
        $recommendations = [];
        
        // Analisis berdasarkan pengeluaran terbesar
        if (isset($summary['largest_expense_category']['percentage']) && 
            $summary['largest_expense_category']['percentage'] > 30) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Pengeluaran kategori "' . $summary['largest_expense_category']['name'] . 
                           '" mencapai ' . $summary['largest_expense_category']['percentage'] . 
                           '% dari total pengeluaran. Pertimbangkan untuk mengurangi.'
            ];
        }
        
        // Analisis berdasarkan tren pengeluaran
        if ($summary['expense_change'] > 15) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Pengeluaran Anda naik ' . abs($summary['expense_change']) . 
                           '% dibanding periode sebelumnya. Periksa kebiasaan belanja Anda.'
            ];
        }
        
        // Analisis berdasarkan rasio penghematan
        if ($summary['savings_rate'] < 20) {
            $recommendations[] = [
                'type' => 'advice',
                'message' => 'Rasio penghematan Anda ' . $summary['savings_rate'] . 
                           '. Coba targetkan minimal 20% untuk keamanan finansial.'
            ];
        }
        
        // Analisis berdasarkan pengeluaran tidak terduga
        if ($habitsAnalysis['unexpected_expenses'] > 15) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Pengeluaran tidak terduga mencapai ' . 
                           $habitsAnalysis['unexpected_expenses'] . 
                           '%. Buat dana darurat untuk mengatasi hal ini.'
            ];
        }
        
        // Jika tidak ada rekomendasi spesifik, berikan saran umum
        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'success',
                'message' => 'Keuangan Anda dalam kondisi baik! Pertahankan kebiasaan menabung dan perhatikan budget.'
            ];
        }
        
        return $recommendations;
    }
    
    // API untuk data chart
    public function getChartData(Request $request)
    {
        $userId = Auth::id();
        $chartType = $request->get('type', 'trend');
        
        switch ($chartType) {
            case 'trend':
                $data = $this->getFinancialTrend($userId);
                break;
                
            case 'distribution':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $distribution = $this->getExpenseDistribution($userId, $startDate, $endDate);
                $data = [
                    'labels' => collect($distribution)->pluck('category')->toArray(),
                    'data' => collect($distribution)->pluck('amount')->toArray(),
                    'colors' => collect($distribution)->pluck('color')->toArray()
                ];
                break;
                
            default:
                $data = [];
        }
        
        return response()->json($data);
    }
    
    // Ekspor laporan analisis
    public function exportAnalysis(Request $request)
    {
        $userId = Auth::id();
        $period = $request->get('period', 'monthly');
        
        // Ambil semua data analisis
        $data = $this->index($request);
        
        // Generate PDF atau CSV
        $fileName = 'Analisis_Keuangan_' . date('Y-m-d') . '.pdf';
        
        // Implementasi export PDF bisa menggunakan DomPDF atau package lain
        // ... kode untuk generate PDF
        
        return response()->download($fileName);
    }
}