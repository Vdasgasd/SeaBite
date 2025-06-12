<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Menu: ') }} {{ $menu->nama_menu }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.menu.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- NAMA & KATEGORI --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="nama_menu" class="block text-sm font-medium text-gray-700">Nama Menu</label>
                                <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('nama_menu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori_id" id="kategori_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id', $menu->kategori_id) == $kategori->kategori_id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="mb-4">
                             <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                             <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                             @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- TIPE HARGA & IKAN --}}
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="tipe_harga" class="block text-sm font-medium text-gray-700">Tipe Harga</label>
                                <select name="tipe_harga" id="tipe_harga" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="satuan" {{ old('tipe_harga', $menu->tipe_harga) == 'satuan' ? 'selected' : '' }}>Satuan</option>
                                    <option value="berat" {{ old('tipe_harga', $menu->tipe_harga) == 'berat' ? 'selected' : '' }}>Berat</option>
                                </select>
                                @error('tipe_harga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="ikan_id" class="block text-sm font-medium text-gray-700">Jenis Ikan (Jika tipe harga berat)</label>
                                <select name="ikan_id" id="ikan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Tidak ada</option>
                                    @foreach($ikans as $ikan)
                                        <option value="{{ $ikan->ikan_id }}" {{ old('ikan_id', $menu->ikan_id) == $ikan->ikan_id ? 'selected' : '' }}>{{ $ikan->nama_ikan }}</option>
                                    @endforeach
                                </select>
                                @error('ikan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- HARGA --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div id="harga_satuan_div">
                                <label for="harga" class="block text-sm font-medium text-gray-700">Harga Satuan (Rp)</label>
                                <input type="number" name="harga" id="harga" value="{{ old('harga', $menu->harga) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('harga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div id="harga_berat_div">
                                <label for="harga_per_100gr" class="block text-sm font-medium text-gray-700">Harga per 100gr (Rp)</label>
                                <input type="number" name="harga_per_100gr" id="harga_per_100gr" value="{{ old('harga_per_100gr', $menu->harga_per_100gr) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('harga_per_100gr')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- GAMBAR --}}
                        <div class="mb-4">
                            <label for="gambar_url" class="block text-sm font-medium text-gray-700">URL Gambar</label>
                            <input type="text" name="gambar_url" id="gambar_url" value="{{ old('gambar_url', $menu->gambar_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('gambar_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.menu.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipeHargaSelect = document.getElementById('tipe_harga');
            const hargaSatuanDiv = document.getElementById('harga_satuan_div');
            const hargaBeratDiv = document.getElementById('harga_berat_div');

            function toggleHargaFields() {
                if (tipeHargaSelect.value === 'satuan') {
                    hargaSatuanDiv.style.display = 'block';
                    hargaBeratDiv.style.display = 'none';
                } else {
                    hargaSatuanDiv.style.display = 'none';
                    hargaBeratDiv.style.display = 'block';
                }
            }

            toggleHargaFields();
            tipeHargaSelect.addEventListener('change', toggleHargaFields);
        });
    </script>
</x-app-layout>
