<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Summary Cards --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Selamat datang, {{ $user->name }}!</h3>
                    <p class="text-gray-600">Ringkasan aktivitas hari ini di bawah.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-gray-600 font-medium">Total Pesanan (Hari Ini)</h4>
                        <p class="text-2xl font-bold">{{ $totalPesananHariIni }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-gray-600 font-medium">Invoice Dibuat (Hari Ini)</h4>
                        <p class="text-2xl font-bold">{{ $totalInvoiceHariIni }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mb-4">
                <a href="{{ route('kasir.invoice.index') }}"
                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Lihat Semua Invoice
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Pesanan Selesai (Siap Bayar)</h3>
                        <a href="{{ route('kasir.pesanan.index') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Lihat Semua Pesanan
                        </a>
                    </div>
                    <div class="space-y-6">
                        @forelse ($pesananSelesai as $pesanan)
                            <div
                                class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm hover:shadow-md transition">
                                <div class="flex justify-between items-start flex-wrap gap-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 mb-1">📦 Pesanan
                                            #{{ $pesanan->pesanan_id }}</h4>
                                        <p class="text-sm text-gray-500">🪑 Meja: <span
                                                class="font-medium text-gray-700">{{ $pesanan->meja->nomor_meja }}</span>
                                        </p>
                                        <p class="text-sm text-gray-500">⏰ Waktu:
                                            <span
                                                class="text-gray-700">{{ \Carbon\Carbon::parse($pesanan->waktu_pesanan)->translatedFormat('d F Y H:i') }}</span>
                                        </p>

                                        <ul class="mt-4 space-y-1">
                                            @foreach ($pesanan->detailPesanan as $detail)
                                                <li class="text-sm text-gray-600 flex items-center">
                                                    🍽️ <span class="ml-1">{{ $detail->menu->nama_menu }} –
                                                        {{ $detail->jumlah ? $detail->jumlah . ' item' : $detail->berat_gram . ' gram' }}
                                                        (<span class="text-gray-700">Rp
                                                            {{ number_format($detail->subtotal, 0, ',', '.') }}</span>)
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <p class="font-semibold text-green-600 text-base mt-4">
                                            💰 Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <a href="{{ route('kasir.pesanan.show', $pesanan) }}"
                                        class="self-start inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg shadow-md transition-all duration-300 ease-in-out">
                                        🔍 Lihat Pesanan
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8 text-base">📭 Tidak ada pesanan yang perlu dibayar
                                saat ini.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $pesananSelesai->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
