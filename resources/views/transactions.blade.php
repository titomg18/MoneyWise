<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - MoneyWise</title>
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
        .selected-row {
            background-color: #f0f9ff !important;
        }
        /* Responsive table styles */
        @media (max-width: 768px) {
            .transaction-table {
                display: block;
            }
            .transaction-table thead {
                display: none;
            }
            .transaction-table tbody {
                display: block;
                width: 100%;
            }
            .transaction-table tr {
                display: block;
                margin-bottom: 1rem;
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .transaction-table td {
                display: block;
                text-align: right;
                padding: 0.75rem 1rem;
                border-bottom: 1px solid #f3f4f6;
            }
            .transaction-table td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                color: #6b7280;
            }
            .transaction-table td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Sidebar untuk Desktop -->
    <div class="hidden lg:flex flex-col fixed inset-y-0 w-64 bg-gradient-to-b from-blue-800 to-indigo-900 text-white shadow-xl">
        <!-- Logo -->
        <div class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
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
            <a href="/transactions" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                <i class="fas fa-exchange-alt"></i>
                <span class="font-medium">Transaksi</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                </div>
                <h1 class="text-lg font-bold font-montserrat">MoneyWise</h1>
            </div>
            <button id="mobileMenuButton" class="text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 pt-16 lg:pt-0 min-h-screen">
        <!-- Header Transaksi -->
        <div class="bg-white shadow-sm border-b">
            <div class="px-4 lg:px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">Manajemen Transaksi</h2>
                        <p class="text-gray-600 mt-1 text-sm lg:text-base">Kelola semua transaksi keuangan Anda di satu tempat</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0">
                        <button id="filterButton" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 w-full sm:w-auto">
                            <i class="fas fa-filter text-gray-500 mr-2"></i>
                            <span>Filter</span>
                        </button>
                        <button onclick="showAddTransactionModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-center w-full sm:w-auto">
                            <i class="fas fa-plus mr-2"></i> Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Filter (Awalnya Tersembunyi) -->
        <div id="filterPanel" class="bg-white border-b p-4 lg:p-6 hidden">
            <form id="filterForm" method="GET" action="{{ route('transactions.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base">
                            <option value="">Semua Jenis</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base">
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-4">
                    <button type="button" id="resetFilterButton" class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 w-full sm:w-auto order-2 sm:order-1">
                        Reset Filter
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 w-full sm:w-auto order-1 sm:order-2">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Notifikasi -->
        @if(session('success'))
        <div class="mx-4 lg:mx-6 mt-6">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm lg:text-base">{{ session('success') }}</span>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-4 lg:mx-6 mt-6">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="text-sm lg:text-base">{{ session('error') }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mx-4 lg:mx-6 mt-6">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <div class="text-sm lg:text-base">
                        @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Konten Utama Transaksi -->
        <div class="p-4 lg:p-6">
            <!-- Ringkasan Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <!-- Total Transaksi -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-white text-lg lg:text-xl"></i>
                        </div>
                        <div class="ml-3 lg:ml-4">
                            <h3 class="text-gray-500 text-xs lg:text-sm font-medium">Total Transaksi</h3>
                            <p class="text-lg lg:text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs lg:text-sm">Bulan ini</p>
                </div>

                <!-- Transaksi Pemasukan -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-down text-white text-lg lg:text-xl"></i>
                        </div>
                        <div class="ml-3 lg:ml-4">
                            <h3 class="text-gray-500 text-xs lg:text-sm font-medium">Total Pemasukan</h3>
                            <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs lg:text-sm">Bulan ini</p>
                </div>

                <!-- Transaksi Pengeluaran -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-arrow-up text-white text-lg lg:text-xl"></i>
                        </div>
                        <div class="ml-3 lg:ml-4">
                            <h3 class="text-gray-500 text-xs lg:text-sm font-medium">Total Pengeluaran</h3>
                            <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs lg:text-sm">Bulan ini</p>
                </div>
            </div>

            <!-- Daftar Transaksi -->
            <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 md:mb-0">Daftar Transaksi</h3>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <div class="relative">
                            <form method="GET" action="{{ route('transactions.index') }}" id="searchForm">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari transaksi..." 
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2 pl-10 text-sm lg:text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </form>
                        </div>
                        <button id="exportButton" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm lg:text-base">
                            <i class="fas fa-file-export text-gray-500 mr-2"></i>
                            <span>Ekspor</span>
                        </button>
                    </div>
                </div>

                <!-- Tabel Transaksi (Responsif) -->
                <div class="overflow-x-auto">
                    <table class="w-full transaction-table">
                        <thead class="hidden md:table-header-group">
                            <tr class="text-left text-gray-500 text-sm border-b">
                                <th class="pb-3 font-medium">Tanggal</th>
                                <th class="pb-3 font-medium">Deskripsi</th>
                                <th class="pb-3 font-medium">Kategori</th>
                                <th class="pb-3 font-medium">Anggaran</th>
                                <th class="pb-3 font-medium">Jenis</th>
                                <th class="pb-3 font-medium">Jumlah</th>
                                <th class="pb-3 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <!-- Tanggal (Responsif) -->
                                <td class="py-3 lg:py-4" data-label="Tanggal">
                                    <div class="font-medium text-sm lg:text-base">{{ $transaction->transaction_date->format('d M Y') }}</div>
                                    <div class="text-gray-500 text-xs lg:text-sm">{{ $transaction->created_at->format('H:i') }}</div>
                                </td>
                                
                                <!-- Deskripsi (Responsif) -->
                                <td class="py-3 lg:py-4 font-medium text-sm lg:text-base" data-label="Deskripsi">
                                    {{ Str::limit($transaction->description, 30) }}
                                    @if(strlen($transaction->description) > 30)
                                    <span class="text-blue-500 text-xs ml-1">...</span>
                                    @endif
                                </td>
                                
                                <!-- Kategori (Responsif) -->
                                <td class="py-3 lg:py-4" data-label="Kategori">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 lg:px-3 py-1 rounded-full">
                                        {{ $transaction->category->category_name ?? 'Tidak ada kategori' }}
                                    </span>
                                </td>

                                <!-- Anggaran (Responsif) -->
                                <td class="py-3 lg:py-4" data-label="Anggaran">
                                    @if($transaction->budget)
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $transaction->budget->color }}"></div>
                                        <span class="text-xs text-gray-700">{{ $transaction->budget->category_name }}</span>
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400">Tidak ada anggaran</span>
                                    @endif
                                </td>
                                
                                <!-- Jenis (Responsif) -->
                                <td class="py-3 lg:py-4" data-label="Jenis">
                                    @if($transaction->transaction_type == 'income')
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pemasukan</span>
                                    @else
                                    <span class="inline-block bg-red-100 text-red-800 text-xs px-2 lg:px-3 py-1 rounded-full">Pengeluaran</span>
                                    @endif
                                </td>
                                
                                <!-- Jumlah (Responsif) -->
                                <td class="py-3 lg:py-4 font-bold text-sm lg:text-base @if($transaction->transaction_type == 'income') text-green-600 @else text-red-600 @endif" data-label="Jumlah">
                                    @if($transaction->transaction_type == 'income')
                                    + Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    @else
                                    - Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    @endif
                                </td>
                                
                                <!-- Aksi (Responsif) -->
                                <td class="py-3 lg:py-4" data-label="Aksi">
                                    <div class="flex space-x-2 justify-end md:justify-start">
                                        <button onclick="editTransaction({{ $transaction->transaction_id }})" 
                                                class="text-blue-600 hover:text-blue-800 p-1">
                                            <i class="fas fa-edit text-sm lg:text-base"></i>
                                        </button>
                                        <form action="{{ route('transactions.destroy', $transaction->transaction_id) }}" 
                                              method="POST" 
                                              class="delete-form inline-block"
                                              data-description="{{ $transaction->description }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                                <i class="fas fa-trash text-sm lg:text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-exchange-alt text-3xl lg:text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-base lg:text-lg">Belum ada transaksi</p>
                                        <p class="text-xs lg:text-sm mt-2">Mulai dengan menambahkan transaksi pertama Anda</p>
                                        <button onclick="showAddTransactionModal()" class="mt-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all flex items-center text-sm lg:text-base">
                                            <i class="fas fa-plus mr-2"></i> Tambah Transaksi
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (Responsif) -->
                @if($transactions->hasPages())
                <div class="flex flex-col md:flex-row md:items-center justify-between mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                    <div class="text-gray-500 text-xs lg:text-sm mb-3 md:mb-0 text-center md:text-left">
                        Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                    </div>
                    <div class="flex justify-center space-x-1 lg:space-x-2">
                        @if($transactions->onFirstPage())
                        <span class="px-2 lg:px-3 py-1 border border-gray-300 rounded-lg text-gray-400">
                            <i class="fas fa-chevron-left text-xs lg:text-sm"></i>
                        </span>
                        @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="px-2 lg:px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-chevron-left text-xs lg:text-sm"></i>
                        </a>
                        @endif

                        @foreach(range(1, $transactions->lastPage()) as $i)
                            @if($i == $transactions->currentPage())
                            <span class="px-2 lg:px-3 py-1 bg-blue-600 text-white rounded-lg text-xs lg:text-sm">{{ $i }}</span>
                            @else
                            <a href="{{ $transactions->url($i) }}" class="px-2 lg:px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50 text-xs lg:text-sm">{{ $i }}</a>
                            @endif
                        @endforeach

                        @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="px-2 lg:px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-chevron-right text-xs lg:text-sm"></i>
                        </a>
                        @else
                        <span class="px-2 lg:px-3 py-1 border border-gray-300 rounded-lg text-gray-400">
                            <i class="fas fa-chevron-right text-xs lg:text-sm"></i>
                        </span>
                        @endif
                    </div>
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
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
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
                            <span class="text-sm lg:text-base">Dashboard</span>
                        </a>
                        <a href="/transactions" class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl">
                            <i class="fas fa-exchange-alt"></i>
                            <span class="font-medium text-sm lg:text-base">Transaksi</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5 rounded-xl transition-colors">
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

    <!-- Modal Tambah Transaksi (Responsif) -->
    <div id="transactionModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden flex items-center justify-center p-2 lg:p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto mx-2 lg:mx-0">
            <div class="p-4 lg:p-6">
                <div class="flex items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-lg lg:text-xl font-bold text-gray-800" id="modalTitle">Tambah Transaksi Baru</h3>
                    <button onclick="closeTransactionModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="transaction_id" name="transaction_id">
                    <input type="hidden" id="_method" name="_method" value="POST">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                            <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                                <label class="flex items-center">
                                    <input type="radio" name="transaction_type" value="income" class="mr-2" id="type_income" checked onchange="updateBudgetOptions()">
                                    <span class="flex items-center text-sm lg:text-base">
                                        <i class="fas fa-arrow-down text-green-500 mr-1"></i>
                                        Pemasukan
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="transaction_type" value="expense" class="mr-2" id="type_expense" onchange="updateBudgetOptions()">
                                    <span class="flex items-center text-sm lg:text-base">
                                        <i class="fas fa-arrow-up text-red-500 mr-1"></i>
                                        Pengeluaran
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="transaction_date" id="transaction_date" 
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base" required>
                            <span class="text-red-500 text-xs lg:text-sm hidden" id="date_error"></span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                            <input type="text" name="description" id="description" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base" 
                                   placeholder="Masukkan deskripsi transaksi" required>
                            <span class="text-red-500 text-xs lg:text-sm hidden" id="description_error"></span>
                        </div>
                        
                        <div class="flex items-end space-x-2">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                                <select name="category_id" id="category_id" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base" required onchange="updateBudgetOptions()">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" data-type="{{ $category->type }}">
                                        {{ $category->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="text-red-500 text-xs lg:text-sm hidden" id="category_error"></span>
                            </div>
                            <button type="button" onclick="showAddCategoryModal()" 
                                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <!-- Budget Selection -->
                        <div id="budgetSection" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Anggaran (Opsional)</label>
                            <select name="budget_id" id="budget_id" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base">
                                <option value="">Tidak ada anggaran</option>
                                <!-- Budget options will be populated by JavaScript -->
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih anggaran untuk mengaitkan transaksi ini</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="number" name="amount" id="amount" min="0" step="1000" 
                                       class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm lg:text-base" 
                                       placeholder="0" required>
                            </div>
                            <span class="text-red-500 text-xs lg:text-sm hidden" id="amount_error"></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 lg:mt-8">
                        <button type="button" onclick="closeTransactionModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm lg:text-base w-full sm:w-auto order-2 sm:order-1">
                            Batal
                        </button>
                        <button type="submit" id="submitButton" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 text-sm lg:text-base w-full sm:w-auto order-1 sm:order-2">
                            <span id="submitButtonText">Simpan Transaksi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kategori (Responsif) -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden flex items-center justify-center p-2 lg:p-4">
        <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto mx-2 lg:mx-0">
            <div class="p-4 lg:p-6">
                <div class="flex items-center justify-between mb-4 lg:mb-6">
                    <h3 class="text-lg lg:text-xl font-bold text-gray-800">Tambah Kategori Baru</h3>
                    <button onclick="closeCategoryModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                            <input type="text" name="category_name" id="category_name" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm lg:text-base" 
                                   placeholder="Contoh: Gaji, Makan, Transportasi" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kategori *</label>
                            <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="income" class="mr-2" checked>
                                    <span class="flex items-center text-green-600 text-sm lg:text-base">
                                        <i class="fas fa-arrow-down mr-1"></i>
                                        Pemasukan
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="expense" class="mr-2">
                                    <span class="flex items-center text-red-600 text-sm lg:text-base">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Pengeluaran
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-6 lg:mt-8">
                        <button type="button" onclick="closeCategoryModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm lg:text-base w-full sm:w-auto order-2 sm:order-1">
                            Batal
                        </button>
                        <button type="submit" id="submitCategoryButton"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 text-sm lg:text-base w-full sm:w-auto order-1 sm:order-2">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
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

        // Filter panel toggle
        const filterButton = document.getElementById('filterButton');
        const filterPanel = document.getElementById('filterPanel');
        const resetFilterButton = document.getElementById('resetFilterButton');

        if (filterButton) {
            filterButton.addEventListener('click', () => {
                filterPanel.classList.toggle('hidden');
            });
        }

        if (resetFilterButton) {
            resetFilterButton.addEventListener('click', () => {
                document.getElementById('filterForm').reset();
                window.location.href = "{{ route('transactions.index') }}";
            });
        }

        // Variables
        let budgets = [];

        // Fetch budgets on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchBudgets();
        });

        // Fetch budgets from API
        async function fetchBudgets() {
            try {
                const response = await fetch('{{ route("budget.data") }}');
                if (response.ok) {
                    const data = await response.json();
                    budgets = data.budgets || [];
                }
            } catch (error) {
                console.error('Error fetching budgets:', error);
            }
        }

        // Update budget options based on selected category and transaction type
        function updateBudgetOptions() {
            const transactionType = document.querySelector('input[name="transaction_type"]:checked').value;
            const categorySelect = document.getElementById('category_id');
            const selectedCategoryId = categorySelect.value;
            const budgetSection = document.getElementById('budgetSection');
            const budgetSelect = document.getElementById('budget_id');
            
            // Clear existing options except the first one
            while (budgetSelect.options.length > 1) {
                budgetSelect.remove(1);
            }
            
            // Only show budget section for expense transactions
            if (transactionType === 'expense' && selectedCategoryId) {
                budgetSection.classList.remove('hidden');
                
                // Get selected category name
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const categoryName = selectedOption.text;
                
                // Filter budgets by category name (matching logic)
                const matchingBudgets = budgets.filter(budget => 
                    budget.category_name.toLowerCase().includes(categoryName.toLowerCase()) ||
                    categoryName.toLowerCase().includes(budget.category_name.toLowerCase())
                );
                
                // Add budget options
                matchingBudgets.forEach(budget => {
                    const option = document.createElement('option');
                    option.value = budget.id;
                    option.textContent = `${budget.category_name} (Rp ${budget.amount.toLocaleString('id-ID')})`;
                    option.dataset.remaining = budget.remaining;
                    budgetSelect.appendChild(option);
                });
                
                // If no matching budgets, show message
                if (matchingBudgets.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Tidak ada anggaran untuk kategori ini';
                    option.disabled = true;
                    budgetSelect.appendChild(option);
                }
            } else {
                budgetSection.classList.add('hidden');
                budgetSelect.value = '';
            }
        }

        // Transaction modal functions
        function showAddTransactionModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Transaksi Baru';
            document.getElementById('transactionForm').action = "{{ route('transactions.store') }}";
            document.getElementById('transaction_id').value = '';
            document.getElementById('_method').value = 'POST';
            document.getElementById('transaction_date').value = '{{ date('Y-m-d') }}';
            document.getElementById('description').value = '';
            document.getElementById('category_id').value = '';
            document.getElementById('budget_id').value = '';
            document.getElementById('amount').value = '';
            document.getElementById('type_income').checked = true;
            document.getElementById('submitButtonText').textContent = 'Simpan Transaksi';
            
            // Reset errors
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.classList.add('hidden');
            });
            
            // Reset budget section
            document.getElementById('budgetSection').classList.add('hidden');
            
            document.getElementById('transactionModal').classList.remove('hidden');
        }

        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
        }

        function editTransaction(id) {
            fetch(`/transactions/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Edit Transaksi';
                    document.getElementById('transactionForm').action = `/transactions/${id}`;
                    document.getElementById('transaction_id').value = id;
                    document.getElementById('_method').value = 'PUT';
                    document.getElementById('transaction_date').value = data.transaction_date;
                    document.getElementById('description').value = data.description;
                    document.getElementById('category_id').value = data.category_id;
                    document.getElementById('amount').value = data.amount;
                    
                    if (data.transaction_type === 'income') {
                        document.getElementById('type_income').checked = true;
                    } else {
                        document.getElementById('type_expense').checked = true;
                    }
                    
                    document.getElementById('submitButtonText').textContent = 'Perbarui Transaksi';
                    
                    // Trigger budget options update
                    setTimeout(() => {
                        updateBudgetOptions();
                        // Set selected budget if exists
                        if (data.budget_id) {
                            document.getElementById('budget_id').value = data.budget_id;
                            document.getElementById('budgetSection').classList.remove('hidden');
                        }
                    }, 100);
                    
                    document.getElementById('transactionModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data transaksi');
                });
        }

        // Category modal functions
        function showAddCategoryModal() {
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        // Filter categories by transaction type
        document.querySelectorAll('input[name="transaction_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateBudgetOptions();
            });
        });

        // Search functionality with debounce
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        }

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const description = this.getAttribute('data-description');
                
                if (confirm(`Apakah Anda yakin ingin menghapus transaksi "${description}"?`)) {
                    // Show loading state
                    const deleteBtn = this.querySelector('button[type="submit"]');
                    const originalHtml = deleteBtn.innerHTML;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    deleteBtn.disabled = true;
                    
                    this.submit();
                }
            });
        });

        // Auto show modal if there are errors from form submission
        @if($errors->any() && old('_token'))
            document.addEventListener('DOMContentLoaded', function() {
                showAddTransactionModal();
                
                // Fill form with old values
                @if(old('transaction_date'))
                    document.getElementById('transaction_date').value = '{{ old("transaction_date") }}';
                @endif
                
                @if(old('description'))
                    document.getElementById('description').value = '{{ old("description") }}';
                @endif
                
                @if(old('category_id'))
                    document.getElementById('category_id').value = '{{ old("category_id") }}';
                @endif
                
                @if(old('budget_id'))
                    setTimeout(() => {
                        document.getElementById('budget_id').value = '{{ old("budget_id") }}';
                        updateBudgetOptions();
                    }, 100);
                @endif
                
                @if(old('amount'))
                    document.getElementById('amount').value = '{{ old("amount") }}';
                @endif
                
                @if(old('transaction_type') == 'income')
                    document.getElementById('type_income').checked = true;
                @elseif(old('transaction_type') == 'expense')
                    document.getElementById('type_expense').checked = true;
                @endif
                
                // Update budget options
                setTimeout(updateBudgetOptions, 200);
            });
        @endif

        // Category form submission with loading state
        document.getElementById('categoryForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitCategoryButton');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitBtn.disabled = true;
        });

        // Transaction form submission with loading state
        document.getElementById('transactionForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitButton');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitBtn.disabled = true;
            
            // Allow form to submit normally
            return true;
        });
    </script>
</body>
</html>