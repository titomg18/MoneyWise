<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis - MoneyWise</title>
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
        .chart-container {
            height: 300px;
        }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-circle {
            transition: stroke-dashoffset 0.5s ease;
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
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="/transactions" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-exchange-alt"></i>
                <span>Transaksi</span>
            </a>
            <a href="/analysis" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                <i class="fas fa-chart-pie"></i>
                <span>Analisis</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-wallet"></i>
                <span>Anggaran</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-bullseye"></i>
                <span>Target Tabungan</span>
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
                    <span class="font-bold">A</span>
                </div>
                <div>
                    <p class="font-medium">John Doe</p>
                    <p class="text-sm text-blue-200">john.doe@example.com</p>
                </div>
            </div>
            
            <form method="POST" action="/logout">
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
        <!-- Header Analisis -->
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Analisis Keuangan</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Analisis mendalam tentang keuangan Anda untuk pengambilan keputusan yang lebih baik</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative w-full sm:w-auto">
                            <select class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm lg:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Bulan Ini</option>
                                <option>3 Bulan Terakhir</option>
                                <option>6 Bulan Terakhir</option>
                                <option>Tahun Ini</option>
                                <option>Sepanjang Masa</option>
                            </select>
                        </div>
                        <button class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center text-sm lg:text-base">
                            <i class="fas fa-download mr-2"></i> Ekspor Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Analisis -->
        <div class="p-4 lg:p-6">
            <!-- Ringkasan Analisis -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Card Tren Pengeluaran -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-red-600 bg-red-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">+8.5%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Tren Pengeluaran</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Naik 8.5%</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">vs bulan lalu</p>
                </div>

                <!-- Card Rasio Penghematan -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-percentage text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-green-600 bg-green-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">+2.1%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Rasio Penghematan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">22.5%</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">dari total pemasukan</p>
                </div>

                <!-- Card Pengeluaran Terbesar -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-red-600 bg-red-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">35%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Pengeluaran Terbesar</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Makan & Minum</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">35% dari total pengeluaran</p>
                </div>

                <!-- Card Pemasukan Rata-rata -->
                <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-green-600 bg-green-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">+5.2%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Pemasukan Rata-rata</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp 8.2 Jt</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">per bulan (6 bulan terakhir)</p>
                </div>
            </div>

            <!-- Grafik dan Visualisasi -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Grafik Tren Bulanan -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Tren Pemasukan & Pengeluaran</h3>
                        <select class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                            <option>6 Bulan Terakhir</option>
                            <option>1 Tahun Terakhir</option>
                            <option>2 Tahun Terakhir</option>
                        </select>
                    </div>
                    <div class="chart-container flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4">
                        <!-- Placeholder untuk grafik line chart -->
                        <div class="relative w-full h-64">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-chart-line text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-sm lg:text-base">Grafik tren bulanan akan muncul di sini</p>
                                    <p class="text-gray-400 text-xs lg:text-sm mt-2">Line chart pemasukan vs pengeluaran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-center space-x-6">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Pemasukan</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-xs text-gray-600">Pengeluaran</span>
                        </div>
                    </div>
                </div>

                <!-- Diagram Kategori Pengeluaran -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Distribusi Kategori Pengeluaran</h3>
                    <div class="flex flex-col lg:flex-row items-center">
                        <div class="lg:w-1/2 flex justify-center mb-6 lg:mb-0">
                            <!-- Placeholder untuk pie chart -->
                            <div class="relative w-48 h-48 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-chart-pie text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500 text-sm">Diagram Pie</p>
                                </div>
                            </div>
                        </div>
                        <div class="lg:w-1/2 space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Makan & Minum</span>
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Rp 1.832.500</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: 35%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500">35% dari total</span>
                                    <span class="text-xs text-gray-500">Terbesar</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Transportasi</span>
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Rp 1.046.000</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: 20%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500">20% dari total</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Tagihan</span>
                                    <span class="text-xs lg:text-sm font-medium text-gray-700">Rp 785.000</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 15%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-xs text-gray-500">15% dari total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analisis Perbandingan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Perbandingan Bulanan -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Perbandingan Bulanan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-up text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Pemasukan Tertinggi</p>
                                    <p class="text-xs text-gray-500">Bulan April 2023</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">Rp 9.500.000</p>
                                <p class="text-xs text-green-600">+18% dari rata-rata</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-up text-red-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Pengeluaran Tertinggi</p>
                                    <p class="text-xs text-gray-500">Bulan Juni 2023</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">Rp 6.800.000</p>
                                <p class="text-xs text-red-600">+22% dari rata-rata</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-piggy-bank text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Penghematan Terbaik</p>
                                    <p class="text-xs text-gray-500">Bulan Maret 2023</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">Rp 3.200.000</p>
                                <p class="text-xs text-green-600">28% dari pemasukan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analisis Kebiasaan -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Analisis Kebiasaan Keuangan</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Konsistensi Menabung</span>
                                <span class="text-sm font-medium text-gray-700">75%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Anda konsisten menabung di 9 dari 12 bulan terakhir</p>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Pengeluaran Tidak Terduga</span>
                                <span class="text-sm font-medium text-gray-700">12%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 12%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">12% dari pengeluaran adalah pengeluaran tidak terduga</p>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Kepatuhan Anggaran</span>
                                <span class="text-sm font-medium text-gray-700">68%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 68%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Anda mematuhi anggaran di 8 dari 12 kategori</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">Rekomendasi:</p>
                        <p class="text-xs text-gray-600">Fokus pada pengurangan pengeluaran kategori "Makan & Minum" untuk meningkatkan rasio penghematan bulanan.</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Analisis Detail -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6 mb-6 lg:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Analisis Detail per Kategori</h3>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center px-3 py-1 border border-blue-200 rounded-lg">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <button class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center px-3 py-1 border border-blue-200 rounded-lg">
                            <i class="fas fa-sort mr-2"></i> Urutkan
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-gray-500 text-xs lg:text-sm border-b">
                                <th class="pb-3 font-medium">Kategori</th>
                                <th class="pb-3 font-medium">Jumlah</th>
                                <th class="pb-3 font-medium">% dari Total</th>
                                <th class="pb-3 font-medium">Tren</th>
                                <th class="pb-3 font-medium">vs Rata-rata</th>
                                <th class="pb-3 font-medium">Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                        Makan & Minum
                                    </div>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Rp 1.832.500</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">35%</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full flex items-center w-20">
                                        <i class="fas fa-arrow-up mr-1 text-xs"></i> Naik 12%
                                    </span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm text-red-600">+22%</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">
                                    <span class="text-yellow-600">Perlu dikurangi</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        Transportasi
                                    </div>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Rp 1.046.000</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">20%</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full flex items-center w-20">
                                        <i class="fas fa-arrow-right mr-1 text-xs"></i> Stabil
                                    </span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm text-gray-600">+5%</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">
                                    <span class="text-green-600">Optimal</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        Tagihan
                                    </div>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Rp 785.000</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">15%</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full flex items-center w-20">
                                        <i class="fas fa-arrow-down mr-1 text-xs"></i> Turun 3%
                                    </span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm text-green-600">-8%</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">
                                    <span class="text-green-600">Bagus</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                        Hiburan
                                    </div>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Rp 680.000</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">13%</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full flex items-center w-20">
                                        <i class="fas fa-arrow-up mr-1 text-xs"></i> Naik 8%
                                    </span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm text-red-600">+15%</td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">
                                    <span class="text-yellow-600">Perlu pengawasan</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t mt-6 lg:mt-8 p-4 lg:p-6">
            <div class="text-center text-gray-500 text-xs lg:text-sm">
                <p>&copy; 2023 MoneyWise. Aplikasi manajemen keuangan pribadi.</p>
                <p class="mt-2">Analisis keuangan membantu Anda membuat keputusan yang lebih bijak</p>
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
                        <span class="font-bold text-lg">A</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm lg:text-base">John Doe</p>
                        <p class="text-xs lg:text-sm text-blue-200">john.doe@example.com</p>
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
                    <a href="/analysis" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                        <i class="fas fa-chart-pie"></i>
                        <span class="text-sm lg:text-base">Analisis</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-wallet"></i>
                        <span class="text-sm lg:text-base">Anggaran</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fas fa-bullseye"></i>
                        <span class="text-sm lg:text-base">Target Tabungan</span>
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

        // Simulasi loading data analisis
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Halaman Analisis MoneyWise dimuat');
            
            // Animasi sederhana untuk progress bars
            const progressBars = document.querySelectorAll('.h-2 > div');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>
</body>
</html>