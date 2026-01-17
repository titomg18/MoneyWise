<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - MoneyWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
        .modal-content::-webkit-scrollbar {
            width: 6px;
        }
        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .modal-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .modal-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .income-bg {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
        }
        .expense-bg {
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
        }
        .balance-bg {
            background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%);
        }
        .savings-bg {
            background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Sidebar untuk Desktop -->
    <div class="hidden lg:flex flex-col fixed inset-y-0 w-64 bg-gradient-to-b from-blue-800 to-indigo-900 text-white shadow-xl">
        <!-- Logo -->
        <div class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-lg lg:text-xl"></i>
                </div>
                <h1 class="text-xl font-bold font-montserrat">MoneyWise</h1>
            </div>
        </div>

        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 space-y-2">
            <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="/transactions" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-exchange-alt"></i>
                <span>Transaksi</span>
            </a>
            <a href="/analysis" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-chart-pie"></i>
                <span>Analisis</span>
            </a>
            <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-wallet"></i>
                <span>Anggaran</span>
            </a>
            <a href="{{ route('tabungan.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-bullseye"></i>
                <span>Target Tabungan</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                <i class="fas fa-file-invoice"></i>
                <span class="font-medium">Laporan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full flex items-center justify-center">
                    <span class="font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-blue-200">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Header untuk Mobile -->
    <div class="lg:hidden fixed top-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg z-10">
        <div class="flex items-center justify-between h-16 px-4">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold font-montserrat">MoneyWise</h1>
            </div>
            <button id="mobileMenuButton" class="text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 pt-16 lg:pt-0 min-h-screen">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-4 mx-4 lg:mx-6 mt-4 fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header Laporan -->
        <div class="bg-white shadow-sm border-b fade-in">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Laporan Keuangan</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Analisis dan ringkasan keuangan Anda, {{ Auth::user()->name }}!</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Periode: {{ $startDate->translatedFormat('d F Y') }} - {{ $endDate->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <!-- Form Filter Periode -->
                        <form id="periodeForm" method="GET" action="{{ route('laporan.index') }}" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <select name="periode" id="periodeLaporan" class="border border-gray-300 rounded-xl px-4 py-2 text-sm lg:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="bulan_ini" {{ $period == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="minggu_ini" {{ $period == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan_lalu" {{ $period == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                                <option value="tahun_ini" {{ $period == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                                <option value="custom">Custom</option>
                            </select>
                            
                            <!-- Input tanggal custom (tersembunyi awalnya) -->
                            <div id="customDateInputs" class="hidden sm:flex space-x-2">
                                <input type="date" name="start_date" class="border border-gray-300 rounded-xl px-4 py-2 text-sm lg:text-base" value="{{ request('start_date', date('Y-m-01')) }}">
                                <span class="self-center">s/d</span>
                                <input type="date" name="end_date" class="border border-gray-300 rounded-xl px-4 py-2 text-sm lg:text-base" value="{{ request('end_date', date('Y-m-t')) }}">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600">
                                    Terapkan
                                </button>
                            </div>
                            
                            <a href="{{ route('laporan.export', ['periode' => $period, 'start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-cyan-700 transition-all flex items-center justify-center text-sm lg:text-base">
                                <i class="fas fa-download mr-2"></i> Export CSV
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Laporan -->
        <div class="p-4 lg:p-6">
            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8 fade-in">
                <!-- Total Pemasukan -->
                <div class="income-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-down text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">
                            @if($incomePercentage >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>+{{ number_format($incomePercentage, 1) }}%
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>{{ number_format($incomePercentage, 1) }}%
                            @endif
                        </span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Total Pemasukan</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <span>Periode terpilih</span>
                    </div>
                </div>

                <!-- Total Pengeluaran -->
                <div class="expense-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-up text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">
                            @if($expensePercentage >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>+{{ number_format($expensePercentage, 1) }}%
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>{{ number_format($expensePercentage, 1) }}%
                            @endif
                        </span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Total Pengeluaran</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <span>Periode terpilih</span>
                    </div>
                </div>

                <!-- Saldo Bersih -->
                <div class="balance-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">
                            @if($balancePercentage >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>+{{ number_format($balancePercentage, 1) }}%
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>{{ number_format($balancePercentage, 1) }}%
                            @endif
                        </span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Saldo Bersih</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">
                        @if($netBalance >= 0)
                            Rp {{ number_format($netBalance, 0, ',', '.') }}
                        @else
                            -Rp {{ number_format(abs($netBalance), 0, ',', '.') }}
                        @endif
                    </p>
                    <div class="flex items-center text-sm">
                        <i class="fas {{ $netBalance >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                        <span>{{ $netBalance >= 0 ? 'Positif' : 'Negatif' }} dari periode sebelumnya</span>
                    </div>
                </div>

                <!-- Total Tabungan -->
                <div class="savings-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-piggy-bank text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">
                            @if($savingsPercentage >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>+{{ number_format($savingsPercentage, 1) }}%
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>{{ number_format($savingsPercentage, 1) }}%
                            @endif
                        </span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Total Tabungan</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp {{ number_format($totalSavings, 0, ',', '.') }}</p>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-bullseye mr-1"></i>
                        <span>{{ number_format($savingsRatio, 1) }}% dari total pemasukan tahun ini</span>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Analisis -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8 fade-in">
                <!-- Grafik Trend Keuangan -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Trend Keuangan 6 Bulan Terakhir</h3>
                        <div class="flex space-x-2 mt-2 sm:mt-0">
                            <button id="lineChartBtn" class="bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <i class="fas fa-chart-line mr-1"></i> Grafik Garis
                            </button>
                            <button id="barChartBtn" class="bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <i class="fas fa-chart-bar mr-1"></i> Grafik Batang
                            </button>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <canvas id="financialTrendChart"></canvas>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rata-rata Pemasukan/Bulan</p>
                            <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($avgIncome, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rata-rata Pengeluaran/Bulan</p>
                            <p class="text-lg font-bold text-red-600">Rp {{ number_format($avgExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rasio Tabungan</p>
                            <p class="text-lg font-bold text-blue-600">{{ number_format($savingsRatio, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Distribusi Pengeluaran -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Distribusi Pengeluaran</h3>
                    
                    <div class="chart-container">
                        <canvas id="expenseDistributionChart"></canvas>
                    </div>
                    
                    <div class="space-y-3 mt-6">
                        @forelse($expenseDistribution as $index => $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $item['color'] ?? $colors[$index] ?? '#'.substr(md5($item['category']), 0, 6) }}"></div>
                                    <span class="text-sm text-gray-700 truncate max-w-[120px]">{{ $item['category'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-medium text-gray-800">{{ number_format($item['percentage'], 1) }}%</span>
                                    <p class="text-xs text-gray-500">Rp {{ number_format($item['amount'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada data pengeluaran</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tabel Transaksi dan Analisis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8 fade-in">
                <!-- Transaksi Terbaru -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
                        <a href="/transactions" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($recentTransactions as $transaction)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors transaction-item">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 {{ $transaction->transaction_type == 'income' ? 'bg-emerald-100' : 'bg-red-100' }}">
                                        <i class="fas {{ $transaction->transaction_type == 'income' ? 'fa-arrow-down text-emerald-500' : 'fa-arrow-up text-red-500' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 truncate max-w-[150px]">{{ $transaction->description }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $transaction->category->category_name ?? 'Tidak ada kategori' }} • 
                                            {{ $transaction->transaction_date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold {{ $transaction->transaction_type == 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $transaction->transaction_type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $transaction->transaction_type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                        @endforelse
                    </div>
                </div>

                <!-- Analisis Anggaran vs Aktual -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Analisis Anggaran</h3>
                        <span class="text-xs text-gray-500">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($budgetAnalysis as $analysis)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 truncate max-w-[150px]">{{ $analysis['category'] }}</span>
                                    <span class="text-sm font-medium text-gray-700 text-nowrap">
                                        Rp {{ number_format($analysis['spent'], 0, ',', '.') }} / 
                                        Rp {{ number_format($analysis['budget'], 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $analysis['status'] == 'exceeded' ? 'bg-red-500' : ($analysis['status'] == 'warning' ? 'bg-yellow-500' : 'bg-emerald-500') }} h-2 rounded-full" 
                                         style="width: {{ min(100, $analysis['percentage']) }}%">
                                    </div>
                                </div>
                                <p class="text-xs {{ $analysis['status'] == 'exceeded' ? 'text-red-500' : 'text-gray-500' }} mt-1">
                                    @if($analysis['status'] == 'exceeded')
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Melebihi anggaran Rp {{ number_format(abs($analysis['remaining']), 0, ',', '.') }}
                                    @elseif($analysis['status'] == 'warning')
                                        <i class="fas fa-exclamation-circle mr-1"></i>Hati-hati! Sisa Rp {{ number_format($analysis['remaining'], 0, ',', '.') }}
                                    @else
                                        <i class="fas fa-check-circle mr-1"></i>Sisa anggaran Rp {{ number_format($analysis['remaining'], 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada anggaran yang dibuat</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Analisis Target Tabungan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8 fade-in">
                <!-- Pencapaian Target Tabungan -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Pencapaian Target Tabungan</h3>
                        <span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">
                            {{ $savingsTargets->count() }} Target
                        </span>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse($savingsTargets as $target)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 truncate max-w-[150px]">{{ $target['target_name'] }}</span>
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($target['percentage'], 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="{{ $target['status'] == 'achieved' ? 'bg-emerald-500' : 'bg-blue-500' }} h-3 rounded-full" 
                                         style="width: {{ min(100, $target['percentage']) }}%">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Rp {{ number_format($target['saved_amount'], 0, ',', '.') }} dari 
                                    Rp {{ number_format($target['target_amount'], 0, ',', '.') }}
                                    @if($target['days_left'] > 0)
                                        • {{ $target['days_left'] }} hari lagi
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada target tabungan</p>
                        @endforelse
                    </div>
                    
                    <!-- Skor Kesehatan Keuangan -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Skor Kesehatan Keuangan</p>
                            @php
                                // Hitung skor sederhana berdasarkan rasio tabungan dan pengeluaran
                                $financialScore = min(10, max(0, 
                                    ($savingsRatio / 20) + // Maksimal 5 poin dari rasio tabungan
                                    (($netBalance > 0 ? 1 : 0) * 3) + // 3 poin jika saldo positif
                                    (($totalIncome > $totalExpense ? 1 : 0) * 2) // 2 poin jika pemasukan > pengeluaran
                                ));
                                $scoreColor = $financialScore >= 8 ? '#10B981' : ($financialScore >= 6 ? '#F59E0B' : '#EF4444');
                            @endphp
                            <div class="relative w-32 h-32 mx-auto">
                                <svg class="w-full h-full" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#E5E7EB" stroke-width="10"/>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $scoreColor }}" stroke-width="10" 
                                            stroke-dasharray="283" 
                                            stroke-dashoffset="{{ 283 - (283 * ($financialScore / 10)) }}" 
                                            stroke-linecap="round"/>
                                    <text x="50" y="50" text-anchor="middle" dy=".3em" class="text-2xl font-bold fill-gray-800">
                                        {{ number_format($financialScore, 1) }}
                                    </text>
                                    <text x="50" y="60" text-anchor="middle" class="text-xs fill-gray-500">/10</text>
                                </svg>
                            </div>
                            <p class="text-sm mt-2 font-medium" style="color: {{ $scoreColor }}">
                                @if($financialScore >= 8)
                                    <i class="fas fa-trophy mr-1"></i>Skor yang sangat baik!
                                @elseif($financialScore >= 6)
                                    <i class="fas fa-thumbs-up mr-1"></i>Skor cukup baik
                                @else
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Perlu perbaikan
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan dan Tips -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Ringkasan Performa</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 shadow-sm">
                                    <i class="fas fa-chart-bar text-blue-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Rata-rata Harian</h4>
                                    <p class="text-xs text-gray-600">Pengeluaran per hari</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-gray-800">Rp {{ number_format($avgExpense / $startDate->diffInDays($endDate), 0, ',', '.') }}/hari</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 shadow-sm">
                                    <i class="fas fa-percentage text-emerald-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Rasio Penghematan</h4>
                                    <p class="text-xs text-gray-600">Persentase dari pemasukan</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-gray-800">{{ number_format($savingsRatio, 1) }}%</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 shadow-sm">
                                    <i class="fas fa-balance-scale text-purple-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Perbandingan</h4>
                                    <p class="text-xs text-gray-600">Pemasukan vs Pengeluaran</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-gray-800">{{ $totalIncome > 0 ? number_format(($totalExpense / $totalIncome) * 100, 1) : 0 }}%</p>
                        </div>
                    </div>
                    
                    <!-- Tips Keuangan -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-medium text-gray-800 mb-3">Tips Keuangan</h4>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                    <i class="fas fa-lightbulb text-emerald-500 text-sm"></i>
                                </div>
                                <p class="text-sm text-gray-600">Coba alokasikan {{ min(30, $savingsRatio + 5) }}% dari pendapatan untuk tabungan.</p>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                    <i class="fas fa-chart-pie text-blue-500 text-sm"></i>
                                </div>
                                <p class="text-sm text-gray-600">Review pengeluaran terbesar Anda dan cari peluang penghematan.</p>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                    <i class="fas fa-bullseye text-purple-500 text-sm"></i>
                                </div>
                                <p class="text-sm text-gray-600">Tetapkan target tabungan baru untuk meningkatkan motivasi menabung.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t mt-6 lg:mt-8 p-4 lg:p-6 fade-in">
            <div class="text-center text-gray-500 text-xs lg:text-sm">
                <p>&copy; {{ date('Y') }} MoneyWise. Aplikasi manajemen keuangan pribadi.</p>
                <p class="mt-2">Laporan ini dibuat otomatis berdasarkan data transaksi Anda • Terakhir diupdate: {{ now()->translatedFormat('d F Y H:i') }}</p>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="lg:hidden fixed inset-0 bg-gray-800 bg-opacity-50 z-20 hidden">
        <div class="absolute right-0 top-0 h-full w-64 bg-gradient-to-b from-blue-800 to-indigo-900 text-white shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                        </div>
                        <h1 class="text-lg font-bold font-montserrat">MoneyWise</h1>
                    </div>
                    <button id="closeMobileMenu" class="text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full flex items-center justify-center">
                        <span class="font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-200">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <nav class="space-y-2">
                    <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-home"></i>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                    <a href="/transactions" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="text-sm">Transaksi</span>
                    </a>
                    <a href="/analysis" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-chart-pie"></i>
                        <span class="text-sm">Analisis</span>
                    </a>
                    <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-wallet"></i>
                        <span class="text-sm">Anggaran</span>
                    </a>
                    <a href="{{ route('tabungan.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-bullseye"></i>
                        <span class="text-sm">Target Tabungan</span>
                    </a>
                    <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                        <i class="fas fa-file-invoice"></i>
                        <span class="font-medium text-sm">Laporan</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-cog"></i>
                        <span class="text-sm">Pengaturan</span>
                    </a>
                </nav>

                <!-- Logout Button -->
                <div class="absolute bottom-6 left-6 right-6">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="text-sm">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // DATA PREPARATION
        // ============================================
        const financialTrendData = @json($financialTrend);
        const expenseDistributionData = @json($expenseDistribution);
        
        // Warna untuk chart
        const chartColors = [
            '#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EC4899',
            '#06B6D4', '#84CC16', '#F97316', '#8B5CF6', '#EC4899'
        ];

        // ============================================
        // CHART CONFIGURATION
        // ============================================

        // Financial Trend Chart (Line Chart)
        const financialTrendCtx = document.getElementById('financialTrendChart')?.getContext('2d');
        let financialTrendChart;
        
        if (financialTrendCtx) {
            financialTrendChart = new Chart(financialTrendCtx, {
                type: 'line',
                data: {
                    labels: financialTrendData.map(item => item.month),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: financialTrendData.map(item => item.income),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#10B981'
                        },
                        {
                            label: 'Pengeluaran',
                            data: financialTrendData.map(item => item.expense),
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#EF4444'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: Rp ${context.raw.toLocaleString('id-ID')}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Expense Distribution Chart (Doughnut Chart)
        const expenseDistributionCtx = document.getElementById('expenseDistributionChart')?.getContext('2d');
        let expenseDistributionChart;
        
        if (expenseDistributionCtx && expenseDistributionData.length > 0) {
            expenseDistributionChart = new Chart(expenseDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels: expenseDistributionData.map(item => item.category),
                    datasets: [{
                        data: expenseDistributionData.map(item => item.amount),
                        backgroundColor: expenseDistributionData.map((item, index) => 
                            item.color || chartColors[index % chartColors.length]
                        ),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${context.label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }

        // ============================================
        // CHART TYPE TOGGLE
        // ============================================
        document.getElementById('lineChartBtn')?.addEventListener('click', function() {
            if (financialTrendChart) {
                financialTrendChart.config.type = 'line';
                financialTrendChart.update();
                this.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                document.getElementById('barChartBtn').classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
            }
        });

        document.getElementById('barChartBtn')?.addEventListener('click', function() {
            if (financialTrendChart) {
                financialTrendChart.config.type = 'bar';
                financialTrendChart.update();
                this.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                document.getElementById('lineChartBtn').classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
            }
        });

        // ============================================
        // MOBILE MENU HANDLING
        // ============================================
        document.getElementById('mobileMenuButton')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.remove('hidden');
        });

        document.getElementById('closeMobileMenu')?.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.add('hidden');
        });

        document.getElementById('mobileMenu')?.addEventListener('click', (e) => {
            if (e.target.id === 'mobileMenu') {
                document.getElementById('mobileMenu').classList.add('hidden');
            }
        });

        // ============================================
        // PERIODE SELECTION
        // ============================================
        document.getElementById('periodeLaporan')?.addEventListener('change', function(e) {
            const periode = e.target.value;
            const customInputs = document.getElementById('customDateInputs');
            
            if (periode === 'custom') {
                customInputs.classList.remove('hidden');
                customInputs.classList.add('flex');
            } else {
                customInputs.classList.add('hidden');
                customInputs.classList.remove('flex');
                document.getElementById('periodeForm').submit();
            }
        });

        // Auto-hide custom inputs if not custom
        document.addEventListener('DOMContentLoaded', function() {
            const periodeSelect = document.getElementById('periodeLaporan');
            const customInputs = document.getElementById('customDateInputs');
            
            if (periodeSelect && periodeSelect.value !== 'custom') {
                customInputs.classList.add('hidden');
            }
        });

        // ============================================
        // SEARCH FUNCTIONALITY
        // ============================================
        const searchInput = document.getElementById('searchLaporan');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                
                // Filter transaksi terbaru
                document.querySelectorAll('.transaction-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            }, 300));
        }

        // ============================================
        // ANIMATIONS
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars on load
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.h-2.rounded-full, .h-3.rounded-full');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                        bar.style.transition = 'width 1s ease-in-out';
                    }, 100);
                });
            }, 500);

            // Add hover effects to cards
            const cards = document.querySelectorAll('.bg-white.rounded-2xl');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                    this.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
                    this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // Add fade-in animation to sections
            const sections = document.querySelectorAll('.fade-in');
            sections.forEach((section, index) => {
                section.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // ============================================
        // RESPONSIVE CHART UPDATES
        // ============================================
        window.addEventListener('resize', function() {
            if (financialTrendChart) financialTrendChart.resize();
            if (expenseDistributionChart) expenseDistributionChart.resize();
        });

        // ============================================
        // HELPER FUNCTIONS
        // ============================================
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ============================================
        // PRINT/EXPORT FUNCTIONALITY
        // ============================================
        window.printReport = function() {
            window.print();
        };

        // ============================================
        // DATA REFRESH INDICATOR
        // ============================================
        let lastUpdateTime = new Date();
        
        function updateLastUpdateTime() {
            const now = new Date();
            const diff = Math.floor((now - lastUpdateTime) / 1000);
            
            if (diff > 60) {
                // Tampilkan indikator bahwa data mungkin perlu refresh
                const footer = document.querySelector('footer p:nth-child(2)');
                if (footer) {
                    footer.innerHTML += ` <span class="text-amber-600 font-medium">(Data mungkin perlu refresh)</span>`;
                }
            }
        }

        // Update setiap 5 menit
        setInterval(updateLastUpdateTime, 300000);
    </script>
</body>
</html>