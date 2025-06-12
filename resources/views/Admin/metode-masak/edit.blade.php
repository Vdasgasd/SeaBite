<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Metode Masak: ') }} {{ $metode->nama_metode }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.metode-masak.update', $metode) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="nama_metode" class="block text-sm font-medium text-gray-700">Nama Metode</label>
                            <input type="text" name="nama_metode" id="nama_metode" value="{{ old('nama_metode', $metode->nama_metode) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('nama_metode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="biaya_tambahan" class="block text-sm font-medium text-gray-700">Biaya Tambahan (Rp)</label>
                            <input type="number" name="biaya_tambahan" id="biaya_tambahan" value="{{ old('biaya_tambahan', $metode->biaya_tambahan) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('biaya_tambahan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.metode-masak.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
