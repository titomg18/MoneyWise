<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - MoneyWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #23a6d5, #23d5ab, #ee7752, #e73c7e);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .btn-gradient {
            background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
        }
        .btn-gradient:hover {
            background: linear-gradient(90deg, #f5576c 0%, #f093fb 100%);
        }
    </style>
</head>
<body class="py-8 md:py-12">
    <!-- Kartu Registrasi -->
    <div class="card-glass rounded-3xl p-8 w-full max-w-md mx-4 my-auto">
        <!-- Logo kecil di dalam card -->
        <div class="flex items-center justify-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg transform hover:rotate-12 transition duration-500">
                <i class="fas fa-piggy-bank text-3xl bg-gradient-to-r from-pink-600 to-orange-600 bg-clip-text text-transparent"></i>
            </div>
            <h1 class="font-montserrat text-3xl font-black bg-gradient-to-r from-pink-600 to-orange-600 bg-clip-text text-transparent ml-4">
                MoneyWise
            </h1>
        </div>
        
        <h2 class="text-2xl font-bold text-white mb-2">Daftar Yuk! 🚀</h2>
        <p class="text-white/80 mb-8">Bikin akun buat manage keuangan kamu</p>

        <!-- Pesan Error (contoh) -->
        <div id="errorMessage" class="hidden mb-6 p-4 bg-red-500/20 border border-red-500 rounded-2xl">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-300 mr-3 text-xl"></i>
                <p class="text-white font-medium" id="errorText">Ada kesalahan dalam pengisian form</p>
            </div>
        </div>

        <form id="registerForm" method="POST" action="/register">
            @csrf
            
            <!-- Nama -->
            <div class="mb-6">
                <label class="block text-white text-sm font-medium mb-3" for="name">
                    <i class="fas fa-user mr-2"></i>Nama Lengkap
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-white/50 text-xl"></i>
                    </div>
                    <input type="text" name="name" id="name" 
                           class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 text-white placeholder-white/50 rounded-2xl focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300"
                           placeholder="Nama Kamu" required>
                </div>
            </div>

            <!-- Email (dengan icon profile) -->
            <div class="mb-6">
                <label class="block text-white text-sm font-medium mb-3" for="email">
                    <i class="fas fa-envelope mr-2"></i>Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user-circle text-white/50 text-xl"></i>
                    </div>
                    <input type="email" name="email" id="email" 
                           class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 text-white placeholder-white/50 rounded-2xl focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300"
                           placeholder="nama@email.com" required>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-white text-sm font-medium mb-3" for="password">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-key text-white/50 text-xl"></i>
                    </div>
                    <input type="password" name="password" id="password" 
                           class="w-full pl-12 pr-12 py-4 bg-white/10 border border-white/20 text-white placeholder-white/50 rounded-2xl focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300"
                           placeholder="Minimal 8 karakter" required>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <i class="fas fa-eye text-white/50 hover:text-white text-xl"></i>
                    </button>
                </div>
                <div class="mt-3 text-xs text-white/60 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Password harus minimal 8 karakter
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-8">
                <label class="block text-white text-sm font-medium mb-3" for="password_confirmation">
                    <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-key text-white/50 text-xl"></i>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full pl-12 pr-12 py-4 bg-white/10 border border-white/20 text-white placeholder-white/50 rounded-2xl focus:ring-2 focus:ring-white focus:border-transparent transition-all duration-300"
                           placeholder="Ulangi password" required>
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <i class="fas fa-eye text-white/50 hover:text-white text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Syarat dan Ketentuan -->
            <div class="mb-8">
                <div class="flex items-start">
                    <input type="checkbox" id="terms" name="terms" class="h-5 w-5 text-pink-600 focus:ring-pink-500 border-gray-300 rounded mt-1" required>
                    <label for="terms" class="ml-3 text-white text-sm">
                        Saya menyetujui 
                        <a href="#" class="text-white font-bold hover:text-pink-300">Syarat & Ketentuan</a> 
                        dan 
                        <a href="#" class="text-white font-bold hover:text-pink-300">Kebijakan Privasi</a>
                        MoneyWise
                    </label>
                </div>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit" 
                    class="w-full btn-gradient text-white font-bold py-4 px-4 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 mb-6">
                <i class="fas fa-user-plus mr-3"></i>Daftar Sekarang
            </button>

            <!-- Pembatas -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-white/30"></div>
                <div class="mx-4 text-white/60 text-sm">atau daftar dengan</div>
                <div class="flex-grow border-t border-white/30"></div>
            </div>

            <!-- Daftar dengan Google -->
            <button type="button" 
                    class="w-full bg-white text-gray-800 font-bold py-4 px-4 rounded-2xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-300 mb-6 flex items-center justify-center">
                <i class="fab fa-google text-red-500 mr-3 text-xl"></i>Google
            </button>
        </form>

        <!-- Keuntungan Bergabung -->
        <div class="mt-6 pt-6 border-t border-white/20">
            <h3 class="font-bold text-white mb-4 flex items-center">
                <i class="fas fa-gift text-yellow-300 mr-3"></i>Keuntungan Bergabung
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-300 mr-3 text-sm"></i>
                    <span class="text-white text-sm">Analisis keuangan</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-300 mr-3 text-sm"></i>
                    <span class="text-white text-sm">Visualisasi data</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-300 mr-3 text-sm"></i>
                    <span class="text-white text-sm">Saran budgeting</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-300 mr-3 text-sm"></i>
                    <span class="text-white text-sm">Target tabungan</span>
                </div>
            </div>
        </div>

        <!-- Link ke Login -->
        <div class="mt-8 pt-6 border-t border-white/20 text-center">
            <p class="text-white">Sudah punya akun? 
                <a href="/login" class="text-white font-bold hover:text-pink-300 ml-1 transition-colors">
                    Masuk di sini <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Validasi form register
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            const errorMessage = document.getElementById('errorMessage');
            
            // Sembunyikan pesan error terlebih dahulu
            errorMessage.classList.add('hidden');
            
            // Validasi nama
            if (!name.trim()) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Nama lengkap harus diisi!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            // Validasi email
            if (!email) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Email harus diisi!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            // Validasi format email sederhana
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Format email tidak valid!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            // Validasi password
            if (!password) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Password harus diisi!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Password minimal 8 karakter!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            // Validasi konfirmasi password
            if (password !== confirmPassword) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Konfirmasi password tidak cocok!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            // Validasi syarat dan ketentuan
            if (!terms) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Anda harus menyetujui Syarat & Ketentuan!';
                errorMessage.classList.remove('hidden');
                return false;
            }
            
            // Jika semua valid, form akan dikirim
            return true;
        });

        // Simulasi daftar dengan Google
        document.querySelector('button[type="button"]:last-child').addEventListener('click', function() {
            alert('Fitur daftar dengan Google akan segera hadir! 🚀');
        });
    </script>
</body>
</html>