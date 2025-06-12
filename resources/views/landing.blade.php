<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FishBite Resto</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree antialiased bg-white dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="flex items-center space-x-2">
                            <i class="fas fa-fish text-blue-500 text-2xl"></i>
                            <h1 class="text-2xl font-bold text-red-500 dark:text-red-400">FishBite Resto</h1>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="hidden sm:flex sm:items-center sm:space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="px-4 py-2 text-sm font-medium text-navy-900 dark:text-gray-200 hover:text-red-500 dark:hover:text-red-400 transition duration-300">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-4 py-2 text-sm font-medium text-navy-900 dark:text-gray-200 hover:text-red-500 dark:hover:text-red-400 transition duration-300">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="ml-4 px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-md transition duration-300">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-white text-navy-900 py-24 sm:py-32">
            <div class="max-w-5xl mx-auto text-center px-6 lg:px-8">
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 tracking-tight leading-tight text-red-500 dark:text-red-400">
                    Selamat Datang di FishBite Resto</h1>
                <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">Nikmati kelezatan
                    hidangan laut segar dengan cita rasa autentik, disajikan dengan sentuhan modern.</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <button id="viewMenuBtn"
                        class="w-full sm:w-auto bg-red-500 text-white px-8 py-3 rounded-lg font-semibold shadow-lg hover:bg-red-600 transform hover:scale-105 transition-all duration-300 ease-in-out">
                        Lihat Menu
                    </button>
                    <a href="{{ route('login') }}" id="loginBtn"
                        class="w-full sm:w-auto border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white px-8 py-3 rounded-lg font-semibold transform hover:scale-105 transition-all duration-300 ease-in-out">
                        Login untuk Reservasi
                    </a>
                </div>
            </div>
        </section>

        <!-- Menu Section -->
        <section id="publicMenuSection" class="py-16 sm:py-24 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-center mb-12 text-red-500 dark:text-red-400">Menu Andalan
                    Kami</h2>

                <!-- Search & Filter -->
                <div class="flex flex-col md:flex-row gap-4 mb-12 max-w-3xl mx-auto">
                    <input type="text" id="searchMenu" placeholder="Cari menu favoritmu..."
                        class="flex-1 p-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white transition duration-300">
                    <select id="filterKategori"
                        class="p-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white transition duration-300">
                        <option value="">Semua Kategori</option>
                        <!-- Kategori akan ditambahkan melalui JS -->
                    </select>
                </div>

                <!-- Menu Grid -->
                <div id="menuGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Menu Item -->
                    <div
                        class="bg-white dark:bg-gray-700 rounded-xl p-6 shadow-lg hover:ring-2 hover:ring-red-500 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <img src="https://placehold.co/600x400/EF4444/FFFFFF?text=Ikan+Bakar" alt="Ikan Bakar"
                            class="rounded-lg mb-4 w-full h-48 object-cover">
                        <h3 class="text-xl font-semibold mb-2 text-navy-900 dark:text-white">Ikan Bakar Rica</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Disajikan dengan sambal khas yang pedas dan
                            nasi hangat pulen.</p>
                        <div class="text-lg font-bold text-red-500 dark:text-red-400">Rp 55.000</div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-700 rounded-xl p-6 shadow-lg hover:ring-2 hover:ring-red-500 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <img src="https://placehold.co/600x400/EF4444/FFFFFF?text=Sup+Ikan" alt="Sup Ikan"
                            class="rounded-lg mb-4 w-full h-48 object-cover">
                        <h3 class="text-xl font-semibold mb-2 text-navy-900 dark:text-white">Sup Ikan Gurame</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Kuah bening yang segar dengan rempah pilihan,
                            cocok untuk menghangatkan badan.</p>
                        <div class="text-lg font-bold text-red-500 dark:text-red-400">Rp 65.000</div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-700 rounded-xl p-6 shadow-lg hover:ring-2 hover:ring-red-500 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                        <img src="https://placehold.co/600x400/EF4444/FFFFFF?text=Ikan+Goreng" alt="Ikan Goreng"
                            class="rounded-lg mb-4 w-full h-48 object-cover">
                        <h3 class="text-xl font-semibold mb-2 text-navy-900 dark:text-white">Ikan Goreng Tepung</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Garing di luar, lembut di dalam, disajikan
                            dengan saus asam manis.</p>
                        <div class="text-lg font-bold text-red-500 dark:text-red-400">Rp 50.000</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-red-500 text-white border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex justify-center space-x-6 mb-4">
                    <a href="#" class="text-white hover:text-navy-900 transition duration-300"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white hover:text-navy-900 transition duration-300"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white hover:text-navy-900 transition duration-300"><i
                            class="fab fa-twitter"></i></a>
                </div>
                <p class="text-sm text-white">© {{ date('Y') }} FishBite Resto. All Rights Reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Custom Tailwind Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            900: '#1E3A8A',
                        },
                        red: {
                            400: '#F87171',
                            500: '#EF4444',
                            600: '#DC2626',
                        },
                    },
                }
            }
        }
    </script>
</body>

</html>
