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
            <a href="/analisis" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-4 mx-4 lg:mx-6 mt-4">
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
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Laporan Keuangan</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Analisis dan ringkasan keuangan Anda, {{ Auth::user()->name }}!</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="searchLaporan" placeholder="Cari laporan..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="flex space-x-2">
                            <select id="periodeLaporan" class="border border-gray-300 rounded-xl px-4 py-2 text-sm lg:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="bulan_ini">Bulan Ini</option>
                                <option value="minggu_ini">Minggu Ini</option>
                                <option value="bulan_lalu">Bulan Lalu</option>
                                <option value="tahun_ini">Tahun Ini</option>
                                <option value="custom">Custom</option>
                            </select>
                            <button id="exportLaporan" class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-cyan-700 transition-all flex items-center justify-center text-sm lg:text-base">
                                <i class="fas fa-download mr-2"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Laporan -->
        <div class="p-4 lg:p-6">
            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Total Pemasukan -->
                <div class="income-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-down text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">+12%</span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Total Pemasukan</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp 12.500.000</p>
                    <div class="flex items-center">
                        <div class="w-full bg-white/30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <span class="ml-2 text-sm">85% target</span>
                    </div>
                </div>

                <!-- Total Pengeluaran -->
                <div class="expense-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-up text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">-8%</span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Total Pengeluaran</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp 8.750.000</p>
                    <div class="flex items-center">
                        <div class="w-full bg-white/30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 65%"></div>
                        </div>
                        <span class="ml-2 text-sm">65% dari anggaran</span>
                    </div>
                </div>

                <!-- Saldo -->
                <div class="balance-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">+18%</span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Saldo Bersih</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp 3.750.000</p>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-arrow-trend-up mr-1"></i>
                        <span>Peningkatan dari bulan lalu</span>
                    </div>
                </div>

                <!-- Tabungan -->
                <div class="savings-bg text-white rounded-2xl p-5 lg:p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-piggy-bank text-white text-xl"></i>
                        </div>
                        <span class="text-white/80 text-sm">+25%</span>
                    </div>
                    <h3 class="text-white/90 text-sm font-medium mb-1">Total Tabungan</h3>
                    <p class="text-2xl lg:text-3xl font-bold mb-2">Rp 5.250.000</p>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-bullseye mr-1"></i>
                        <span>70% dari target tahunan</span>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Analisis -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Grafik Trend Keuangan -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Trend Keuangan 6 Bulan Terakhir</h3>
                        <div class="flex space-x-2">
                            <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <i class="fas fa-chart-line mr-1"></i> Grafik Garis
                            </button>
                            <button class="bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <i class="fas fa-chart-bar mr-1"></i> Grafik Batang
                            </button>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <canvas id="financialTrendChart"></canvas>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rata-rata Pemasukan</p>
                            <p class="text-lg font-bold text-emerald-600">Rp 2.1jt</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rata-rata Pengeluaran</p>
                            <p class="text-lg font-bold text-red-600">Rp 1.5jt</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rasio Tabungan</p>
                            <p class="text-lg font-bold text-blue-600">25%</p>
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
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Makanan & Minuman</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">35%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Transportasi</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">20%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-purple-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Hiburan</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">15%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-amber-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Belanja</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">12%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-pink-500 mr-2"></div>
                                <span class="text-sm text-gray-700">Lainnya</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">18%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Transaksi dan Analisis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Transaksi Terbaru -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
                        <a href="/transactions" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $transactions = [
                                    ['name' => 'Gaji Bulanan', 'amount' => 7500000, 'type' => 'income', 'category' => 'Pendapatan', 'date' => 'Hari ini'],
                                    ['name' => 'Belanja Bulanan', 'amount' => 1250000, 'type' => 'expense', 'category' => 'Belanja', 'date' => 'Kemarin'],
                                    ['name' => 'Bayar Listrik', 'amount' => 450000, 'type' => 'expense', 'category' => 'Utilities', 'date' => '2 hari lalu'],
                                    ['name' => 'Freelance Project', 'amount' => 2500000, 'type' => 'income', 'category' => 'Pendapatan', 'date' => '3 hari lalu'],
                                    ['name' => 'Makan di Restoran', 'amount' => 250000, 'type' => 'expense', 'category' => 'Makanan', 'date' => '4 hari lalu'],
                                ];
                                $t = $transactions[$i-1];
                            @endphp
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 {{ $t['type'] == 'income' ? 'bg-emerald-100' : 'bg-red-100' }}">
                                        <i class="fas {{ $t['type'] == 'income' ? 'fa-arrow-down text-emerald-500' : 'fa-arrow-up text-red-500' }}"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $t['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $t['category'] }} • {{ $t['date'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold {{ $t['type'] == 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $t['type'] == 'income' ? '+' : '-' }}Rp {{ number_format($t['amount'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $t['type'] == 'income' ? 'Pemasukan' : 'Pengeluaran' }}</p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Analisis Anggaran vs Aktual -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Anggaran vs Aktual</h3>
                        <select class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                            <option>Juni 2024</option>
                            <option>Mei 2024</option>
                            <option>April 2024</option>
                        </select>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Makanan & Minuman</span>
                                <span class="text-sm font-medium text-gray-700">Rp 1.2jt / Rp 1.5jt</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masih tersisa Rp 300.000</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Transportasi</span>
                                <span class="text-sm font-medium text-gray-700">Rp 800k / Rp 1jt</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masih tersisa Rp 200.000</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Hiburan</span>
                                <span class="text-sm font-medium text-gray-700">Rp 600k / Rp 500k</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 120%"></div>
                            </div>
                            <p class="text-xs text-red-500 mt-1">Melebihi anggaran Rp 100.000</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Belanja</span>
                                <span class="text-sm font-medium text-gray-700">Rp 900k / Rp 1.2jt</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masih tersisa Rp 300.000</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Tabungan</span>
                                <span class="text-sm font-medium text-gray-700">Rp 1.5jt / Rp 1.5jt</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                            <p class="text-xs text-emerald-500 mt-1">Target tercapai!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Tahunan dan Target -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Ringkasan Tahunan -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Ringkasan Tahunan 2024</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2 text-sm font-medium text-gray-700">Bulan</th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-700">Pemasukan</th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-700">Pengeluaran</th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-700">Saldo</th>
                                    <th class="text-left py-2 text-sm font-medium text-gray-700">Tabungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
                                    $incomes = [10, 11, 12, 11, 13, 12.5];
                                    $expenses = [7, 7.5, 8, 7.8, 8.2, 8.75];
                                @endphp
                                
                                @foreach($months as $index => $month)
                                    @php
                                        $income = $incomes[$index];
                                        $expense = $expenses[$index];
                                        $balance = $income - $expense;
                                        $savings = $balance * 0.25;
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 text-sm text-gray-800">{{ $month }}</td>
                                        <td class="py-3 text-sm text-emerald-600 font-medium">Rp {{ number_format($income * 1000000, 0, ',', '.') }}</td>
                                        <td class="py-3 text-sm text-red-600 font-medium">Rp {{ number_format($expense * 1000000, 0, ',', '.') }}</td>
                                        <td class="py-3 text-sm {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }} font-medium">
                                            Rp {{ number_format($balance * 1000000, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-sm text-purple-600 font-medium">Rp {{ number_format($savings * 1000000, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="py-3 text-sm text-gray-800">Total</td>
                                    <td class="py-3 text-sm text-emerald-600">Rp {{ number_format(array_sum($incomes) * 1000000, 0, ',', '.') }}</td>
                                    <td class="py-3 text-sm text-red-600">Rp {{ number_format(array_sum($expenses) * 1000000, 0, ',', '.') }}</td>
                                    <td class="py-3 text-sm text-blue-600">Rp {{ number_format((array_sum($incomes) - array_sum($expenses)) * 1000000, 0, ',', '.') }}</td>
                                    <td class="py-3 text-sm text-purple-600">Rp {{ number_format((array_sum($incomes) - array_sum($expenses)) * 0.25 * 1000000, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pencapaian Target -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Pencapaian Target</h3>
                        <span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">Tahun 2024</span>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Target Tabungan Tahunan</span>
                                <span class="text-sm font-medium text-gray-700">70%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-emerald-500 h-3 rounded-full" style="width: 70%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Rp 5.25jt dari Rp 7.5jt</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Target Investasi</span>
                                <span class="text-sm font-medium text-gray-700">45%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" style="width: 45%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Rp 4.5jt dari Rp 10jt</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Pengurangan Utang</span>
                                <span class="text-sm font-medium text-gray-700">85%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-purple-500 h-3 rounded-full" style="width: 85%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Rp 8.5jt dari Rp 10jt</p>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Emergency Fund</span>
                                <span class="text-sm font-medium text-gray-700">90%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-amber-500 h-3 rounded-full" style="width: 90%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Rp 9jt dari Rp 10jt</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Skor Kesehatan Keuangan</p>
                            <div class="relative w-32 h-32 mx-auto">
                                <svg class="w-full h-full" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#E5E7EB" stroke-width="10"/>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#10B981" stroke-width="10" 
                                            stroke-dasharray="283" stroke-dashoffset="85" 
                                            stroke-linecap="round"/>
                                    <text x="50" y="50" text-anchor="middle" dy=".3em" class="text-2xl font-bold fill-gray-800">8.5</text>
                                    <text x="50" y="60" text-anchor="middle" class="text-xs fill-gray-500">/10</text>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Skor yang sangat baik!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Keuangan -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-6 lg:p-8">
                <div class="flex items-center mb-4 lg:mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-blue-500"></i>
                    </div>
                    <h3 class="text-lg lg:text-xl font-bold text-gray-800">Tips Keuangan Bulan Ini</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-chart-line text-emerald-500"></i>
                            </div>
                            <h4 class="font-medium text-gray-800">Review Anggaran</h4>
                        </div>
                        <p class="text-sm text-gray-600">Tinjau kembali anggaran bulanan Anda dan sesuaikan dengan kebutuhan aktual.</p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-piggy-bank text-blue-500"></i>
                            </div>
                            <h4 class="font-medium text-gray-800">Tingkatkan Tabungan</h4>
                        </div>
                        <p class="text-sm text-gray-600">Coba tingkatkan persentase tabungan bulanan Anda sebesar 5%.</p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-2">
                                <i class="fas fa-receipt text-purple-500"></i>
                            </div>
                            <h4 class="font-medium text-gray-800">Analisis Pengeluaran</h4>
                        </div>
                        <p class="text-sm text-gray-600">Identifikasi 3 pengeluaran terbesar Anda dan cari cara untuk mengoptimalkannya.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t mt-6 lg:mt-8 p-4 lg:p-6">
            <div class="text-center text-gray-500 text-xs lg:text-sm">
                <p>&copy; {{ date('Y') }} MoneyWise. Aplikasi manajemen keuangan pribadi.</p>
                <p class="mt-2">Laporan ini dibuat otomatis berdasarkan data transaksi Anda</p>
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
                    <a href="/analisis" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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
        // CHART CONFIGURATION
        // ============================================

        // Financial Trend Chart (Line Chart)
        const financialTrendCtx = document.getElementById('financialTrendChart').getContext('2d');
        const financialTrendChart = new Chart(financialTrendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [10, 11, 12, 11, 13, 12.5],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran',
                        data: [7, 7.5, 8, 7.8, 8.2, 8.75],
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Tabungan',
                        data: [3, 3.5, 4, 3.2, 4.8, 3.75],
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: Rp ${(context.raw * 1000000).toLocaleString('id-ID')}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value * 1000000).toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Expense Distribution Chart (Doughnut Chart)
        const expenseDistributionCtx = document.getElementById('expenseDistributionChart').getContext('2d');
        const expenseDistributionChart = new Chart(expenseDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Makanan & Minuman', 'Transportasi', 'Hiburan', 'Belanja', 'Lainnya'],
                datasets: [{
                    data: [35, 20, 15, 12, 18],
                    backgroundColor: [
                        '#10B981',
                        '#3B82F6',
                        '#8B5CF6',
                        '#F59E0B',
                        '#EC4899'
                    ],
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
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                },
                cutout: '70%'
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
        // SEARCH FUNCTIONALITY
        // ============================================
        document.getElementById('searchLaporan')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            // Implement search functionality here
            console.log('Searching for:', searchTerm);
        });

        // ============================================
        // PERIODE SELECTION
        // ============================================
        document.getElementById('periodeLaporan')?.addEventListener('change', function(e) {
            const periode = e.target.value;
            if (periode === 'custom') {
                // Show date picker modal
                alert('Fitur periode custom akan segera hadir!');
            }
            // Update charts based on selected period
            console.log('Period changed to:', periode);
        });

        // ============================================
        // EXPORT FUNCTIONALITY
        // ============================================
        document.getElementById('exportLaporan')?.addEventListener('click', function() {
            // Show export options modal
            const exportOptions = `
                <div class="space-y-2">
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">
                        <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export sebagai PDF
                    </button>
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">
                        <i class="fas fa-file-excel mr-2 text-emerald-500"></i> Export sebagai Excel
                    </button>
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-lg text-sm">
                        <i class="fas fa-file-csv mr-2 text-blue-500"></i> Export sebagai CSV
                    </button>
                </div>
            `;
            
            // Simple alert for now - can be replaced with modal
            alert('Pilih format export:\n1. PDF\n2. Excel\n3. CSV');
        });

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
                    }, 300);
                });
            }, 500);

            // Add hover effects to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.transition = 'transform 0.2s ease';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // ============================================
        // RESPONSIVE CHART UPDATES
        // ============================================
        window.addEventListener('resize', function() {
            financialTrendChart.resize();
            expenseDistributionChart.resize();
        });
    </script>
</body>
</html>