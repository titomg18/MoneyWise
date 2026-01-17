<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MoneyWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
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
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                <i class="fas fa-home"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-exchange-alt"></i>
                <span>Transaksi</span>
            </a>
            <a href="{{ route('analysis.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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
            <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-file-invoice"></i>
                <span>Laporan</span>
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
                    <span class="font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <p class="font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-blue-200">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
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
        <!-- Header Dashboard -->
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Dashboard MoneyWise</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan keuangan Anda</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="searchInput" placeholder="Cari transaksi..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <a href="{{ route('transactions.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center text-sm lg:text-base">
                            <i class="fas fa-plus mr-2"></i> Transaksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Keuangan -->
        <div class="p-4 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Card Pemasukan -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="{{ ($incomeChange ?? 0) >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">
                            {{ ($incomeChange ?? 0) >= 0 ? '+' : '' }}{{ $incomeChange ?? 0 }}%
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Pemasukan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">
                        Rp {{ number_format($currentMonthIncome ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Bulan ini</p>
                </div>

                <!-- Card Pengeluaran -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="{{ ($expenseChange ?? 0) <= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">
                            {{ ($expenseChange ?? 0) >= 0 ? '+' : '' }}{{ $expenseChange ?? 0 }}%
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Pengeluaran</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">
                        Rp {{ number_format($currentMonthExpense ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Bulan ini</p>
                </div>

                <!-- Card Saldo -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="{{ ($currentMonthBalance ?? 0) >= 0 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }} px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">
                            {{ ($balanceChange ?? 0) >= 0 ? '+' : '' }}{{ $balanceChange ?? 0 }}%
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Saldo Akhir</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">
                        Rp {{ number_format($currentMonthBalance ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">
                        {{ ($currentMonthBalance ?? 0) >= 0 ? 'Surplus' : 'Defisit' }}
                    </p>
                </div>

                <!-- Card Target Tabungan -->
                <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bullseye text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-purple-600 bg-purple-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">
                            {{ round($savingsPercentage ?? 0, 1) }}%
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Target Tabungan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">
                        Rp {{ number_format($totalSavingsTarget ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">
                        Tercapai: Rp {{ number_format($totalSavings ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Grafik dan Tabel -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Grafik Pemasukan vs Pengeluaran -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Pemasukan vs Pengeluaran</h3>
                        <select id="chartPeriod" class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                            <option value="3months">3 Bulan Terakhir</option>
                            <option value="6months" selected>6 Bulan Terakhir</option>
                            <option value="1year">1 Tahun Terakhir</option>
                        </select>
                    </div>
                    <div class="h-48 lg:h-64">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Kategori Pengeluaran -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Kategori Pengeluaran</h3>
                    <div class="space-y-3 lg:space-y-4">
                        @php
                            $expenseCategories = $expenseCategories ?? [];
                        @endphp
                        
                        @if(count($expenseCategories) > 0)
                            @foreach($expenseCategories as $category)
                                @if($loop->index < 5) <!-- Batasi 5 kategori -->
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-xs lg:text-sm font-medium text-gray-700">{{ $category['name'] }}</span>
                                        <span class="text-xs lg:text-sm font-medium text-gray-700">{{ $category['percentage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full" style="width: {{ $category['percentage'] }}%; background-color: {{ $category['color'] ?? '#EF4444' }}"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Rp {{ number_format($category['amount'], 0, ',', '.') }}
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-pie text-2xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500 text-sm">Belum ada pengeluaran bulan ini</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                        <a href="{{ route('analysis.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center">
                            <i class="fas fa-chart-pie mr-2"></i> Lihat analisis lengkap
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Transaksi Terbaru</h3>
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                @if(count($recentTransactions ?? []) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-500 text-xs lg:text-sm border-b">
                                    <th class="pb-3 font-medium">Tanggal</th>
                                    <th class="pb-3 font-medium">Deskripsi</th>
                                    <th class="pb-3 font-medium">Kategori</th>
                                    <th class="pb-3 font-medium">Jenis</th>
                                    <th class="pb-3 font-medium">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="py-3 lg:py-4 text-xs lg:text-sm">
                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">
                                            {{ \Illuminate\Support\Str::limit($transaction->description, 20) }}
                                        </td>
                                        <td class="py-3 lg:py-4">
                                            @if($transaction->transaction_type == 'income')
                                                <span class="bg-green-100 text-green-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pemasukan</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pengeluaran</span>
                                            @endif
                                        </td>
                                        <td class="py-3 lg:py-4 text-xs lg:text-sm">
                                            {{ $transaction->category->category_name ?? 'Tidak ada kategori' }}
                                        </td>
                                        <td class="py-3 lg:py-4 font-bold text-xs lg:text-sm {{ $transaction->transaction_type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->transaction_type == 'income' ? '+' : '-' }} 
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-exchange-alt text-3xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-sm">Belum ada transaksi</p>
                        <a href="{{ route('transactions.create') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 font-medium text-xs">
                            <i class="fas fa-plus mr-1"></i> Tambah transaksi pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t mt-6 lg:mt-8 p-4 lg:p-6">
            <div class="text-center text-gray-500 text-xs lg:text-sm">
                <p>&copy; {{ date('Y') }} MoneyWise. Aplikasi manajemen keuangan pribadi.</p>
                <p class="mt-2">Dibuat dengan <i class="fas fa-heart text-red-400 mx-1"></i> untuk pengelolaan keuangan yang lebih bijak</p>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobileMenu" class="lg:hidden fixed inset-0 bg-gray-800 bg-opacity-50 z-20 hidden">
        <div class="absolute right-0 top-0 h-full w-64 bg-gradient-to-b from-blue-800 to-indigo-900 text-white shadow-xl">
            <div class="p-4 lg:p-6">
                <div class="flex items-center justify-between mb-6 lg:mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                        </div>
                        <h1 class="text-lg lg:text-xl font-bold font-montserrat">MoneyWise</h1>
                    </div>
                    <button id="closeMobileMenu" class="text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-3 mb-6 lg:mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full flex items-center justify-center">
                        <span class="font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm lg:text-base">{{ Auth::user()->name }}</p>
                        <p class="text-xs lg:text-sm text-blue-200">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                        <i class="fas fa-home"></i>
                        <span class="font-medium text-sm lg:text-base">Dashboard</span>
                    </a>
                    <a href="{{ route('transactions.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="text-sm lg:text-base">Transaksi</span>
                    </a>
                    <a href="{{ route('analysis.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-chart-pie"></i>
                        <span class="text-sm lg:text-base">Analisis</span>
                    </a>
                    <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-wallet"></i>
                        <span class="text-sm lg:text-base">Anggaran</span>
                    </a>
                    <a href="{{ route('tabungan.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-bullseye"></i>
                        <span class="text-sm lg:text-base">Target Tabungan</span>
                    </a>
                    <a href="{{ route('laporan.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-file-invoice"></i>
                        <span class="text-sm lg:text-base">Laporan</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-cog"></i>
                        <span class="text-sm lg:text-base">Pengaturan</span>
                    </a>
                </nav>

                <!-- Logout Button -->
                <div class="absolute bottom-4 lg:bottom-6 left-4 lg:left-6 right-4 lg:right-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="text-sm lg:text-base">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.remove('hidden');
            });
        }

        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        }

        if (mobileMenu) {
            mobileMenu.addEventListener('click', (e) => {
                if (e.target.id === 'mobileMenu') {
                    mobileMenu.classList.add('hidden');
                }
            });
        }

        // Initialize Chart and other functionality
        document.addEventListener('DOMContentLoaded', function() {
            initializeFinancialChart();
            
            // Chart period change
            document.getElementById('chartPeriod')?.addEventListener('change', function() {
                const period = this.value;
                updateChartData(period);
            });
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = `/transactions?search=${encodeURIComponent(query)}`;
                        }
                    }
                });
            }
            
            // Animate progress bars
            animateProgressBars();
        });

        function initializeFinancialChart() {
            const ctx = document.getElementById('financialChart');
            if (!ctx) return;
            
            // Data from controller
            const chartData = @json($financialChart ?? []);
            
            if (!chartData.labels || chartData.labels.length === 0) {
                ctx.parentElement.innerHTML = `
                    <div class="h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-chart-bar text-3xl lg:text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-sm lg:text-base">Belum ada data transaksi</p>
                            <p class="text-gray-400 text-xs lg:text-sm mt-2">Tambah transaksi untuk melihat grafik</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels || [],
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: chartData.income || [],
                            backgroundColor: '#10B981',
                            borderColor: '#10B981',
                            borderWidth: 1
                        },
                        {
                            label: 'Pengeluaran',
                            data: chartData.expense || [],
                            backgroundColor: '#EF4444',
                            borderColor: '#EF4444',
                            borderWidth: 1
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
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
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
                        }
                    }
                }
            });
        }

        function updateChartData(period) {
            fetch(`/dashboard/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    // Destroy old chart
                    const chart = Chart.getChart('financialChart');
                    if (chart) {
                        chart.destroy();
                    }
                    
                    // Create new chart with updated data
                    const ctx = document.getElementById('financialChart');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels || [],
                            datasets: [
                                {
                                    label: 'Pemasukan',
                                    data: data.income || [],
                                    backgroundColor: '#10B981',
                                    borderColor: '#10B981',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Pengeluaran',
                                    data: data.expense || [],
                                    backgroundColor: '#EF4444',
                                    borderColor: '#EF4444',
                                    borderWidth: 1
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
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
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
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error updating chart:', error));
        }

        function animateProgressBars() {
            const progressBars = document.querySelectorAll('.h-2 > div');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        }

        // Quick stats update (optional feature)
        function updateQuickStats(type) {
            fetch(`/dashboard/quick-stats?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Quick stats updated:', data);
                    // Update UI with new stats
                    // This is optional - you can implement if needed
                });
        }
    </script>
</body>
</html>