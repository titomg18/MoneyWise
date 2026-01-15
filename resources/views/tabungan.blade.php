<!-- File: resources/views/tabungan.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Tabungan - MoneyWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
        .progress-ring {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
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
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-chart-pie"></i>
                <span>Analisis</span>
            </a>
            <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-wallet"></i>
                <span>Anggaran</span>
            </a>
            <a href="{{ route('tabungan.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                <i class="fas fa-bullseye"></i>
                <span class="font-medium">Target Tabungan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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
        <!-- Header Target Tabungan -->
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Target Tabungan</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Rencanakan dan pantau target tabungan Anda, {{ Auth::user()->name }}!</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="searchTabungan" placeholder="Cari target tabungan..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button id="tambahTargetBtn" class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all flex items-center justify-center text-sm lg:text-base">
                            <i class="fas fa-plus mr-2"></i> Target Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Tabungan -->
        <div class="p-4 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Total Tabungan -->
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-piggy-bank text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-emerald-600 bg-emerald-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">+15%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Tabungan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp 5.250.000</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Dari semua target</p>
                </div>

                <!-- Target Aktif -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bullseye text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-blue-600 bg-blue-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">3 Aktif</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Target Aktif</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">4 Target</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Sedang berjalan</p>
                </div>

                <!-- Target Tercapai -->
                <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-purple-600 bg-purple-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">2 Selesai</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Target Tercapai</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">2 Target</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Berhasil diselesaikan</p>
                </div>

                <!-- Rata-rata Waktu -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-amber-600 bg-amber-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">45 Hari</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Rata-rata Waktu</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">2.3 Bulan</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Per target</p>
                </div>
            </div>

            <!-- Daftar Target dan Grafik -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Daftar Target Tabungan -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Daftar Target Tabungan</h3>
                        <div class="flex space-x-2">
                            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <option value="all">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="overdue">Terlambat</option>
                            </select>
                            <button class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <div id="targetList" class="space-y-4 lg:space-y-5">
                        <!-- Target 1 -->
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: #10B98120">
                                        <i class="fas fa-car text-emerald-500"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">DP Mobil Baru</h4>
                                        <p class="text-xs text-gray-500">Target: Rp 50.000.000</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp 32.500.000</p>
                                    <p class="text-xs text-gray-500">65% tercapai</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">Tersisa: 45 hari</span>
                                <span class="text-emerald-600 font-medium">On Track</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full bg-emerald-500" style="width: 65%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span>Mulai: 01 Jan 2024</span>
                                <span>Target: 30 Jun 2024</span>
                            </div>
                        </div>

                        <!-- Target 2 -->
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: #8B5CF620">
                                        <i class="fas fa-umbrella-beach text-purple-500"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Liburan ke Bali</h4>
                                        <p class="text-xs text-gray-500">Target: Rp 15.000.000</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp 15.000.000</p>
                                    <p class="text-xs text-emerald-500">100% tercapai</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">Selesai!</span>
                                <span class="text-emerald-600 font-medium"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full bg-emerald-500" style="width: 100%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span>Mulai: 01 Mar 2024</span>
                                <span>Selesai: 01 Jun 2024</span>
                            </div>
                        </div>

                        <!-- Target 3 -->
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: #F59E0B20">
                                        <i class="fas fa-laptop text-amber-500"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Laptop Baru</h4>
                                        <p class="text-xs text-gray-500">Target: Rp 12.000.000</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp 8.400.000</p>
                                    <p class="text-xs text-gray-500">70% tercapai</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">Tersisa: 15 hari</span>
                                <span class="text-amber-600 font-medium">Perlu percepatan</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full bg-amber-500" style="width: 70%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span>Mulai: 01 Apr 2024</span>
                                <span>Target: 30 Jun 2024</span>
                            </div>
                        </div>

                        <!-- Target 4 -->
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: #EF444420">
                                        <i class="fas fa-graduation-cap text-red-500"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Kursus Online</h4>
                                        <p class="text-xs text-gray-500">Target: Rp 3.000.000</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp 1.200.000</p>
                                    <p class="text-xs text-gray-500">40% tercapai</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">Terlambat 10 hari</span>
                                <span class="text-red-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i> Terlambat</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full bg-red-500" style="width: 40%"></div>
                            </div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span>Mulai: 01 Feb 2024</span>
                                <span class="text-red-500">Target: 30 Mei 2024</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <button id="tambahTargetBtnFooter" class="text-emerald-600 hover:text-emerald-800 font-medium text-sm flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Target Tabungan Baru
                        </button>
                    </div>
                </div>

                <!-- Grafik Progress dan Tips -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Progress Tabungan</h3>
                    
                    <!-- Grafik Progress -->
                    <div class="flex flex-col items-center justify-center mb-6">
                        <div class="relative w-48 h-48">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <!-- Background circle -->
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#E5E7EB" stroke-width="10"/>
                                <!-- Progress circle -->
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#10B981" stroke-width="10" 
                                        stroke-dasharray="283" stroke-dashoffset="113.2" 
                                        class="progress-ring" stroke-linecap="round"/>
                                <!-- Inner text -->
                                <text x="50" y="50" text-anchor="middle" dy=".3em" class="text-2xl font-bold fill-gray-800">60%</text>
                                <text x="50" y="60" text-anchor="middle" class="text-xs fill-gray-500">Overall Progress</text>
                            </svg>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-600">Rata-rata progress semua target tabungan</p>
                        </div>
                    </div>
                    
                    <!-- Tips Tabungan -->
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-lightbulb text-blue-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Tips #1: Tabung Otomatis</h4>
                                    <p class="text-xs text-gray-600">Setel transfer otomatis ke rekening tabungan setiap gajian.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-100 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-chart-line text-emerald-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Tips #2: Mulai Kecil</h4>
                                    <p class="text-xs text-gray-600">Mulai dengan target kecil untuk membangun kebiasaan menabung.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bullseye text-purple-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Tips #3: Deadline Realistis</h4>
                                    <p class="text-xs text-gray-600">Tetapkan deadline yang realistis sesuai kemampuan keuangan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="#" class="text-emerald-600 hover:text-emerald-800 font-medium text-xs lg:text-sm flex items-center">
                            <i class="fas fa-book mr-2"></i> Pelajari strategi tabungan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Analisis dan Pencapaian -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Analisis Pencapaian -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Analisis Pencapaian</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Tercapai Tepat Waktu</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">50%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 50%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Lebih Cepat</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">25%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Terlambat</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">15%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Dibatalkan</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">10%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-400 h-2 rounded-full" style="width: 10%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Analisis berdasarkan 20 target tabungan sebelumnya</p>
                    </div>
                </div>

                <!-- Pencapaian Terbaru -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Pencapaian Terbaru</h3>
                        <span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">2 Minggu Terakhir</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-emerald-50 rounded-xl">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-trophy text-emerald-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Target "Liburan ke Bali" tercapai!</p>
                                <p class="text-xs text-gray-600">Rp 15.000.000 • 01 Jun 2024</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-blue-50 rounded-xl">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-chart-line text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Progress "DP Mobil" mencapai 65%</p>
                                <p class="text-xs text-gray-600">Rp 32.5 juta dari Rp 50 juta</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-amber-50 rounded-xl">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Target "Kursus Online" terlambat</p>
                                <p class="text-xs text-gray-600">Butuh Rp 1.8 juta dalam 10 hari</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-purple-50 rounded-xl">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-plus-circle text-purple-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Target baru dibuat: "Emergency Fund"</p>
                                <p class="text-xs text-gray-600">Rp 10 juta dalam 6 bulan</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                        <a href="#" class="text-emerald-600 hover:text-emerald-800 font-medium text-xs lg:text-sm flex items-center">
                            <i class="fas fa-history mr-2"></i> Lihat riwayat lengkap
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Bulanan -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Ringkasan Tabungan Bulanan</h3>
                    <select class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                        <option>Juni 2024</option>
                        <option>Mei 2024</option>
                        <option>April 2024</option>
                        <option>Maret 2024</option>
                    </select>
                </div>
                <div class="h-48 lg:h-64 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-3xl lg:text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-sm lg:text-base">Grafik tabungan bulanan akan muncul di sini</p>
                        <p class="text-gray-400 text-xs lg:text-sm mt-2">Grafik batang perkembangan tabungan per bulan</p>
                    </div>
                </div>
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

    <!-- Modal Tambah Target Tabungan -->
    <div id="modalTambahTarget" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Buat Target Tabungan Baru</h3>
                    <button id="tutupModalTarget" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="formTambahTarget">
                    <div class="space-y-4">
                        <div>
                            <label for="target_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Target *</label>
                            <input type="text" id="target_name" name="target_name" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                   placeholder="Contoh: DP Rumah, Liburan, Laptop Baru" required>
                            <p class="text-xs text-gray-500 mt-1">Berikan nama yang mudah diingat untuk target Anda</p>
                        </div>
                        
                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1">Target Jumlah *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="target_amount" name="target_amount" 
                                       class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                       placeholder="0" min="0" step="100000" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jumlah total yang ingin Anda tabung</p>
                        </div>
                        
                        <div>
                            <label for="saved_amount" class="block text-sm font-medium text-gray-700 mb-1">Sudah Tertabung</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="saved_amount" name="saved_amount" 
                                       class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                       placeholder="0" min="0" step="100000">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jumlah yang sudah Anda tabung (jika ada)</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                                <input type="date" id="start_date" name="start_date" 
                                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Target *</label>
                                <input type="date" id="end_date" name="end_date" 
                                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Target</label>
                            <select id="target_category" name="target_category" 
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="transportasi">Transportasi</option>
                                <option value="rumah">Rumah & Properti</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="liburan">Liburan & Hiburan</option>
                                <option value="elektronik">Elektronik</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih kategori untuk target tabungan</p>
                        </div>
                        
                        <div>
                            <label for="target_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                            <textarea id="target_description" name="target_description" 
                                      class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                      rows="3" placeholder="Tambahkan catatan atau motivasi untuk target ini"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Deskripsi atau catatan tambahan</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Warna Target *</label>
                            <div class="flex space-x-2">
                                @php
                                    $tabunganColors = [
                                        ['id' => 'color-emerald', 'value' => '#10B981', 'label' => 'Hijau'],
                                        ['id' => 'color-blue', 'value' => '#3B82F6', 'label' => 'Biru'],
                                        ['id' => 'color-purple', 'value' => '#8B5CF6', 'label' => 'Ungu'],
                                        ['id' => 'color-amber', 'value' => '#F59E0B', 'label' => 'Kuning'],
                                        ['id' => 'color-pink', 'value' => '#EC4899', 'label' => 'Pink'],
                                        ['id' => 'color-indigo', 'value' => '#6366F1', 'label' => 'Indigo'],
                                    ];
                                @endphp
                                
                                @foreach($tabunganColors as $color)
                                <div class="flex flex-col items-center">
                                    <input type="radio" id="{{ $color['id'] }}" name="target_color" value="{{ $color['value'] }}" 
                                           class="hidden" {{ $loop->first ? 'checked' : '' }}>
                                    <label for="{{ $color['id'] }}" 
                                           class="w-8 h-8 rounded-full border-2 border-white shadow-sm cursor-pointer hover:scale-110 transition-transform"
                                           style="background-color: {{ $color['value'] }}"
                                           title="{{ $color['label'] }}"></label>
                                </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Pilih warna untuk target ini</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="batalTambahTarget" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="submitTargetButton"
                                class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            <span>Simpan Target</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                        <span class="font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm lg:text-base">{{ Auth::user()->name }}</p>
                        <p class="text-xs lg:text-sm text-blue-200">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <nav class="space-y-2">
                    <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-home"></i>
                        <span class="font-medium text-sm lg:text-base">Dashboard</span>
                    </a>
                    <a href="/transactions" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="text-sm lg:text-base">Transaksi</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-chart-pie"></i>
                        <span class="text-sm lg:text-base">Analisis</span>
                    </a>
                    <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-wallet"></i>
                        <span class="text-sm lg:text-base">Anggaran</span>
                    </a>
                    <a href="{{ route('tabungan.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                        <i class="fas fa-bullseye"></i>
                        <span class="font-medium text-sm lg:text-base">Target Tabungan</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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
                    <form method="POST" action="/logout">
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
        // ============================================
        // VARIABEL DAN KONSTANTA
        // ============================================
        let targetsData = [];

        // ============================================
        // FUNGSI UTILITY
        // ============================================

        // Format angka ke Rupiah
        function formatRupiah(amount) {
            if (amount === null || amount === undefined) return 'Rp 0';
            return 'Rp ' + parseInt(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Hitung persentase
        function calculatePercentage(saved, target) {
            if (target === 0) return 0;
            return Math.min(100, (saved / target) * 100);
        }

        // Hitung sisa hari
        function calculateRemainingDays(endDate) {
            const today = new Date();
            const targetDate = new Date(endDate);
            const diffTime = targetDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        }

        // Tentukan status target
        function getTargetStatus(saved, target, startDate, endDate) {
            const percentage = calculatePercentage(saved, target);
            const remainingDays = calculateRemainingDays(endDate);
            
            if (percentage >= 100) return 'completed';
            if (remainingDays < 0) return 'overdue';
            if (percentage >= 70 && remainingDays > 7) return 'on_track';
            if (percentage < 50 && remainingDays < 15) return 'need_speed';
            return 'active';
        }

        // Tentukan warna berdasarkan status
        function getStatusColor(status) {
            switch(status) {
                case 'completed': return '#10B981';
                case 'on_track': return '#10B981';
                case 'active': return '#3B82F6';
                case 'need_speed': return '#F59E0B';
                case 'overdue': return '#EF4444';
                default: return '#6B7280';
            }
        }

        // Tentukan teks status
        function getStatusText(status) {
            switch(status) {
                case 'completed': return '<i class="fas fa-check-circle mr-1"></i> Selesai';
                case 'on_track': return 'On Track';
                case 'active': return 'Aktif';
                case 'need_speed': return 'Perlu percepatan';
                case 'overdue': return '<i class="fas fa-exclamation-triangle mr-1"></i> Terlambat';
                default: return 'Tidak Diketahui';
            }
        }

        // ============================================
        // FUNGSI NOTIFIKASI
        // ============================================
        function showNotification(message, type = 'success') {
            // Hapus notifikasi sebelumnya
            const existingNotification = document.querySelector('.custom-notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            // Tentukan warna berdasarkan jenis notifikasi
            const bgColor = type === 'success' ? 'bg-emerald-500' : 
                           type === 'error' ? 'bg-red-500' : 
                           type === 'warning' ? 'bg-amber-500' : 'bg-blue-500';
            
            const icon = type === 'success' ? 'fa-check-circle' : 
                        type === 'error' ? 'fa-exclamation-circle' : 
                        type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = `custom-notification fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${bgColor} text-white`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-3"></i>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animasi masuk
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);
            
            // Animasi keluar setelah 3 detik
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // ============================================
        // FUNGSI LOAD DATA TARGET
        // ============================================
        async function loadTargets() {
            try {
                // Untuk sekarang kita buat data dummy
                // Nanti bisa diganti dengan API call
                targetsData = [
                    {
                        id: 1,
                        name: 'DP Mobil Baru',
                        target_amount: 50000000,
                        saved_amount: 32500000,
                        start_date: '2024-01-01',
                        end_date: '2024-06-30',
                        category: 'transportasi',
                        color: '#10B981',
                        description: 'Untuk DP mobil keluarga'
                    },
                    {
                        id: 2,
                        name: 'Liburan ke Bali',
                        target_amount: 15000000,
                        saved_amount: 15000000,
                        start_date: '2024-03-01',
                        end_date: '2024-06-01',
                        category: 'liburan',
                        color: '#8B5CF6',
                        description: 'Liburan akhir tahun'
                    },
                    {
                        id: 3,
                        name: 'Laptop Baru',
                        target_amount: 12000000,
                        saved_amount: 8400000,
                        start_date: '2024-04-01',
                        end_date: '2024-06-30',
                        category: 'elektronik',
                        color: '#F59E0B',
                        description: 'Untuk kerja dan editing'
                    },
                    {
                        id: 4,
                        name: 'Kursus Online',
                        target_amount: 3000000,
                        saved_amount: 1200000,
                        start_date: '2024-02-01',
                        end_date: '2024-05-30',
                        category: 'pendidikan',
                        color: '#EF4444',
                        description: 'Kursus programming'
                    }
                ];
                
                // Update statistik
                updateStatistics();
                
                // Update daftar target
                updateTargetList();
                
            } catch (error) {
                console.error('Error loading targets:', error);
                showNotification('Gagal memuat data target tabungan', 'error');
            }
        }

        // Update statistik
        function updateStatistics() {
            if (targetsData.length === 0) return;
            
            // Hitung total tabungan
            const totalSaved = targetsData.reduce((sum, target) => sum + target.saved_amount, 0);
            document.querySelector('p:contains("Total Tabungan")').nextElementSibling.textContent = formatRupiah(totalSaved);
            
            // Hitung target aktif
            const activeTargets = targetsData.filter(target => {
                const status = getTargetStatus(target.saved_amount, target.target_amount, target.start_date, target.end_date);
                return status !== 'completed';
            }).length;
            
            // Update UI
            const activeElement = document.querySelector('span:contains("Aktif")');
            if (activeElement) {
                activeElement.textContent = activeTargets + ' Aktif';
                activeElement.parentElement.previousElementSibling.querySelector('p').textContent = activeTargets + ' Target';
            }
            
            // Hitung target selesai
            const completedTargets = targetsData.filter(target => {
                const status = getTargetStatus(target.saved_amount, target.target_amount, target.start_date, target.end_date);
                return status === 'completed';
            }).length;
            
            // Update progress ring
            const progressRing = document.querySelector('.progress-ring');
            if (progressRing) {
                const totalTargets = targetsData.length;
                const averageProgress = targetsData.reduce((sum, target) => {
                    return sum + calculatePercentage(target.saved_amount, target.target_amount);
                }, 0) / totalTargets;
                
                const circumference = 2 * Math.PI * 45;
                const offset = circumference - (averageProgress / 100) * circumference;
                progressRing.style.strokeDashoffset = offset;
                
                // Update teks
                const textElement = document.querySelector('text.fill-gray-800');
                if (textElement) {
                    textElement.textContent = Math.round(averageProgress) + '%';
                }
            }
        }

        // Update daftar target
        function updateTargetList() {
            const targetList = document.getElementById('targetList');
            
            if (!targetsData || targetsData.length === 0) {
                targetList.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bullseye text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-700 mb-2">Belum ada target tabungan</h4>
                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan target tabungan pertama Anda</p>
                        <button id="tambahTargetBtnEmpty" class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all">
                            <i class="fas fa-plus mr-2"></i> Tambah Target Pertama
                        </button>
                    </div>
                `;
                
                // Tambahkan event listener
                document.getElementById('tambahTargetBtnEmpty')?.addEventListener('click', () => {
                    modalTambahTarget.classList.remove('hidden');
                });
                return;
            }
            
            let html = '';
            targetsData.forEach(target => {
                const percentage = calculatePercentage(target.saved_amount, target.target_amount);
                const remaining = target.target_amount - target.saved_amount;
                const remainingDays = calculateRemainingDays(target.end_date);
                const status = getTargetStatus(target.saved_amount, target.target_amount, target.start_date, target.end_date);
                const statusColor = getStatusColor(status);
                const statusText = getStatusText(status);
                
                // Format tanggal
                const startDate = new Date(target.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                const endDate = new Date(target.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                
                // Tentukan icon berdasarkan kategori
                let icon = 'fa-bullseye';
                switch(target.category) {
                    case 'transportasi': icon = 'fa-car'; break;
                    case 'rumah': icon = 'fa-home'; break;
                    case 'pendidikan': icon = 'fa-graduation-cap'; break;
                    case 'liburan': icon = 'fa-umbrella-beach'; break;
                    case 'elektronik': icon = 'fa-laptop'; break;
                    case 'kesehatan': icon = 'fa-heart'; break;
                    default: icon = 'fa-bullseye';
                }
                
                html += `
                    <div class="target-item border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow" data-category="${target.category}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                            <div class="flex items-center mb-2 sm:mb-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: ${target.color}20">
                                    <i class="fas ${icon}" style="color: ${target.color}"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">${target.name}</h4>
                                    <p class="text-xs text-gray-500">Target: ${formatRupiah(target.target_amount)}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">${formatRupiah(target.saved_amount)}</p>
                                <p class="text-xs ${status === 'completed' ? 'text-emerald-500' : 'text-gray-500'}">${Math.round(percentage)}% tercapai</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600">
                                ${remainingDays >= 0 ? `Tersisa: ${remainingDays} hari` : `Terlambat: ${Math.abs(remainingDays)} hari`}
                            </span>
                            <span class="font-medium" style="color: ${statusColor}">${statusText}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full" style="width: ${percentage}%; background-color: ${statusColor}"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-500">
                            <span>Mulai: ${startDate}</span>
                            <span ${status === 'overdue' ? 'class="text-red-500"' : ''}>${status === 'completed' ? 'Selesai' : 'Target'}: ${endDate}</span>
                        </div>
                    </div>
                `;
            });
            
            targetList.innerHTML = html;
        }

        // ============================================
        // FUNGSI FILTER DAN PENCARIAN
        // ============================================
        function setupSearch() {
            const searchInput = document.getElementById('searchTabungan');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    const targetItems = document.querySelectorAll('.target-item');
                    
                    targetItems.forEach(item => {
                        const name = item.querySelector('h4').textContent.toLowerCase();
                        const description = item.textContent.toLowerCase();
                        
                        if (name.includes(searchTerm) || description.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        }

        function setupFilter() {
            const filterSelect = document.getElementById('filterStatus');
            
            if (filterSelect) {
                filterSelect.addEventListener('change', function(e) {
                    const filterValue = e.target.value;
                    const targetItems = document.querySelectorAll('.target-item');
                    
                    // Implement filter logic here
                    // For now, just show all
                    targetItems.forEach(item => {
                        item.style.display = 'block';
                    });
                });
            }
        }

        // ============================================
        // EVENT LISTENERS
        // ============================================

        // DOM Elements
        const modalTambahTarget = document.getElementById('modalTambahTarget');
        const mobileMenu = document.getElementById('mobileMenu');
        const formTambahTarget = document.getElementById('formTambahTarget');

        // Mobile menu toggle
        document.getElementById('mobileMenuButton')?.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });

        document.getElementById('closeMobileMenu')?.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });

        mobileMenu?.addEventListener('click', (e) => {
            if (e.target.id === 'mobileMenu') {
                mobileMenu.classList.add('hidden');
            }
        });

        // Modal tambah target - semua tombol pembuka modal
        const semuaTombolTambah = [
            'tambahTargetBtn',
            'tambahTargetBtnFooter',
            'tambahTargetBtnEmpty'
        ];
        
        semuaTombolTambah.forEach(buttonId => {
            document.getElementById(buttonId)?.addEventListener('click', () => {
                modalTambahTarget.classList.remove('hidden');
            });
        });

        // Modal tutup dan batal
        document.getElementById('tutupModalTarget')?.addEventListener('click', () => {
            modalTambahTarget.classList.add('hidden');
        });

        document.getElementById('batalTambahTarget')?.addEventListener('click', () => {
            modalTambahTarget.classList.add('hidden');
        });

        // Tutup modal saat klik di luar
        modalTambahTarget?.addEventListener('click', (e) => {
            if (e.target.id === 'modalTambahTarget') {
                modalTambahTarget.classList.add('hidden');
            }
        });

        // Form submission - Tambah target
        formTambahTarget?.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitButton = document.getElementById('submitTargetButton');
            const originalText = submitButton.innerHTML;
            
            // Tampilkan loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitButton.disabled = true;
            
            // Simulasi API call
            setTimeout(() => {
                // Dapatkan data dari form
                const formData = new FormData(formTambahTarget);
                const newTarget = {
                    id: targetsData.length + 1,
                    name: formData.get('target_name'),
                    target_amount: parseInt(formData.get('target_amount')),
                    saved_amount: parseInt(formData.get('saved_amount')) || 0,
                    start_date: formData.get('start_date'),
                    end_date: formData.get('end_date'),
                    category: formData.get('target_category'),
                    color: formData.get('target_color'),
                    description: formData.get('target_description')
                };
                
                // Tambahkan ke data
                targetsData.push(newTarget);
                
                showNotification('Target tabungan berhasil ditambahkan!', 'success');
                
                // Reset form dan tutup modal
                formTambahTarget.reset();
                document.getElementById('color-emerald').checked = true;
                modalTambahTarget.classList.add('hidden');
                
                // Refresh data
                setTimeout(() => {
                    updateStatistics();
                    updateTargetList();
                }, 500);
                
                // Kembalikan state tombol
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 1500);
        });

        // ============================================
        // INISIALISASI SAAT HALAMAN DIMUAT
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Load data target
            loadTargets();
            
            // Setup pencarian dan filter
            setupSearch();
            setupFilter();
            
            // Set tanggal default di form
            const today = new Date();
            const oneMonthLater = new Date();
            oneMonthLater.setMonth(today.getMonth() + 1);
            
            document.getElementById('start_date').value = today.toISOString().split('T')[0];
            document.getElementById('end_date').value = oneMonthLater.toISOString().split('T')[0];
            
            // Animasi progress bar
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.target-item .h-2.rounded-full');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 300);
                });
            }, 500);
        });
    </script>
</body>
</html>