<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu - Meja {{ $meja->nomor_meja }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .modal {
            transition: opacity 0.25s ease;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div x-data="{ modalOpen: false, selectedMenu: null, beratRequired: false }">
        <!-- Header -->
        <header class="bg-white shadow-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-red-500">FishBite Resto</h1>
                    <p class="text-gray-600">Anda memesan untuk <span class="font-bold">Meja
                            {{ $meja->nomor_meja }}</span></p>
                </div>
            </div>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 max-w-7xl mx-auto mt-4"
                role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 max-w-7xl mx-auto mt-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Menu -->
                <div class="lg:col-span-2">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Pilih Menu</h2>

                    <!-- Daftar Menu -->
                    @foreach ($kategoris as $kategori)
                        <div class="mb-10">
                            <h3 class="text-2xl font-semibold text-gray-700 mb-4 pb-2 border-b-2 border-red-200">
                                {{ $kategori->nama_kategori }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($menus->where('kategori_id', $kategori->kategori_id) as $menu)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                        <img src="{{ $menu->gambar_url ?: 'https://placehold.co/600x400/cccccc/ffffff?text=Menu' }}"
                                            alt="{{ $menu->nama_menu }}" class="w-full h-40 object-cover">
                                        <div class="p-4">
                                            <h4 class="text-xl font-bold">{{ $menu->nama_menu }}</h4>
                                            <p class="text-gray-600 text-sm my-2">{{ $menu->deskripsi }}</p>
                                            <div class="flex justify-between items-center mt-4">
                                                <span class="font-bold text-lg text-red-500">
                                                    @if ($menu->tipe_harga == 'satuan')
                                                        Rp {{ number_format($menu->harga) }}
                                                    @else
                                                        Rp {{ number_format($menu->harga_per_100gr) }}/100g
                                                    @endif
                                                </span>
                                                <button
                                                    @click="modalOpen = true; selectedMenu = {{ $menu }}; beratRequired = selectedMenu.tipe_harga === 'berat'"
                                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                                                    Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Kolom Keranjang -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Keranjang Anda</h3>

                        @if (empty($cart))
                            <p class="text-gray-500 text-center py-8">Keranjang masih kosong.</p>
                        @else
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                @foreach ($cart as $id => $item)
                                    <div class="flex justify-between items-start border-b pb-2">
                                        <div>
                                            <p class="font-bold">{{ $item['nama_menu'] }}</p>
                                            <p class="text-sm text-gray-500">Jml: {{ $item['jumlah'] }} @if ($item['berat_gram'])
                                                    | Berat: {{ $item['berat_gram'] }}g
                                                @endif
                                            </p>
                                            @if ($item['nama_metode'])
                                                <p class="text-sm text-gray-500">Masak: {{ $item['nama_metode'] }}</p>
                                            @endif
                                            @if ($item['catatan'])
                                                <p class="text-sm text-gray-500 italic">Note: "{{ $item['catatan'] }}"
                                                </p>
                                            @endif
                                            <p class="font-semibold text-red-500">Rp
                                                {{ number_format($item['subtotal']) }}</p>
                                        </div>
                                        <form action="{{ route('order.cart.remove', $meja->nomor_meja) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $id }}">
                                            <button type="submit"
                                                class="text-gray-400 hover:text-red-500">&times;</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between font-bold text-xl">
                                    <span>Total</span>
                                    <span>Rp {{ number_format(array_sum(array_column($cart, 'subtotal'))) }}</span>
                                </div>

                                <form action="{{ route('order.place', $meja->nomor_meja) }}" method="POST"
                                    onsubmit="return confirm('Konfirmasi untuk membuat pesanan ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-500 text-white font-bold py-3 rounded-lg mt-4 hover:bg-green-600 transition-colors">
                                        Pesan Sekarang
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal Tambah Item -->
        <div x-show="modalOpen" @keydown.escape.window="modalOpen = false"
            class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.outside="modalOpen = false">
                <h3 class="text-2xl font-bold mb-4" x-text="selectedMenu ? selectedMenu.nama_menu : ''"></h3>

                <form :action="'/pesan/' + '{{ $meja->nomor_meja }}' + '/add'" method="POST">
                    @csrf
                    <input type="hidden" name="menu_id" :value="selectedMenu ? selectedMenu.menu_id : ''">

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label for="jumlah" class="block font-medium mb-1">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" value="1" min="1"
                            class="w-full p-2 border rounded">
                    </div>

                    <!-- Berat (jika perlu) -->
                    <div class="mb-4" x-show="beratRequired">
                        <label for="berat" class="block font-medium mb-1">Berat (gram)</label>
                        <input type="number" id="berat" name="berat_gram" placeholder="Contoh: 500" min="1"
                            class="w-full p-2 border rounded" :required="beratRequired">
                    </div>

                    <!-- Metode Masak -->
                    <div class="mb-4">
                        <label for="metode" class="block font-medium mb-1">Metode Masak (Opsional)</label>
                        <select name="metode_masak_id" id="metode" class="w-full p-2 border rounded">
                            <option value="">-- Pilih Metode --</option>
                            @foreach ($metodeMasaks as $metode)
                                <option value="{{ $metode->metode_id }}">{{ $metode->nama_metode }} (+Rp
                                    {{ number_format($metode->biaya_tambahan) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-6">
                        <label for="catatan" class="block font-medium mb-1">Catatan (Opsional)</label>
                        <textarea name="catatan" id="catatan" rows="3" class="w-full p-2 border rounded"
                            placeholder="Contoh: jangan terlalu pedas"></textarea>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Tambah
                            ke Keranjang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
