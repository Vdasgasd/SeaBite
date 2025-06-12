<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Semua Menu | FishBite Resto</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree antialiased bg-white dark:bg-gray-900 text-gray-800 dark:text-white">
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

        <!-- Main Content -->
        <main class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-center text-red-600 dark:text-red-400 mb-10">Semua Menu</h2>

                <!-- Search & Filter Form -->
                <form method="GET" class="flex flex-col md:flex-row gap-4 mb-10 max-w-3xl mx-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari menu favoritmu..."
                        class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white" />

                    <select name="kategori"
                        class="w-full md:w-auto p-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->nama_kategori }}" {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                        Cari
                    </button>
                </form>

                <!-- Menu Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($menus as $menu)
                        <div class="menu-item bg-white dark:bg-gray-800 rounded-xl shadow hover:shadow-xl transform hover:-translate-y-1 transition p-5">
                            <img src="{{ $menu->gambar_url ?: 'https://placehold.co/600x400/EF4444/FFFFFF?text=Menu' }}"
                                alt="{{ $menu->nama_menu }}" class="rounded-lg mb-4 w-full h-48 object-cover" />
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ $menu->nama_menu }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-3">
                                {{ $menu->deskripsi ?: 'Keterangan menu belum tersedia.' }}
                            </p>
                            <div class="text-lg font-bold text-red-500 dark:text-red-400">
                                @if ($menu->tipe_harga == 'satuan')
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($menu->harga_per_100gr, 0, ',', '.') }} / 100gr
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 col-span-3">Menu tidak ditemukan.</p>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $menus->withQueryString()->links() }}
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-red-600 text-white py-6 mt-20">
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
</body>

</html>
