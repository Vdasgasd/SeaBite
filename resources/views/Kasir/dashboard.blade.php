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
                    <div class="space-y-4">
                        @forelse ($pesananSelesai as $pesanan)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Pesanan #{{ $pesanan->pesanan_id }}</h4>
                                        <p class="text-sm text-gray-500">Meja: {{ $pesanan->meja->nomor_meja }}</p>
                                        <p class="text-sm text-gray-500">Waktu:
                                            {{ \Carbon\Carbon::parse($pesanan->waktu_pesanan)->translatedFormat('d F Y H:i') }}
                                        </p>
                                        <ul class="mt-2 space-y-1">
                                            @foreach ($pesanan->detailPesanan as $detail)
                                                <li class="text-sm text-gray-600">{{ $detail->menu->nama_menu }} -
                                                    {{ $detail->jumlah ? $detail->jumlah . ' item' : $detail->berat_gram . ' gram' }}
                                                    (Rp {{ number_format($detail->subtotal, 0, ',', '.') }})
                                                </li>
                                            @endforeach
                                        </ul>
                                        <p class="font-medium text-lg text-green-600 mt-2">Total: Rp
                                            {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                    <button
                                        onclick="openPaymentModal('{{ $pesanan->pesanan_id }}', '{{ $pesanan->total_harga }}')"
                                        class="text-blue-600 hover:text-blue-900">
                                        Proses Pembayaran
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Tidak ada pesanan yang perlu dibayar saat ini.</p>
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
