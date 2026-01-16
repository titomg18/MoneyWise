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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        /* Custom scrollbar for modal */
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

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 mx-4 lg:mx-6 mt-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
        @endif

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
                        @php
                            $percentageChange = $totalTarget > 0 ? (($totalSaved - ($totalTarget * 0.7)) / ($totalTarget * 0.7)) * 100 : 0;
                            $percentageChange = round($percentageChange, 0);
                        @endphp
                        <span class="text-emerald-600 bg-emerald-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">
                            {{ $percentageChange > 0 ? '+' : '' }}{{ $percentageChange }}%
                        </span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Total Tabungan</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">Rp {{ number_format($totalSaved, 0, ',', '.') }}</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Dari Rp {{ number_format($totalTarget, 0, ',', '.') }} target</p>
                </div>

                <!-- Target Aktif -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bullseye text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-blue-600 bg-blue-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">{{ $activeSavings }} Aktif</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Target Aktif</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">{{ $savings->where('status', 'active')->count() }} Target</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Sedang berjalan</p>
                </div>

                <!-- Target Tercapai -->
                <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-violet-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-purple-600 bg-purple-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">{{ $completedSavings }} Selesai</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Target Tercapai</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">{{ $savings->where('status', 'completed')->count() }} Target</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Berhasil diselesaikan</p>
                </div>

                <!-- Rata-rata Waktu -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-4 lg:p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-3 lg:mb-4">
                        @php
                            $avgDays = 0;
                            $activeSavingsList = $savings->where('status', 'active');
                            $activeCount = $activeSavingsList->count();
                            if ($activeCount > 0) {
                                $totalDays = 0;
                                foreach ($activeSavingsList as $saving) {
                                    if ($saving->end_date) {
                                        $days = \Carbon\Carbon::parse($saving->end_date)->diffInDays(now());
                                        $totalDays += $days > 0 ? $days : 0;
                                    }
                                }
                                $avgDays = $totalDays > 0 ? round($totalDays / $activeCount) : 0;
                            }
                        @endphp
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-white text-lg lg:text-xl"></i>
                        </div>
                        <span class="text-amber-600 bg-amber-100 px-2 lg:px-3 py-1 rounded-full text-xs lg:text-sm font-medium">{{ $avgDays }} Hari</span>
                    </div>
                    <h3 class="text-gray-500 text-xs lg:text-sm font-medium mb-1">Rata-rata Waktu</h3>
                    <p class="text-lg lg:text-2xl font-bold text-gray-800">{{ $avgDays > 0 ? round($avgDays / 30, 1) : '0' }} Bulan</p>
                    <p class="text-gray-500 text-xs lg:text-sm mt-2">Per target aktif</p>
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
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                            <button class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 rounded-lg px-3 py-1 text-xs lg:text-sm" id="applyFilter">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <div id="targetList" class="space-y-4 lg:space-y-5">
                        @if($savings->count() > 0)
                            @foreach($savings as $saving)
                                @php
                                    $progressPercentage = $saving->target_amount > 0 ? round(($saving->saved_amount / $saving->target_amount) * 100) : 0;
                                    
                                    // Tentukan status dan warna
                                    if ($saving->status == 'completed') {
                                        $statusColor = 'emerald';
                                        $statusText = '<i class="fas fa-check-circle mr-1"></i> Selesai';
                                    } elseif ($saving->status == 'cancelled') {
                                        $statusColor = 'gray';
                                        $statusText = 'Dibatalkan';
                                    } else {
                                        if ($saving->end_date) {
                                            $daysRemaining = \Carbon\Carbon::parse($saving->end_date)->diffInDays(now(), false);
                                            
                                            if ($daysRemaining < 0) {
                                                $statusColor = 'red';
                                                $statusText = '<i class="fas fa-exclamation-triangle mr-1"></i> Terlambat';
                                            } elseif ($progressPercentage >= 70 && $daysRemaining > 7) {
                                                $statusColor = 'emerald';
                                                $statusText = 'On Track';
                                            } elseif ($progressPercentage < 50 && $daysRemaining < 15) {
                                                $statusColor = 'amber';
                                                $statusText = 'Perlu percepatan';
                                            } else {
                                                $statusColor = 'blue';
                                                $statusText = 'Aktif';
                                            }
                                        } else {
                                            $statusColor = 'blue';
                                            $statusText = 'Aktif';
                                        }
                                    }
                                    
                                    // Map icon berdasarkan kategori
                                    $iconMap = [
                                        'transportasi' => 'fa-car',
                                        'rumah' => 'fa-home',
                                        'pendidikan' => 'fa-graduation-cap',
                                        'liburan' => 'fa-umbrella-beach',
                                        'elektronik' => 'fa-laptop',
                                        'kesehatan' => 'fa-heart',
                                        'lainnya' => 'fa-bullseye'
                                    ];
                                    
                                    $icon = $iconMap[$saving->target_category ?? 'lainnya'] ?? 'fa-bullseye';
                                    $iconClass = $saving->icon ?? $icon;
                                    
                                    // Format tanggal
                                    $startDate = \Carbon\Carbon::parse($saving->start_date)->format('d M Y');
                                    $endDate = $saving->end_date ? \Carbon\Carbon::parse($saving->end_date)->format('d M Y') : '-';
                                    $completedDate = $saving->status == 'completed' ? \Carbon\Carbon::parse($saving->updated_at)->format('d M Y') : null;
                                @endphp
                                
                                <div class="target-item border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow" 
                                     data-category="{{ $saving->target_category ?? 'lainnya' }}" 
                                     data-status="{{ $saving->status }}">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                        <div class="flex items-center mb-2 sm:mb-0">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3" style="background-color: {{ ($saving->color ?: '#3B82F6') }}20">
                                                <i class="fas {{ $iconClass }} text-{{ $statusColor }}-500"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ $saving->target_name }}</h4>
                                                <p class="text-xs text-gray-500">Target: Rp {{ number_format($saving->target_amount, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-800">Rp {{ number_format($saving->saved_amount, 0, ',', '.') }}</p>
                                            <p class="text-xs {{ $saving->status == 'completed' ? 'text-emerald-500' : 'text-gray-500' }}">
                                                {{ $progressPercentage }}% tercapai
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        @if($saving->end_date && $saving->status == 'active')
                                            @php
                                                $daysRemaining = \Carbon\Carbon::parse($saving->end_date)->diffInDays(now(), false);
                                            @endphp
                                            <span class="text-gray-600">
                                                @if($daysRemaining >= 0)
                                                    Tersisa: {{ $daysRemaining }} hari
                                                @else
                                                    Terlambat: {{ abs($daysRemaining) }} hari
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-600">
                                                @if($saving->status == 'completed')
                                                    Selesai!
                                                @elseif($saving->status == 'cancelled')
                                                    Dibatalkan
                                                @else
                                                    Tanpa deadline
                                                @endif
                                            </span>
                                        @endif
                                        <span class="text-{{ $statusColor }}-600 font-medium">{!! $statusText !!}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-{{ $statusColor }}-500" style="width: {{ min($progressPercentage, 100) }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                                        <span>Mulai: {{ $startDate }}</span>
                                        @if($saving->end_date)
                                            <span class="{{ $statusColor == 'red' ? 'text-red-500' : '' }}">
                                                @if($saving->status == 'completed')
                                                    Selesai: {{ $completedDate }}
                                                @else
                                                    Target: {{ $endDate }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    <!-- Tombol Aksi -->
                                    <div class="flex justify-end space-x-3 mt-3 pt-3 border-t border-gray-100">
                                        @if($saving->status == 'active')
                                            <button class="add-savings-btn text-emerald-600 hover:text-emerald-800 text-sm flex items-center"
                                                    data-id="{{ $saving->id }}"
                                                    data-name="{{ $saving->target_name }}"
                                                    data-saved="{{ $saving->saved_amount }}"
                                                    data-target="{{ $saving->target_amount }}">
                                                <i class="fas fa-plus-circle mr-1"></i> Tambah
                                            </button>
                                        @endif
                                        <button class="edit-target-btn text-blue-600 hover:text-blue-800 text-sm flex items-center"
                                                data-id="{{ $saving->id }}"
                                                data-name="{{ $saving->target_name }}"
                                                data-target="{{ $saving->target_amount }}"
                                                data-saved="{{ $saving->saved_amount }}"
                                                data-start="{{ $saving->start_date }}"
                                                data-end="{{ $saving->end_date }}"
                                                data-category="{{ $saving->target_category }}"
                                                data-color="{{ $saving->color }}"
                                                data-icon="{{ $saving->icon }}"
                                                data-description="{{ $saving->description }}"
                                                data-status="{{ $saving->status }}">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <form action="{{ route('tabungan.destroy', $saving) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm flex items-center"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus target ini?')">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
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
                        @endif
                    </div>
                    
                    @if($savings->count() > 0)
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button id="tambahTargetBtnFooter" class="text-emerald-600 hover:text-emerald-800 font-medium text-sm flex items-center">
                                <i class="fas fa-plus-circle mr-2"></i> Tambah Target Tabungan Baru
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Grafik Progress dan Tips -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <h3 class="text-base lg:text-lg font-bold text-gray-800 mb-4 lg:mb-6">Progress Tabungan</h3>
                    
                    <!-- Grafik Progress -->
                    <div class="flex flex-col items-center justify-center mb-6">
                        @php
                            $overallProgress = 0;
                            if ($savings->count() > 0) {
                                $totalProgress = 0;
                                foreach ($savings as $saving) {
                                    if ($saving->target_amount > 0) {
                                        $progress = ($saving->saved_amount / $saving->target_amount) * 100;
                                        $totalProgress += min($progress, 100);
                                    }
                                }
                                $overallProgress = round($totalProgress / $savings->count(), 0);
                            }
                        @endphp
                        <div class="relative w-48 h-48">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <!-- Background circle -->
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#E5E7EB" stroke-width="10"/>
                                <!-- Progress circle -->
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#10B981" stroke-width="10" 
                                        stroke-dasharray="283" stroke-dashoffset="{{ 283 - ($overallProgress / 100 * 283) }}" 
                                        class="progress-ring" stroke-linecap="round"/>
                                <!-- Inner text -->
                                <text x="50" y="50" text-anchor="middle" dy=".3em" class="text-2xl font-bold fill-gray-800">{{ $overallProgress }}%</text>
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
                        @php
                            $totalCount = $savings->count();
                            $completedOnTime = $savings->where('status', 'completed')->count();
                            $activeCount = $savings->where('status', 'active')->count();
                            $cancelledCount = $savings->where('status', 'cancelled')->count();
                            
                            $completedPercentage = $totalCount > 0 ? round(($completedOnTime / $totalCount) * 100) : 0;
                            $activePercentage = $totalCount > 0 ? round(($activeCount / $totalCount) * 100) : 0;
                            $cancelledPercentage = $totalCount > 0 ? round(($cancelledCount / $totalCount) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Tercapai</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">{{ $completedPercentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $completedPercentage }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Aktif</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">{{ $activePercentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $activePercentage }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs lg:text-sm font-medium text-gray-700">Target Dibatalkan</span>
                                <span class="text-xs lg:text-sm font-medium text-gray-700">{{ $cancelledPercentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-400 h-2 rounded-full" style="width: {{ $cancelledPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Analisis berdasarkan {{ $totalCount }} target tabungan</p>
                    </div>
                </div>

                <!-- Pencapaian Terbaru -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 lg:p-6">
                    <div class="flex items-center justify-between mb-4 lg:mb-6">
                        <h3 class="text-base lg:text-lg font-bold text-gray-800">Pencapaian Terbaru</h3>
                        <span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">2 Minggu Terakhir</span>
                    </div>
                    
                    <div class="space-y-4">
                        @php
                            $recentSavings = $savings->sortByDesc('updated_at')->take(4);
                        @endphp
                        
                        @foreach($recentSavings as $recent)
                            @php
                                if ($recent->status == 'completed') {
                                    $bgColor = 'bg-emerald-50';
                                    $iconColor = 'emerald';
                                    $icon = 'fa-trophy';
                                    $message = "Target '{$recent->target_name}' tercapai!";
                                } elseif ($recent->status == 'active' && $recent->progress_percentage >= 70) {
                                    $bgColor = 'bg-blue-50';
                                    $iconColor = 'blue';
                                    $icon = 'fa-chart-line';
                                    $message = "Progress '{$recent->target_name}' mencapai " . round($recent->progress_percentage) . "%";
                                } elseif ($recent->status == 'active' && $recent->days_remaining < 0) {
                                    $bgColor = 'bg-amber-50';
                                    $iconColor = 'amber';
                                    $icon = 'fa-exclamation-triangle';
                                    $message = "Target '{$recent->target_name}' terlambat";
                                } else {
                                    $bgColor = 'bg-purple-50';
                                    $iconColor = 'purple';
                                    $icon = 'fa-plus-circle';
                                    $message = "Target '{$recent->target_name}' aktif";
                                }
                            @endphp
                            
                            <div class="flex items-center p-3 {{ $bgColor }} rounded-xl">
                                <div class="w-10 h-10 bg-{{ $iconColor }}-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas {{ $icon }} text-{{ $iconColor }}-500"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $message }}</p>
                                    <p class="text-xs text-gray-600">
                                        Rp {{ number_format($recent->saved_amount, 0, ',', '.') }} dari Rp {{ number_format($recent->target_amount, 0, ',', '.') }}
                                        @if($recent->updated_at)
                                            • {{ \Carbon\Carbon::parse($recent->updated_at)->format('d M Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($savings->count() == 0)
                            <div class="text-center py-4">
                                <p class="text-gray-500 text-sm">Belum ada pencapaian terbaru</p>
                            </div>
                        @endif
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
                        <option>{{ \Carbon\Carbon::now()->format('F Y') }}</option>
                        <option>{{ \Carbon\Carbon::now()->subMonth()->format('F Y') }}</option>
                        <option>{{ \Carbon\Carbon::now()->subMonths(2)->format('F Y') }}</option>
                        <option>{{ \Carbon\Carbon::now()->subMonths(3)->format('F Y') }}</option>
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
    <div id="modalTambahTarget" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md my-8">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Buat Target Tabungan Baru</h3>
                    <button id="tutupModalTarget" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-content max-h-[calc(100vh-200px)] overflow-y-auto pr-2">
                    <form id="formTambahTarget" action="{{ route('tabungan.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="target_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Target *</label>
                                <input type="text" id="target_name" name="target_name" 
                                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                       placeholder="Contoh: DP Rumah, Liburan, Laptop Baru" 
                                       value="{{ old('target_name') }}" required>
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
                                           placeholder="0" min="0" step="10000" 
                                           value="{{ old('target_amount') }}" required>
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
                                           placeholder="0" min="0" step="10000"
                                           value="{{ old('saved_amount', 0) }}">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Jumlah yang sudah Anda tabung (jika ada)</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                                    <input type="date" id="start_date" name="start_date" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                           value="{{ old('start_date', date('Y-m-d')) }}" required>
                                </div>
                                
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Target</label>
                                    <input type="date" id="end_date" name="end_date" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                           value="{{ old('end_date') }}">
                                </div>
                            </div>
                            
                            <div>
                                <label for="target_category" class="block text-sm font-medium text-gray-700 mb-1">Kategori Target</label>
                                <select id="target_category" name="target_category" 
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Pilih Kategori</option>
                                    <option value="transportasi" {{ old('target_category') == 'transportasi' ? 'selected' : '' }}>Transportasi</option>
                                    <option value="rumah" {{ old('target_category') == 'rumah' ? 'selected' : '' }}>Rumah & Properti</option>
                                    <option value="pendidikan" {{ old('target_category') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                    <option value="liburan" {{ old('target_category') == 'liburan' ? 'selected' : '' }}>Liburan & Hiburan</option>
                                    <option value="elektronik" {{ old('target_category') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="kesehatan" {{ old('target_category') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="lainnya" {{ old('target_category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih kategori untuk target tabungan</p>
                            </div>
                            
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Ikon</label>
                                <select id="icon" name="icon" 
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="piggy-bank" {{ old('icon') == 'piggy-bank' ? 'selected' : '' }}>Celengan</option>
                                    <option value="car" {{ old('icon') == 'car' ? 'selected' : '' }}>Mobil</option>
                                    <option value="home" {{ old('icon') == 'home' ? 'selected' : '' }}>Rumah</option>
                                    <option value="graduation-cap" {{ old('icon') == 'graduation-cap' ? 'selected' : '' }}>Wisuda</option>
                                    <option value="umbrella-beach" {{ old('icon') == 'umbrella-beach' ? 'selected' : '' }}>Liburan</option>
                                    <option value="laptop" {{ old('icon') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                                    <option value="heart" {{ old('icon') == 'heart' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="bullseye" {{ old('icon') == 'bullseye' ? 'selected' : '' }}>Target</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih ikon untuk target</p>
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                                <textarea id="description" name="description" 
                                          class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                          rows="3" placeholder="Tambahkan catatan atau motivasi untuk target ini">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Deskripsi atau catatan tambahan</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Warna Target</label>
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
                                        <input type="radio" id="{{ $color['id'] }}" name="color" value="{{ $color['value'] }}" 
                                               class="hidden" {{ old('color', '#3B82F6') == $color['value'] ? 'checked' : '' }}>
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
    </div>

    <!-- Modal Edit Target -->
    <div id="modalEditTarget" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md my-8">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Edit Target Tabungan</h3>
                    <button id="tutupModalEdit" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-content max-h-[calc(100vh-200px)] overflow-y-auto pr-2">
                    <form id="formEditTarget" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        
                        <div class="space-y-4">
                            <div>
                                <label for="edit_target_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Target *</label>
                                <input type="text" id="edit_target_name" name="target_name" 
                                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                       placeholder="Contoh: DP Rumah, Liburan, Laptop Baru" required>
                            </div>
                            
                            <div>
                                <label for="edit_target_amount" class="block text-sm font-medium text-gray-700 mb-1">Target Jumlah *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">Rp</span>
                                    </div>
                                    <input type="number" id="edit_target_amount" name="target_amount" 
                                           class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                           placeholder="0" min="0" step="10000" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="edit_saved_amount" class="block text-sm font-medium text-gray-700 mb-1">Sudah Tertabung</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">Rp</span>
                                    </div>
                                    <input type="number" id="edit_saved_amount" name="saved_amount" 
                                           class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                           placeholder="0" min="0" step="10000">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                                    <input type="date" id="edit_start_date" name="start_date" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                </div>
                                
                                <div>
                                    <label for="edit_end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Target</label>
                                    <input type="date" id="edit_end_date" name="end_date" 
                                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="edit_target_category" class="block text-sm font-medium text-gray-700 mb-1">Kategori Target</label>
                                <select id="edit_target_category" name="target_category" 
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Pilih Kategori</option>
                                    <option value="transportasi">Transportasi</option>
                                    <option value="rumah">Rumah & Properti</option>
                                    <option value="pendidikan">Pendidikan</option>
                                    <option value="liburan">Liburan & Hiburan</option>
                                    <option value="elektronik">Elektronik</option>
                                    <option value="kesehatan">Kesehatan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="edit_status" name="status" 
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="active">Aktif</option>
                                    <option value="completed">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <textarea id="edit_description" name="description" 
                                          class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                          rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" id="batalEditTarget" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl hover:from-blue-600 hover:to-cyan-700 transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                <span>Update Target</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tabungan -->
    <div id="modalTambahTabungan" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800" id="modalTabunganTitle">Tambah Tabungan</h3>
                    <button id="tutupModalTabungan" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="formTambahTabungan" method="POST">
                    @csrf
                    <input type="hidden" id="saving_id" name="saving_id">
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Untuk target: <span id="targetName" class="font-semibold"></span></p>
                            <p class="text-sm text-gray-600">Terkumpul: <span id="currentSaved" class="font-semibold"></span> dari <span id="targetAmount" class="font-semibold"></span></p>
                            <p class="text-sm text-gray-600 mt-1">Sisa: <span id="remainingAmount" class="font-semibold text-emerald-600"></span></p>
                        </div>
                        
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tambahan *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="amount" name="amount" 
                                       class="w-full border border-gray-300 rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" 
                                       placeholder="0" min="1" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan jumlah yang ingin Anda tambahkan</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="batalTambahTabungan" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i>
                            <span>Tambah Tabungan</span>
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
        // FUNGSI UTILITY
        // ============================================

        // Format angka ke Rupiah
        function formatRupiah(amount) {
            if (amount === null || amount === undefined) return 'Rp 0';
            return 'Rp ' + parseInt(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // ============================================
        // ELEMENT REFERENCES
        // ============================================
        const modalTambahTarget = document.getElementById('modalTambahTarget');
        const modalEditTarget = document.getElementById('modalEditTarget');
        const modalTambahTabungan = document.getElementById('modalTambahTabungan');
        const mobileMenu = document.getElementById('mobileMenu');
        const searchInput = document.getElementById('searchTabungan');
        const filterStatus = document.getElementById('filterStatus');
        const applyFilter = document.getElementById('applyFilter');

        // ============================================
        // MOBILE MENU HANDLING
        // ============================================
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

        // ============================================
        // MODAL TAMBAH TARGET HANDLING
        // ============================================
        const semuaTombolTambah = [
            'tambahTargetBtn',
            'tambahTargetBtnFooter',
            'tambahTargetBtnEmpty'
        ];
        
        semuaTombolTambah.forEach(buttonId => {
            document.getElementById(buttonId)?.addEventListener('click', () => {
                modalTambahTarget.classList.remove('hidden');
                // Prevent body scrolling when modal is open
                document.body.style.overflow = 'hidden';
            });
        });

        // Modal tutup dan batal
        document.getElementById('tutupModalTarget')?.addEventListener('click', () => {
            modalTambahTarget.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        document.getElementById('batalTambahTarget')?.addEventListener('click', () => {
            modalTambahTarget.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        // Tutup modal saat klik di luar
        modalTambahTarget?.addEventListener('click', (e) => {
            if (e.target.id === 'modalTambahTarget') {
                modalTambahTarget.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Prevent modal content click from closing modal
        modalTambahTarget?.querySelector('.bg-white')?.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // ============================================
        // MODAL EDIT TARGET HANDLING
        // ============================================
        // Event delegation untuk edit button
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-target-btn')) {
                const button = e.target.closest('.edit-target-btn');
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const targetAmount = button.getAttribute('data-target');
                const savedAmount = button.getAttribute('data-saved');
                const startDate = button.getAttribute('data-start');
                const endDate = button.getAttribute('data-end');
                const category = button.getAttribute('data-category');
                const status = button.getAttribute('data-status');
                const description = button.getAttribute('data-description') || '';
                
                // Set form values
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_target_name').value = name;
                document.getElementById('edit_target_amount').value = targetAmount;
                document.getElementById('edit_saved_amount').value = savedAmount;
                document.getElementById('edit_start_date').value = startDate;
                document.getElementById('edit_end_date').value = endDate || '';
                document.getElementById('edit_target_category').value = category || '';
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_description').value = description;
                
                // Set form action
                document.getElementById('formEditTarget').action = `/tabungan/${id}`;
                
                // Show modal
                modalEditTarget.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });

        document.getElementById('tutupModalEdit')?.addEventListener('click', () => {
            modalEditTarget.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        document.getElementById('batalEditTarget')?.addEventListener('click', () => {
            modalEditTarget.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        modalEditTarget?.addEventListener('click', (e) => {
            if (e.target.id === 'modalEditTarget') {
                modalEditTarget.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Prevent modal content click from closing modal
        modalEditTarget?.querySelector('.bg-white')?.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // ============================================
        // MODAL TAMBAH TABUNGAN HANDLING
        // ============================================
        // Event delegation untuk add savings button
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-savings-btn')) {
                const button = e.target.closest('.add-savings-btn');
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const saved = parseInt(button.getAttribute('data-saved'));
                const target = parseInt(button.getAttribute('data-target'));
                const remaining = target - saved;
                
                // Set modal values
                document.getElementById('saving_id').value = id;
                document.getElementById('targetName').textContent = name;
                document.getElementById('currentSaved').textContent = formatRupiah(saved);
                document.getElementById('targetAmount').textContent = formatRupiah(target);
                document.getElementById('remainingAmount').textContent = formatRupiah(remaining);
                
                // Set form action
                document.getElementById('formTambahTabungan').action = `/tabungan/${id}/add-savings`;
                
                // Show modal
                modalTambahTabungan.classList.remove('hidden');
            }
        });

        document.getElementById('tutupModalTabungan')?.addEventListener('click', () => {
            modalTambahTabungan.classList.add('hidden');
        });

        document.getElementById('batalTambahTabungan')?.addEventListener('click', () => {
            modalTambahTabungan.classList.add('hidden');
        });

        modalTambahTabungan?.addEventListener('click', (e) => {
            if (e.target.id === 'modalTambahTabungan') {
                modalTambahTabungan.classList.add('hidden');
            }
        });

        // Prevent modal content click from closing modal
        modalTambahTabungan?.querySelector('.bg-white')?.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // ============================================
        // SEARCH AND FILTER FUNCTIONALITY
        // ============================================
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

        if (applyFilter) {
            applyFilter.addEventListener('click', function() {
                const filterValue = filterStatus.value;
                const targetItems = document.querySelectorAll('.target-item');
                
                targetItems.forEach(item => {
                    const status = item.getAttribute('data-status');
                    
                    if (filterValue === 'all' || status === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // ============================================
        // FORM SUBMISSION LOADING STATES
        // ============================================
        // Form Tambah Target loading state
        const formTambahTarget = document.getElementById('formTambahTarget');
        if (formTambahTarget) {
            formTambahTarget.addEventListener('submit', function(e) {
                const submitButton = document.getElementById('submitTargetButton');
                if (submitButton) {
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    submitButton.disabled = true;
                }
            });
        }

        // Form Edit Target loading state
        const formEditTarget = document.getElementById('formEditTarget');
        if (formEditTarget) {
            formEditTarget.addEventListener('submit', function(e) {
                const submitButton = formEditTarget.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memperbarui...';
                    submitButton.disabled = true;
                }
            });
        }

        // Form Tambah Tabungan loading state
        const formTambahTabungan = document.getElementById('formTambahTabungan');
        if (formTambahTabungan) {
            formTambahTabungan.addEventListener('submit', function(e) {
                const submitButton = formTambahTabungan.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menambahkan...';
                    submitButton.disabled = true;
                }
            });
        }

        // ============================================
        // AUTO-CALCULATE DATE FOR NEW TARGET
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates for new target form
            const today = new Date();
            const threeMonthsLater = new Date();
            threeMonthsLater.setMonth(today.getMonth() + 3);
            
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            
            if (startDateInput && !startDateInput.value) {
                startDateInput.value = today.toISOString().split('T')[0];
            }
            
            if (endDateInput && !endDateInput.value) {
                endDateInput.value = threeMonthsLater.toISOString().split('T')[0];
            }
            
            // Animate progress bars
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

        // ============================================
        // VALIDATION FOR ADD SAVINGS AMOUNT
        // ============================================
        const amountInput = document.getElementById('amount');
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                const amount = parseInt(this.value) || 0;
                const remainingText = document.getElementById('remainingAmount');
                if (remainingText) {
                    const remaining = parseInt(remainingText.textContent.replace(/[^0-9]/g, '')) || 0;
                    if (amount > remaining) {
                        this.setCustomValidity('Jumlah tidak boleh melebihi sisa target');
                    } else {
                        this.setCustomValidity('');
                    }
                }
            });
        }

        // ============================================
        // HANDLE ESC KEY TO CLOSE MODALS
        // ============================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!modalTambahTarget.classList.contains('hidden')) {
                    modalTambahTarget.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
                if (!modalEditTarget.classList.contains('hidden')) {
                    modalEditTarget.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
                if (!modalTambahTabungan.classList.contains('hidden')) {
                    modalTambahTabungan.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>