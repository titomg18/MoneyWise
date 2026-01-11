<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MoneyWise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
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
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        .btn-gradient:hover {
            background: linear-gradient(90deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body class="py-8 md:py-12">
    <!-- Kartu Login -->
    <div class="card-glass rounded-3xl p-8 w-full max-w-md mx-4 my-auto">
        <!-- Logo kecil di dalam card -->
        <div class="flex items-center justify-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg transform hover:rotate-12 transition duration-500">
                <i class="fas fa-chart-line text-3xl bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent"></i>
            </div>
            <h1 class="font-montserrat text-3xl font-black bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent ml-4">
                MoneyWise
            </h1>
        </div>
        
        <h2 class="text-2xl font-bold text-white mb-3">Yuk, Masuk! 👋</h2>
        <p class="text-white/80 mb-8">Isi data kamu buat akses dashboard keren</p>
        
        <!-- Pesan Error (contoh) -->
        <div id="errorMessage" class="hidden mb-6 p-4 bg-red-500/20 border border-red-500 rounded-2xl">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-300 mr-3 text-xl"></i>
                <p class="text-white font-medium" id="errorText">Email atau password salah</p>
            </div>
        </div>

        <form id="loginForm" method="POST" action="/login">
            <?php echo csrf_field(); ?>
            <!-- Email -->
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
                           placeholder="Masukkan password" required>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <i class="fas fa-eye text-white/50 hover:text-white text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Opsi Ingat Saya -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="remember" class="ml-3 text-white">Ingat saya</label>
                </div>
                <a href="#" class="text-white hover:text-purple-300 font-medium transition-colors">Lupa password?</a>
            </div>

            <!-- Tombol Login -->
            <button type="submit" 
                    class="w-full btn-gradient text-white font-bold py-4 px-4 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 mb-6">
                <i class="fas fa-sign-in-alt mr-3"></i>Masuk Sekarang
            </button>

            <!-- Pembatas -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-white/30"></div>
                <div class="mx-4 text-white/60 text-sm">atau lanjutkan dengan</div>
                <div class="flex-grow border-t border-white/30"></div>
            </div>

            <!-- Login dengan Google -->
            <button type="button" 
                    class="w-full bg-white text-gray-800 font-bold py-4 px-4 rounded-2xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-all duration-300 mb-6 flex items-center justify-center">
                <i class="fab fa-google text-red-500 mr-3 text-xl"></i>Google
            </button>
        </form>

        <!-- Link ke Register -->
        <div class="mt-8 pt-6 border-t border-white/20 text-center">
            <p class="text-white">Belum punya akun? 
                <a href="/register" class="text-white font-bold hover:text-purple-300 ml-1 transition-colors">
                    Daftar Gratis <i class="fas fa-arrow-right ml-1"></i>
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

        // Validasi form sederhana
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');
            
            // Sembunyikan pesan error terlebih dahulu
            errorMessage.classList.add('hidden');
            
            // Validasi sederhana
            if (!email || !password) {
                e.preventDefault();
                document.getElementById('errorText').textContent = 'Email dan password harus diisi!';
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
            
            // Jika semua valid, form akan dikirim
            return true;
        });

        // Simulasi login dengan Google
        document.querySelector('button[type="button"]:last-child').addEventListener('click', function() {
            alert('Fitur login dengan Google akan segera hadir! 🚀');
        });
    </script>
</body>
</html>