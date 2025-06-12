<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>FishBite Resto</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-figtree antialiased bg-gradient-to-br from-white via-gray-100 to-gray-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white/90 dark:bg-gray-800/90 shadow backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <a href="/" class="flex items-center space-x-3">
                    <i class="fas fa-fish text-red-500 text-2xl"></i>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">FishBite Resto</h1>
                </a>
                <nav class="hidden sm:flex space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-gray-700 dark:text-gray-200 hover:text-red-500 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-md transition">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow transition">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>

            </div>
        </header>

        <!-- Hero -->
        <section
            class="py-24 text-center bg-gradient-to-br from-red-100 via-white to-red-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="max-w-4xl mx-auto px-6">
                <h1 class="text-5xl font-bold text-red-600 dark:text-red-400 mb-4">Selamat Datang di FishBite Resto</h1>
                <p class="text-xl text-gray-700 dark:text-gray-300 mb-8">Nikmati kelezatan hidangan laut segar dengan
                    cita rasa autentik dan sentuhan modern.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#menu-section"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-md font-semibold transition">Lihat
                        Menu</a>
                    <a href="#reservasi-section"
                        class="px-6 py-3 border border-red-600 text-red-600 hover:bg-red-600 hover:text-white rounded-lg font-semibold transition">Reservasi
                        Tempat</a>
                </div>
            </div>
        </section>

        <!-- Menu Section -->
<section id="menu-section" class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-red-600 dark:text-red-400 mb-10">Menu Andalan Kami</h2>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($menus as $menu)
                <div class="menu-item bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-xl transform hover:-translate-y-1 transition p-5">
                    <img src="{{ $menu->gambar_url ?: 'https://placehold.co/600x400/EF4444/FFFFFF?text=Menu' }}"
                        alt="{{ $menu->nama_menu }}" class="rounded-lg mb-4 w-full h-48 object-cover" />
                    <h3 class="menu-title text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ $menu->nama_menu }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-3">
                        {{ $menu->deskripsi ?: 'Keterangan menu belum tersedia.' }}</p>
                    <div class="text-lg font-bold text-red-500 dark:text-red-400">
                        @if ($menu->tipe_harga == 'satuan')
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($menu->harga_per_100gr, 0, ',', '.') }} / 100gr
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 col-span-3">Saat ini belum ada menu yang tersedia.</p>
            @endforelse
        </div>

        <!-- Lihat Semua Menu -->
        <div class="text-center mt-10">
            <a href="{{ route('menu.index') }}"
                class="inline-block px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow font-semibold transition">
                Lihat Semua Menu
            </a>
        </div>
    </div>
</section>


        <!-- Reservation Section -->
        <section id="reservasi-section"
            class="py-20 bg-gradient-to-br from-white to-red-50 dark:from-gray-900 dark:to-gray-800">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold text-red-600 dark:text-red-400 mb-6">Ingin Reservasi Tempat?</h2>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">Pastikan Anda mendapatkan tempat duduk favorit.
                    Silakan daftar atau login untuk reservasi meja dengan mudah.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 font-semibold transition">Daftar
                        Sekarang</a>
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 border border-red-600 text-red-600 hover:bg-red-600 hover:text-white rounded-lg font-semibold transition">Login</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-red-600 text-white py-6">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="flex justify-center space-x-6 mb-3">
                    <a href="#" class="hover:text-gray-200 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-gray-200 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-gray-200 transition"><i class="fab fa-twitter"></i></a>
                </div>
                <p class="text-sm">© {{ date('Y') }} FishBite Resto. All Rights Reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchMenu');
            const kategoriSelect = document.getElementById('filterKategori');
            const menuGrid = document.getElementById('menuGrid');
            const menuItems = menuGrid.querySelectorAll('.menu-item');

            function filterAndSearch() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedKategori = kategoriSelect.value;
                menuItems.forEach(item => {
                    const title = item.querySelector('.menu-title').textContent.toLowerCase();
                    const kategori = item.dataset.kategori;
                    const matchesSearch = title.includes(searchTerm);
                    const matchesKategori = !selectedKategori || kategori === selectedKategori;
                    item.style.display = matchesSearch && matchesKategori ? 'block' : 'none';
                });
            }
            searchInput.addEventListener('input', filterAndSearch);
            kategoriSelect.addEventListener('change', filterAndSearch);
        });
    </script>
</body>

</html>
