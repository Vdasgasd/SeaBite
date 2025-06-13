<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-fire-burner mr-2"></i> {{ __('Dashboard Dapur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Menampilkan notifikasi sukses atau error --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <p class="font-bold">Sukses</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2">
                    <div class="bg-white/50 backdrop-blur-sm shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-inbox text-blue-500 mr-3"></i>
                            Pesanan Aktif
                            <span id="pesanan-count"
                                class="ml-3 bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-1 rounded-full">
                                {{ $pesananMasuk->count() }}
                            </span>
                        </h3>

                        {{-- Memanggil partial view untuk daftar pesanan --}}
                        @include('kitchen._pesanan-list', ['pesananMasuk' => $pesananMasuk])

                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg sticky top-24">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-history text-green-500 mr-3"></i>
                                Riwayat Selesai
                            </h3>
                            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                                @forelse ($pesananSiap as $pesanan)
                                    <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                        <p class="font-semibold text-green-800">
                                            Pesanan #{{ $pesanan->pesanan_id }} (Meja {{ $pesanan->meja->nomor_meja }})
                                        </p>
                                        <p class="text-xs text-gray-500">Selesai: {{ $pesanan->updated_at->format('H:i') }}</p>
                                        <div class="mt-2 border-t pt-2">
                                            @foreach ($pesanan->detailPesanan as $detail)
                                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">
                                                    {{ $detail->menu->nama_menu }} ({{ $detail->jumlah }}x)
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Belum ada pesanan yang diselesaikan hari ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
