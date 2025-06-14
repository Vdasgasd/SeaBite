<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pembayaran untuk Pesanan #{{ $pesanan->pesanan_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Meja: {{ $pesanan->meja->nomor_meja }}</h3>
                    <p class="text-2xl font-bold my-4">Total Tagihan: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>

                    <div id="payment-actions">
                        <h4 class="font-semibold mb-2">Pilih Metode Pembayaran:</h4>
                        <button id="pay-tunai" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Bayar Tunai
                        </button>

                        <button id="pay-midtrans" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Bayar dengan QRIS / Online
                        </button>
                    </div>

                    <div id="payment-loading" style="display: none;">
                        <p>Memproses pembayaran, jangan tutup halaman ini...</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
      src="{{ config('midtrans.snap_url') }}"
      data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const pesananId = "{{ $pesanan->pesanan_id }}";
            const payTunaiButton = document.getElementById('pay-tunai');
            const payMidtransButton = document.getElementById('pay-midtrans');
            const paymentActions = document.getElementById('payment-actions');
            const paymentLoading = document.getElementById('payment-loading');

            // --- Handler untuk Pembayaran Tunai ---
            payTunaiButton.addEventListener('click', function(e) {
                e.preventDefault();
                handlePayment('tunai');
            });

            // --- Handler untuk Pembayaran Midtrans ---
            payMidtransButton.addEventListener('click', function(e) {
                e.preventDefault();
                handlePayment('midtrans');
            });


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
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        showLoading(false);
                        return;
                    }

                    // Jika metode tunai, redirect berdasarkan respons
                    if (method === 'tunai') {
                        alert(data.message);
                        window.location.href = data.redirect_url;
                        return;
                    }

                    // Jika metode midtrans, buka snap
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            /* Anda bisa tambahkan notifikasi atau redirect di sini */
                            alert("Pembayaran berhasil!"); console.log(result);
                            // Redirect ke halaman invoice setelah pembayaran berhasil
                            window.location.href = `/kasir/invoice/${result.order_id}`;
                        },
                        onPending: function(result){
                            /* Anda bisa tambahkan notifikasi pending di sini */
                            alert("Menunggu pembayaran Anda!"); console.log(result);
                            showLoading(false);
                        },
                        onError: function(result){
                            /* Anda bisa tambahkan notifikasi error di sini */
                            alert("Pembayaran gagal!"); console.log(result);
                            showLoading(false);
                        },
                        onClose: function(){
                            /* Pelanggan menutup popup tanpa menyelesaikan pembayaran */
                            alert('Anda menutup popup pembayaran.');
                            showLoading(false);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    showLoading(false);
                });
            }

            function showLoading(isLoading) {
                paymentActions.style.display = isLoading ? 'none' : 'block';
                paymentLoading.style.display = isLoading ? 'block' : 'none';
            }
        });
    </script>
</x-app-layout>
