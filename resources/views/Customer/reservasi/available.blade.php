<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pilih Meja & Lengkapi Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <div class="flex justify-between items-center border-b pb-2 mb-4">
                        <h3 class="text-xl font-semibold">Langkah 2: Pilih Meja Anda</h3>
                        <a href="{{ route('customer.reservasi.create') }}"
                            class="text-sm font-semibold text-blue-600 hover:underline">
                            &larr; Ubah Waktu
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200 text-red-700" role="alert">
                            <p class="font-bold">Oops! Pastikan semua data terisi dengan benar, termasuk memilih meja.
                            </p>
                        </div>
                    @endif

                    @if ($availableTables->isEmpty())
                        {{-- Tampilan jika tidak ada meja --}}
                        <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-lg border">
                            <i class="far fa-calendar-times fa-3x mb-3 text-gray-400"></i>
                            <h4 class="text-lg font-semibold text-gray-600 mb-2">Tidak Ada Meja Tersedia</h4>
                            <p>Maaf, tidak ada meja yang tersedia untuk waktu dan jumlah tamu yang Anda pilih. Silakan
                                coba waktu lain.</p>
                        </div>
                    @else
                        <form action="{{ route('customer.reservasi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="waktu_reservasi" value="{{ $waktu_reservasi }}">
                            <input type="hidden" name="jumlah_tamu" value="{{ $jumlah_tamu }}">

                            {{-- Pilihan Meja dengan gaya visual --}}
                            <p class="text-gray-600 mb-4">Pilih salah satu meja di bawah ini yang paling sesuai untuk
                                Anda.</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
                                @foreach ($availableTables as $meja)
                                    <div>
                                        <input type="radio" id="meja_{{ $meja->meja_id }}" name="meja_id"
                                            value="{{ $meja->meja_id }}" class="hidden peer"
                                            {{ old('meja_id') == $meja->meja_id ? 'checked' : '' }} required>
                                        <label for="meja_{{ $meja->meja_id }}"
                                            class="block p-4 border-2 rounded-lg text-center cursor-pointer hover:bg-blue-50 hover:border-blue-500 transition-all duration-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md">
                                            <div class="text-3xl text-blue-500 mb-2">
                                                <i class="fas fa-chair"></i>
                                            </div>
                                            <p class="font-bold text-lg text-gray-800">{{ $meja->nomor_meja }}</p>
                                            <p class="text-sm text-gray-500">Kapasitas: {{ $meja->kapasitas }}</p>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('meja_id')" class="mt-2 mb-6 text-center" />

                            {{-- Form Isian Data Diri --}}
                            <h4 class="text-lg font-semibold mb-4 border-t pt-6">Lengkapi Data Anda</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="nama_pelanggan" :value="__('Nama Lengkap')" />
                                    <x-text-input id="nama_pelanggan" class="block mt-1 w-full" type="text"
                                        name="nama_pelanggan" :value="auth()->user()->name" required />
                                    <x-input-error :messages="$errors->get('nama_pelanggan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="telepon" :value="__('Nomor Telepon')" />
                                    <x-text-input id="telepon" class="block mt-1 w-full" type="tel" name="telepon"
                                        :value="old('telepon')" required placeholder="08123456789" />
                                    <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-8 border-t pt-6">
                                <button type="submit"
                                    class="inline-flex items-center bg-red-500 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-red-600 transition-transform transform hover:scale-105">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ __('Konfirmasi & Buat Reservasi') }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
