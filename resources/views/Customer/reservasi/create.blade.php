<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Reservasi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Judul Sesuai Gaya Dashboard --}}
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Langkah 1: Cek Ketersediaan Meja</h3>

                    <p class="text-gray-600 mb-6">
                        Silakan masukkan tanggal, waktu, dan jumlah tamu untuk menemukan meja yang sempurna untuk Anda.
                    </p>

                    <!-- Menampilkan Error Validasi dengan Gaya yang Sama -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200 text-red-700" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-5a1 1 0 0 1 1-1h2a1 1 0 0 1 0 2h-2a1 1 0 0 1-1-1zm1-4a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1z"/></svg></div>
                                <div>
                                    <p class="font-bold">Terjadi kesalahan</p>
                                    <ul class="mt-1 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form untuk Cek Ketersediaan -->
                    <form action="{{ route('customer.reservasi.availableTables') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Waktu Reservasi -->
                            <div>
                                <x-input-label for="waktu_reservasi" :value="__('Tanggal & Waktu Reservasi')" />
                                <x-text-input id="waktu_reservasi" class="block mt-1 w-full" type="datetime-local" name="waktu_reservasi" :value="old('waktu_reservasi')" required autofocus />
                                <x-input-error :messages="$errors->get('waktu_reservasi')" class="mt-2" />
                            </div>

                            <!-- Jumlah Tamu -->
                            <div>
                                <x-input-label for="jumlah_tamu" :value="__('Jumlah Tamu')" />
                                <x-text-input id="jumlah_tamu" class="block mt-1 w-full" type="number" name="jumlah_tamu" :value="old('jumlah_tamu')" required min="1" placeholder="Contoh: 4" />
                                <x-input-error :messages="$errors->get('jumlah_tamu')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <button type="submit" class="inline-flex items-center bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition-transform transform hover:scale-105">
                                <i class="fas fa-search mr-2"></i>
                                {{ __('Cari Meja Tersedia') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
