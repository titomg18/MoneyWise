<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MoneyWise</title>
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
            <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                <i class="fas fa-home"></i>
                <span class="font-medium">Dashboard</span>
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
                            <input type="text" placeholder="Cari transaksi..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <a href="/transactions" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center text-sm lg:text-base">
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
                        <span class="text-green-600 bg-green-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">+12%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Pemasukan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp 8.450.000</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Bulan ini</p>
                </div>

                <!-- Card Pengeluaran -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-red-600 bg-red-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">-5%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Pengeluaran</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp 5.230.000</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Bulan ini</p>
                </div>

                <!-- Card Saldo -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-blue-600 bg-blue-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">+18%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Saldo Akhir</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp 3.220.000</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Surplus</p>
                </div>

                <!-- Card Target Tabungan -->
                <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bullseye text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-purple-600 bg-purple-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">65%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Target Tabungan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp 1.500.000</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Tercapai: Rp 975.000</p>
                </div>
            </div>

            <!-- Grafik dan Tabel -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Grafik Pemasukan vs Pengeluaran -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Pemasukan vs Pengeluaran</h3>
                        <select class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                            <option>Bulan Ini</option>
                            <option>3 Bulan Terakhir</option>
                            <option>Tahun Ini</option>
                        </select>
                    </div>
                    <div class="h-48 lg:h-64 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-chart-bar text-3xl lg:text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-sm lg:text-base">Visualisasi grafik akan muncul di sini</p>
                            <p class="text-gray-400 text-xs lg:text-sm mt-2">Grafik batang perbandingan bulanan</p>
                        </div>
                    </div>
                </div>

                <!-- Kategori Pengeluaran -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Kategori Pengeluaran</h3>
                    <div class="space-y-3 lg:space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Makan & Minum</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">35%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 35%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Transportasi</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">20%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 20%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Hiburan</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">15%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Tagihan</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">25%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Lainnya</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">5%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: 5%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center">
                            <i class="fas fa-chart-pie mr-2"></i> Lihat analisis lengkap
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Transaksi Terbaru</h3>
                    <a href="/transactions" class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
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
                            <tr>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">12 Jun 2023</td>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">Gaji Bulanan</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-green-100 text-green-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pemasukan</span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Gaji</td>
                                <td class="py-3 lg:py-4 font-bold text-green-600 text-xs lg:text-sm">+ Rp 5.000.000</td>
                            </tr>
                            <tr>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">11 Jun 2023</td>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">Belanja Bulanan</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-red-100 text-red-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pengeluaran</span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Makan & Minum</td>
                                <td class="py-3 lg:py-4 font-bold text-red-600 text-xs lg:text-sm">- Rp 850.000</td>
                            </tr>
                            <tr>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">10 Jun 2023</td>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">Bayar Listrik</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-red-100 text-red-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pengeluaran</span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Tagihan</td>
                                <td class="py-3 lg:py-4 font-bold text-red-600 text-xs lg:text-sm">- Rp 450.000</td>
                            </tr>
                            <tr>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">09 Jun 2023</td>
                                <td class="py-3 lg:py-4 font-medium text-xs lg:text-sm">Freelance Project</td>
                                <td class="py-3 lg:py-4">
                                    <span class="bg-green-100 text-green-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pemasukan</span>
                                </td>
                                <td class="py-3 lg:py-4 text-xs lg:text-sm">Usaha</td>
                                <td class="py-3 lg:py-4 font-bold text-green-600 text-xs lg:text-sm">+ Rp 1.500.000</td>
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
                        <span class="font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-sm lg:text-base">{{ Auth::user()->name }}</p>
                        <p class="text-xs lg:text-sm text-blue-200">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <nav class="space-y-2">
                    <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
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
    </script>
</body>
</html>