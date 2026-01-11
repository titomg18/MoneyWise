<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anggaran - MoneyWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
        .notification {
            transition: all 0.3s ease-in-out;
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
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
                <i class="fas fa-chart-pie"></i>
                <span>Analisis</span>
            </a>
            <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
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
        <!-- Header Anggaran -->
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Kelola Anggaran</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Kelola anggaran bulanan Anda dengan bijak, {{ Auth::user()->name }}!</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="searchBudget" placeholder="Cari kategori anggaran..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <button id="tambahAnggaranBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center text-sm lg:text-base">
                            <i class="fas fa-plus mr-2"></i> Tambah Anggaran
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Anggaran -->
        <div class="p-4 lg:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Total Anggaran -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wallet text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-blue-600 bg-blue-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">Bulan Ini</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Anggaran</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800" id="totalBudget">Rp 0</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2" id="currentMonth"></p>
                </div>

                <!-- Anggaran Terpakai -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-check-alt text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-amber-600 bg-amber-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium" id="spentPercentage">0%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Anggaran Terpakai</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800" id="spentAmount">Rp 0</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Dari total anggaran</p>
                </div>

                <!-- Sisa Anggaran -->
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-emerald-600 bg-emerald-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium" id="remainingPercentage">0%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Sisa Anggaran</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800" id="remainingAmount">Rp 0</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Tersisa untuk bulan ini</p>
                </div>

                <!-- Anggaran Rata-rata -->
                <div class="bg-gradient-to-r from-violet-50 to-purple-50 border border-violet-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-violet-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-violet-600 bg-violet-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium" id="averageChange">0%</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Rata-rata Bulanan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800" id="averageAmount">Rp 0</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">6 bulan terakhir</p>
                </div>
            </div>

            <!-- Ringkasan Anggaran dan Kategori -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Ringkasan Anggaran -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Ringkasan Anggaran Bulanan</h3>
                        <div class="flex space-x-2">
                            <select id="monthFilter" class="border border-gray-300 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                @php
                                    $currentMonth = now()->format('F Y');
                                    $prevMonth1 = now()->subMonth()->format('F Y');
                                    $prevMonth2 = now()->subMonths(2)->format('F Y');
                                @endphp
                                <option value="{{ now()->format('Y-m') }}">{{ $currentMonth }}</option>
                                <option value="{{ now()->subMonth()->format('Y-m') }}">{{ $prevMonth1 }}</option>
                                <option value="{{ now()->subMonths(2)->format('Y-m') }}">{{ $prevMonth2 }}</option>
                            </select>
                            <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded-lg px-3 py-1 text-xs lg:text-sm">
                                <i class="fas fa-download mr-1"></i> Ekspor
                            </button>
                        </div>
                    </div>
                    
                    <div id="budgetList" class="space-y-4 lg:space-y-5">
                        <!-- Data anggaran akan ditampilkan di sini -->
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-wallet text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-700 mb-2">Belum ada anggaran</h4>
                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan anggaran pertama Anda</p>
                            <button id="tambahAnggaranBtnEmpty" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all">
                                <i class="fas fa-plus mr-2"></i> Tambah Anggaran Pertama
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <button id="tambahAnggaranBtnFooter" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Kategori Anggaran Baru
                        </button>
                    </div>
                </div>

                <!-- Ringkasan Kategori -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Distribusi Anggaran</h3>
                    
                    <!-- Chart Ringkasan -->
                    <div id="budgetChart" class="h-48 lg:h-56 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl mb-6">
                        <div class="text-center">
                            <i class="fas fa-chart-pie text-3xl lg:text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-sm lg:text-base">Visualisasi distribusi anggaran</p>
                            <p class="text-gray-400 text-xs lg:text-sm mt-2">Diagram pie persentase per kategori</p>
                        </div>
                    </div>
                    
                    <!-- Legenda Kategori -->
                    <div id="categoryLegend" class="space-y-3">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-info-circle mb-2"></i>
                            <p class="text-sm">Belum ada kategori anggaran</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-xs lg:text-sm flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i> Lihat analisis detail
                        </a>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi dan Tips -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6 mb-6 lg:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-2 sm:mb-0">Rekomendasi & Tips Anggaran</h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Berdasarkan pola pengeluaran Anda</span>
                </div>
                
                <div id="budgetTips" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Tips akan ditampilkan di sini -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Mulai dengan anggaran sederhana</h4>
                                <p class="text-xs text-gray-600">Tambahkan kategori anggaran pertama Anda untuk memulai perencanaan keuangan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Tips anggaran akan diperbarui secara otomatis berdasarkan transaksi dan pola pengeluaran Anda.</p>
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

    <!-- Modal Tambah Anggaran -->
    <div id="modalTambahAnggaran" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Anggaran Baru</h3>
                    <button id="tutupModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="formTambahAnggaran" method="POST" action="{{ route('budget.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="category_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                            <input type="text" id="category_name" name="category_name" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="Contoh: Makan & Minum, Transportasi" required>
                            <p class="text-xs text-gray-500 mt-1">Nama kategori untuk anggaran Anda</p>
                        </div>
                        
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggaran *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="amount" name="amount" 
                                       class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="0" min="0" step="1000" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jumlah anggaran untuk kategori ini</p>
                        </div>
                        
                        <div>
                            <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Periode *</label>
                            <select id="period" name="period" 
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="Bulanan" selected>Bulanan</option>
                                <option value="Mingguan">Mingguan</option>
                                <option value="Tahunan">Tahunan</option>
                                <option value="Custom">Custom</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Periode anggaran</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Warna Kategori *</label>
                            <div class="flex space-x-2">
                                @php
                                    $colors = [
                                        ['id' => 'color-red', 'value' => '#EF4444', 'label' => 'Merah'],
                                        ['id' => 'color-blue', 'value' => '#3B82F6', 'label' => 'Biru'],
                                        ['id' => 'color-green', 'value' => '#10B981', 'label' => 'Hijau'],
                                        ['id' => 'color-yellow', 'value' => '#F59E0B', 'label' => 'Kuning'],
                                        ['id' => 'color-purple', 'value' => '#8B5CF6', 'label' => 'Ungu'],
                                        ['id' => 'color-pink', 'value' => '#EC4899', 'label' => 'Pink'],
                                    ];
                                @endphp
                                
                                @foreach($colors as $color)
                                <div class="flex flex-col items-center">
                                    <input type="radio" id="{{ $color['id'] }}" name="color" value="{{ $color['value'] }}" 
                                           class="hidden" {{ $loop->first ? 'checked' : '' }}>
                                    <label for="{{ $color['id'] }}" 
                                           class="w-8 h-8 rounded-full border-2 border-white shadow-sm cursor-pointer hover:scale-110 transition-transform"
                                           style="background-color: {{ $color['value'] }}"
                                           title="{{ $color['label'] }}"></label>
                                </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Pilih warna untuk kategori ini</p>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                            <textarea id="description" name="description" 
                                      class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      rows="3" placeholder="Tambahkan catatan untuk anggaran ini"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Deskripsi atau catatan tambahan</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="batalTambah" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="submitButton"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            <span>Simpan Anggaran</span>
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
                    <a href="{{ route('budget.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
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
        // ============================================
        // VARIABEL GLOBAL DAN KONSTANTA
        // ============================================
        let budgetsData = [];
        let currentMonth = new Date().toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

        // ============================================
        // FUNGSI UTILITY
        // ============================================

        // Format angka ke Rupiah
        function formatRupiah(amount) {
            if (amount === null || amount === undefined) return 'Rp 0';
            return 'Rp ' + parseInt(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Format persentase
        function formatPercentage(value) {
            return Math.round(value) + '%';
        }

        // Hitung persentase
        function calculatePercentage(part, total) {
            if (total === 0) return 0;
            return (part / total) * 100;
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
            const bgColor = type === 'success' ? 'bg-green-500' : 
                           type === 'error' ? 'bg-red-500' : 
                           type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
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
        // FUNGSI LOAD DATA ANGGARAN
        // ============================================
        async function loadBudgets() {
            try {
                const response = await fetch('{{ route("budget.data") }}');
                
                if (!response.ok) {
                    throw new Error('Gagal memuat data anggaran');
                }
                
                const data = await response.json();
                budgetsData = data.budgets || [];
                
                // Update statistik
                updateStatistics(data);
                
                // Update daftar anggaran
                updateBudgetList(data.budgets);
                
                // Update legenda kategori
                updateCategoryLegend(data.budgets);
                
                // Update tips
                updateBudgetTips(data.budgets);
                
            } catch (error) {
                console.error('Error loading budgets:', error);
                showNotification('Gagal memuat data anggaran', 'error');
            }
        }

        // Update statistik
        function updateStatistics(data) {
            // Total anggaran
            document.getElementById('totalBudget').textContent = formatRupiah(data.totalBudget);
            
            // Anggaran terpakai
            document.getElementById('spentAmount').textContent = formatRupiah(data.totalSpent);
            document.getElementById('spentPercentage').textContent = formatPercentage(data.totalSpentPercentage);
            
            // Sisa anggaran
            document.getElementById('remainingAmount').textContent = formatRupiah(data.remainingBudget);
            const remainingPercentage = 100 - data.totalSpentPercentage;
            document.getElementById('remainingPercentage').textContent = formatPercentage(remainingPercentage);
            
            // Bulan saat ini
            document.getElementById('currentMonth').textContent = currentMonth;
            
            // Rata-rata anggaran
            const averageBudget = data.budgets.length > 0 ? 
                data.budgets.reduce((sum, budget) => sum + budget.amount, 0) / data.budgets.length : 0;
            document.getElementById('averageAmount').textContent = formatRupiah(averageBudget);
            
            // Hitung perubahan rata-rata (contoh: -5%)
            const averageChange = data.budgets.length > 0 ? -5 : 0;
            document.getElementById('averageChange').textContent = averageChange + '%';
        }

        // Update daftar anggaran
        function updateBudgetList(budgets) {
            const budgetList = document.getElementById('budgetList');
            
            if (!budgets || budgets.length === 0) {
                budgetList.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-wallet text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-700 mb-2">Belum ada anggaran</h4>
                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan anggaran pertama Anda</p>
                        <button id="tambahAnggaranBtnEmpty" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all">
                            <i class="fas fa-plus mr-2"></i> Tambah Anggaran Pertama
                        </button>
                    </div>
                `;
                
                // Tambahkan event listener untuk tombol di state kosong
                document.getElementById('tambahAnggaranBtnEmpty')?.addEventListener('click', () => {
                    modalTambahAnggaran.classList.remove('hidden');
                });
                return;
            }
            
            let html = '';
            budgets.forEach(budget => {
                const percentage = calculatePercentage(budget.spent_amount, budget.amount);
                const remaining = budget.amount - budget.spent_amount;
                
                // Tentukan warna progress bar berdasarkan persentase
                let progressColor = budget.color;
                if (percentage >= 90) progressColor = '#EF4444'; // Merah
                else if (percentage >= 70) progressColor = '#F59E0B'; // Kuning
                
                html += `
                    <div class="budget-item border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow" data-category="${budget.category_name.toLowerCase()}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                            <div class="flex items-center mb-2 sm:mb-0">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: ${budget.color}20">
                                    <i class="fas fa-tag" style="color: ${budget.color}"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">${budget.category_name}</h4>
                                    <p class="text-xs text-gray-500">${budget.description || 'Tidak ada deskripsi'}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">${formatRupiah(budget.amount)}</p>
                                <p class="text-xs text-gray-500">${formatRupiah(budget.spent_amount)} terpakai</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600">${formatPercentage(percentage)} terpakai</span>
                            <span class="text-gray-600">${formatRupiah(remaining)} tersisa</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full progress-bar" style="width: ${percentage}%; background-color: ${progressColor}"></div>
                        </div>
                        <div class="flex justify-end mt-2 space-x-2">
                            <button class="edit-budget text-blue-600 hover:text-blue-800 text-sm" data-id="${budget.id}">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button class="delete-budget text-red-600 hover:text-red-800 text-sm" data-id="${budget.id}">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                `;
            });
            
            budgetList.innerHTML = html;
            
            // Tambahkan event listener untuk tombol edit dan hapus
            document.querySelectorAll('.edit-budget').forEach(button => {
                button.addEventListener('click', function() {
                    const budgetId = this.getAttribute('data-id');
                    editBudget(budgetId);
                });
            });
            
            document.querySelectorAll('.delete-budget').forEach(button => {
                button.addEventListener('click', function() {
                    const budgetId = this.getAttribute('data-id');
                    deleteBudget(budgetId);
                });
            });
        }

        // Update legenda kategori
        function updateCategoryLegend(budgets) {
            const categoryLegend = document.getElementById('categoryLegend');
            
            if (!budgets || budgets.length === 0) {
                categoryLegend.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-info-circle mb-2"></i>
                        <p class="text-sm">Belum ada kategori anggaran</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            const totalAmount = budgets.reduce((sum, budget) => sum + budget.amount, 0);
            
            budgets.forEach(budget => {
                const percentage = calculatePercentage(budget.amount, totalAmount);
                html += `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: ${budget.color}"></div>
                            <span class="text-sm text-gray-700 truncate">${budget.category_name}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-800">${formatPercentage(percentage)}</span>
                    </div>
                `;
            });
            
            categoryLegend.innerHTML = html;
        }

        // Update tips anggaran
        function updateBudgetTips(budgets) {
            const budgetTips = document.getElementById('budgetTips');
            
            if (!budgets || budgets.length === 0) {
                budgetTips.innerHTML = `
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Mulai dengan anggaran sederhana</h4>
                                <p class="text-xs text-gray-600">Tambahkan kategori anggaran pertama Anda untuk memulai perencanaan keuangan.</p>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }
            
            let html = '';
            
            // Analisis anggaran untuk tips
            budgets.forEach(budget => {
                const percentage = calculatePercentage(budget.spent_amount, budget.amount);
                let tip = '';
                let icon = 'fa-lightbulb';
                let bgColor = 'from-blue-50 to-cyan-50';
                let borderColor = 'border-blue-100';
                let textColor = 'text-blue-500';
                
                if (percentage >= 90) {
                    tip = `Anggaran <strong>${budget.category_name}</strong> hampir habis (${formatPercentage(percentage)}). Pertimbangkan untuk menambah anggaran atau mengurangi pengeluaran.`;
                    icon = 'fa-exclamation-triangle';
                    bgColor = 'from-red-50 to-orange-50';
                    borderColor = 'border-red-100';
                    textColor = 'text-red-500';
                } else if (percentage >= 70) {
                    tip = `Anggaran <strong>${budget.category_name}</strong> sudah ${formatPercentage(percentage)}. Pantau pengeluaran Anda dengan cermat.`;
                    icon = 'fa-exclamation-circle';
                    bgColor = 'from-yellow-50 to-amber-50';
                    borderColor = 'border-yellow-100';
                    textColor = 'text-yellow-500';
                } else if (percentage <= 30) {
                    tip = `Anggaran <strong>${budget.category_name}</strong> masih tersisa banyak (${formatPercentage(100 - percentage)}). Pertimbangkan untuk mengalokasikan ke kategori lain.`;
                    icon = 'fa-piggy-bank';
                    bgColor = 'from-green-50 to-emerald-50';
                    borderColor = 'border-green-100';
                    textColor = 'text-green-500';
                } else {
                    return; // Skip tips untuk persentase normal
                }
                
                html += `
                    <div class="bg-gradient-to-r ${bgColor} ${borderColor} rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: ${budget.color}20">
                                <i class="fas ${icon} ${textColor}"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Tips untuk ${budget.category_name}</h4>
                                <p class="text-xs text-gray-600">${tip}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            // Jika tidak ada tips khusus, tampilkan tips umum
            if (html === '') {
                const totalSpent = budgets.reduce((sum, budget) => sum + budget.spent_amount, 0);
                const totalBudget = budgets.reduce((sum, budget) => sum + budget.amount, 0);
                const overallPercentage = calculatePercentage(totalSpent, totalBudget);
                
                if (overallPercentage >= 70) {
                    html = `
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-chart-line text-amber-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Pengeluaran Tinggi</h4>
                                    <p class="text-xs text-gray-600">Total pengeluaran Anda sudah mencapai ${formatPercentage(overallPercentage)}. Pertimbangkan untuk mengevaluasi pengeluaran bulan ini.</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html = `
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-thumbs-up text-blue-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Pengelolaan Baik</h4>
                                    <p class="text-xs text-gray-600">Pengeluaran Anda terkendali dengan baik. Pertahankan pengelolaan anggaran yang sehat!</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            budgetTips.innerHTML = html;
        }

        // ============================================
        // FUNGSI CRUD ANGGARAN
        // ============================================

        // Tambah anggaran
        async function tambahAnggaran(formData) {
            try {
                const submitButton = document.getElementById('submitButton');
                const originalText = submitButton.innerHTML;
                
                // Tampilkan loading state
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                submitButton.disabled = true;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch('{{ route("budget.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showNotification(result.message || 'Anggaran berhasil ditambahkan!', 'success');
                    
                    // Reset form dan tutup modal
                    document.getElementById('formTambahAnggaran').reset();
                    document.getElementById('color-red').checked = true;
                    modalTambahAnggaran.classList.add('hidden');
                    
                    // Refresh data
                    setTimeout(() => {
                        loadBudgets();
                    }, 500);
                    
                } else {
                    throw new Error(result.message || 'Terjadi kesalahan saat menyimpan anggaran');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showNotification(error.message || 'Terjadi kesalahan jaringan', 'error');
            } finally {
                // Kembalikan state tombol
                const submitButton = document.getElementById('submitButton');
                submitButton.innerHTML = '<i class="fas fa-save mr-2"></i><span>Simpan Anggaran</span>';
                submitButton.disabled = false;
            }
        }

        // Edit anggaran
        async function editBudget(budgetId) {
            try {
                const response = await fetch(`/budget/${budgetId}/edit`);
                
                if (response.ok) {
                    // Redirect ke halaman edit
                    window.location.href = `/budget/${budgetId}/edit`;
                } else {
                    showNotification('Gagal membuka halaman edit', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            }
        }

        // Hapus anggaran
        async function deleteBudget(budgetId) {
            if (!confirm('Apakah Anda yakin ingin menghapus anggaran ini?')) {
                return;
            }
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch(`/budget/${budgetId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showNotification(result.message || 'Anggaran berhasil dihapus!', 'success');
                    
                    // Refresh data
                    setTimeout(() => {
                        loadBudgets();
                    }, 500);
                    
                } else {
                    throw new Error(result.message || 'Terjadi kesalahan saat menghapus anggaran');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showNotification(error.message || 'Terjadi kesalahan jaringan', 'error');
            }
        }

        // ============================================
        // FUNGSI PENCARIAN DAN FILTER
        // ============================================

        // Pencarian anggaran
        function setupSearch() {
            const searchInput = document.getElementById('searchBudget');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    const budgetItems = document.querySelectorAll('.budget-item');
                    
                    budgetItems.forEach(item => {
                        const category = item.getAttribute('data-category');
                        const title = item.querySelector('h4').textContent.toLowerCase();
                        const description = item.querySelector('p.text-xs').textContent.toLowerCase();
                        
                        if (title.includes(searchTerm) || 
                            description.includes(searchTerm) || 
                            category.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        }

        // Filter berdasarkan bulan
        function setupMonthFilter() {
            const monthFilter = document.getElementById('monthFilter');
            
            if (monthFilter) {
                monthFilter.addEventListener('change', function(e) {
                    const selectedMonth = e.target.value;
                    // TODO: Implement filter by month
                    showNotification('Filter berdasarkan bulan akan segera tersedia', 'info');
                });
            }
        }

        // ============================================
        // EVENT LISTENERS
        // ============================================

        // DOM Elements
        const modalTambahAnggaran = document.getElementById('modalTambahAnggaran');
        const formTambahAnggaran = document.getElementById('formTambahAnggaran');
        const mobileMenu = document.getElementById('mobileMenu');

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

        // Modal tambah anggaran - semua tombol pembuka modal
        const semuaTombolTambah = [
            'tambahAnggaranBtn',
            'tambahAnggaranBtnFooter',
            'tambahAnggaranBtnEmpty'
        ];
        
        semuaTombolTambah.forEach(buttonId => {
            document.getElementById(buttonId)?.addEventListener('click', () => {
                modalTambahAnggaran.classList.remove('hidden');
            });
        });

        // Modal tutup dan batal
        document.getElementById('tutupModal')?.addEventListener('click', () => {
            modalTambahAnggaran.classList.add('hidden');
        });

        document.getElementById('batalTambah')?.addEventListener('click', () => {
            modalTambahAnggaran.classList.add('hidden');
        });

        // Tutup modal saat klik di luar
        modalTambahAnggaran?.addEventListener('click', (e) => {
            if (e.target.id === 'modalTambahAnggaran') {
                modalTambahAnggaran.classList.add('hidden');
            }
        });

        // Form submission
        formTambahAnggaran?.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(formTambahAnggaran);
            tambahAnggaran(formData);
        });

        // ============================================
        // INISIALISASI SAAT HALAMAN DIMUAT
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Load data anggaran
            loadBudgets();
            
            // Setup pencarian dan filter
            setupSearch();
            setupMonthFilter();
            
            // Animasi progress bar
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.progress-bar');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 300);
                });
            }, 500);
            
            // Tampilkan bulan saat ini di filter
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const now = new Date();
            currentMonth = `${monthNames[now.getMonth()]} ${now.getFullYear()}`;
        });

        // ============================================
        // EVENT LISTENER UNTUK REAL-TIME UPDATES
        // ============================================
        // Refresh data setiap 30 detik untuk update real-time
        setInterval(() => {
            loadBudgets();
        }, 30000);
    </script>
</body>
</html>