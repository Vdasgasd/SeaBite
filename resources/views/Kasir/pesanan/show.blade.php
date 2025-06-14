<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>

    {{-- Elemen untuk notifikasi kustom --}}
    <div id="notification-banner" class="fixed top-20 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg z-50 transition-transform transform translate-x-full hidden">
        <p id="notification-message"></p>
    </div>


    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Detail Pesanan #{{ $pesanan->pesanan_id }}</h2>
                        <a href="{{ route('kasir.pesanan.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>

                    {{-- Menampilkan notifikasi dari session (jika ada) --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Informasi Pesanan</h3>
                            <div class="space-y-2">
                                {{-- Detail info pesanan tetap sama --}}
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Pesanan:</span>
                                    <span class="font-medium">#{{ $pesanan->pesanan_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Meja:</span>
                                    <span class="font-medium">{{ $pesanan->meja->nomor_meja }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu Pesanan:</span>
                                    <span class="font-medium">{{ $pesanan->waktu_pesanan->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($pesanan->status_pesanan == 'antrian') bg-blue-100 text-blue-800
                                        @elseif($pesanan->status_pesanan == 'diproses') bg-yellow-100 text-yellow-800
                                        @elseif($pesanan->status_pesanan == 'selesai') bg-green-100 text-green-800
                                        @elseif($pesanan->status_pesanan == 'dibayar') bg-purple-100 text-purple-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($pesanan->status_pesanan) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- === BAGIAN PEMBAYARAN YANG DIUBAH === --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Total Pembayaran</h3>
                            <div class="text-3xl font-bold text-green-600">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </div>

                            @if ($pesanan->status_pesanan == 'selesai')
                                <div id="payment-actions" class="mt-4 space-y-3">
                                    {{-- Tombol Pembayaran Tunai --}}
                                    <button id="pay-tunai"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Proses Bayar Tunai
                                    </button>

                                    {{-- Tombol Pembayaran Midtrans --}}
                                    <button id="pay-midtrans"
                                        class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded transition duration-200 flex items-center justify-center">
                                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        Bayar dengan QRIS / Online
                                    </button>
                                </div>
                                <div id="payment-loading" class="mt-4 text-center" style="display: none;">
                                    <p class="text-gray-600">Memproses pembayaran, mohon tunggu...</p>
                                    {{-- Animasi loading sederhana --}}
                                    <svg class="animate-spin h-8 w-8 text-blue-500 mx-auto mt-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            @elseif ($pesanan->status_pesanan == 'dibayar')
                                <div class="mt-4 p-3 bg-purple-100 border border-purple-400 text-purple-700 rounded text-center">
                                    Pesanan telah dibayar.
                                </div>
                                <a href="{{ route('kasir.invoice.index') }}"
                                   class="w-full inline-block text-center bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded mt-2 transition duration-200">
                                    Lihat Daftar Invoice
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Menu tetap sama --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Detail Menu</h3>
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                   {{-- ... Header tabel ... --}}
                                   <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Masak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah/Berat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                   </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pesanan->detailPesanan as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->menu->nama_menu }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->metodeMasak->nama_metode ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if ($detail->jumlah) {{ $detail->jumlah }} porsi @else {{ $detail->berat_gram }}g @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $detail->catatan ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                 <tfoot class="bg-gray-100">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === SCRIPT UNTUK PEMBAYARAN === --}}
    {{-- 1. Load script Midtrans Snap.js --}}
    <script type="text/javascript"
      src="{{ config('midtrans.snap_url') }}"
      data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        // Fungsi untuk menampilkan notifikasi kustom
        function showNotification(message, type = 'success') {
            const banner = document.getElementById('notification-banner');
            const messageEl = document.getElementById('notification-message');

            // Set message and style
            messageEl.textContent = message;
            banner.classList.remove('bg-green-500', 'bg-red-500', 'bg-blue-500');
            if (type === 'success') {
                banner.classList.add('bg-green-500');
            } else if (type === 'error') {
                banner.classList.add('bg-red-500');
            } else { // info
                banner.classList.add('bg-blue-500');
            }

            // Show banner
            banner.classList.remove('hidden', 'translate-x-full');
            banner.classList.add('translate-x-0');


            // Hide after 5 seconds
            setTimeout(() => {
                banner.classList.remove('translate-x-0');
                banner.classList.add('translate-x-full');
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Cek jika elemen pembayaran ada di halaman
            if(document.getElementById('pay-tunai')) {
                const pesananId = "{{ $pesanan->pesanan_id }}";
                const payTunaiButton = document.getElementById('pay-tunai');
                const payMidtransButton = document.getElementById('pay-midtrans');
                const paymentActions = document.getElementById('payment-actions');
                const paymentLoading = document.getElementById('payment-loading');

                payTunaiButton.addEventListener('click', () => handlePayment('tunai'));
                payMidtransButton.addEventListener('click', () => handlePayment('midtrans'));

                function handlePayment(method) {
                    showLoading(true);

                    fetch("{{ route('kasir.invoice.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            pesanan_id: pesananId,
                            metode_pembayaran: method
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Menangkap error HTTP seperti 500
                             return response.json().then(err => { throw new Error(err.message || 'Terjadi kesalahan server.') });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.error);
                        }

                        // Jika metode tunai, redirect berdasarkan respons dari controller
                        if (method === 'tunai') {
                            showNotification(data.message || 'Invoice tunai berhasil dibuat.');
                            window.location.href = data.redirect_url;
                            return;
                        }

                        // Jika metode midtrans, buka snap
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result){
                                showNotification('Pembayaran Berhasil! Mengalihkan halaman...');
                                // Redirect ke daftar invoice setelah pembayaran berhasil
                                // Webhook dari midtrans akan memproses pembuatan invoice di backend
                                window.location.href = "{{ route('kasir.invoice.index') }}";
                            },
                            onPending: function(result){
                                showNotification('Menunggu pembayaran Anda.', 'info');
                                showLoading(false);
                            },
                            onError: function(result){
                                showNotification('Pembayaran Gagal!', 'error');
                                showLoading(false);
                            },
                            onClose: function(){
                                showNotification('Anda menutup popup pembayaran.', 'info');
                                showLoading(false);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification(error.message || 'Terjadi kesalahan.', 'error');
                        showLoading(false);
                    });
                }

                function showLoading(isLoading) {
                    paymentActions.style.display = isLoading ? 'none' : 'block';
                    paymentLoading.style.display = isLoading ? 'block' : 'none';
                }
            }
        });
    </script>
</x-app-layout>
