<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Meja: ') }} {{ $meja->nomor_meja }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.meja.update', $meja) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="nomor_meja" class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                                <input type="text" name="nomor_meja" id="nomor_meja" value="{{ old('nomor_meja', $meja->nomor_meja) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('nomor_meja')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                                <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $meja->kapasitas) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @error('kapasitas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="tersedia" {{ old('status', $meja->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="terisi" {{ old('status', $meja->status) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                <option value="direservasi" {{ old('status', $meja->status) == 'direservasi' ? 'selected' : '' }}>Direservasi</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.meja.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-gray-500 font-bold py-2 px-4 rounded">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
