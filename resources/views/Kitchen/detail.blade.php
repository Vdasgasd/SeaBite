<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-receipt mr-2"></i> {{ __('Detail Pesanan #') }}{{ $pesanan->pesanan_id }}
            </h2>
            <a href="{{ route('kitchen.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Info Pesanan -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Informasi Pesanan</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ID Pesanan</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $pesanan->pesanan_id }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Meja</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $pesanan->meja->nomor_meja }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Waktu Pesanan</label>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pesanan->waktu_pesanan->format('d/m/Y H:i:s') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <div class="mt-1">
                                        @if($pesanan->status_pesanan === 'antrian')
                                            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>
                                                Antrian
                                            </span>
                                        @elseif($pesanan->status_pesanan === 'diproses')
                                            <span class="bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                                                <i class="fas fa-fire mr-1"></i>
                                                Sedang Dimasak
                                            </span>
                                        @elseif($pesanan->status_pesanan === 'selesai')
                                            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                                <i class="fas fa-check mr-1"></i>
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Menu -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Detail Menu</h3>

                            <div class="space-y-4">
                                @foreach($pesanan->detailPesanan as $detail)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $detail->menu->nama_menu }}</h4>
                                                <p class="text-gray-600">{{ $detail->menu->kategori->nama_kategori ?? 'Tidak ada kategori' }}</p>
                                            </div>
                                            <span class="bg-blue-100 text-blue-800 text-lg font-bold px-3 py-1 rounded">
                                                {{ $detail->jumlah }}x
                                            </span>
                                        </div>

                                        @if($detail->metodeMasak)
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Metode Masak</label>
                                                <p class="text-blue-600 font-medium">
                                                    <i class="fas fa-utensils mr-1"></i>
                                                    {{ $detail->metodeMasak->nama_metode }}
                                                </p>
                                            </div>
                                        @endif

                                        @if($detail->catatan)
                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Catatan Khusus</label>
                                                <p class="text-gray-800 bg-yellow-50 p-2 rounded border border-yellow-200">
                                                    <i class="fas fa-sticky-note mr-1 text-yellow-600"></i>
                                                    {{ $detail->catatan }}
                                                </p>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span>Harga Satuan: Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                                            <span class="font-semibold">Subtotal: Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center text-xl font-bold">
                                    <span>Total Pesanan:</span>
                                    <span class="text-green-600">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg sticky top-24">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Aksi Pesanan</h3>

                            <div class="space-y-4">
                                @if($pesanan->status_pesanan === 'antrian')
                                    <button onclick="updateStatus('diproses')"
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-fire mr-2"></i>
                                        Mulai Memasak
                                    </button>
                                @elseif($pesanan->status_pesanan === 'diproses')
                                    <button onclick="updateStatus('selesai')"
                                            class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-check mr-2"></i>
                                        Selesai Dimasak
                                    </button>
                                @elseif($pesanan->status_pesanan === 'selesai')
                                    <div class="text-center py-4">
                                        <i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>
                                        <p class="text-green-600 font-semibold">Pesanan Selesai</p>
                                    </div>
                                @endif

                                <button onclick="window.print()"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-print mr-2"></i>
                                    Cetak Detail
                                </button>
                            </div>

                            <!-- Timer untuk pesanan yang sedang diproses -->
                            @if($pesanan->status_pesanan === 'diproses')
                                <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                                    <h4 class="font-semibold text-orange-800 mb-2">Waktu Memasak</h4>
                                    <div id="cooking-timer" class="text-2xl font-bold text-orange-600">
                                        00:00:00
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(status) {
            if (!confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
                return;
            }

            fetch(`/kitchen/pesanan/{{ $pesanan->pesanan_id }}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status_pesanan: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal mengubah status pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }

        // Timer untuk pesanan yang sedang diproses
        @if($pesanan->status_pesanan === 'diproses')
            let startTime = new Date('{{ $pesanan->updated_at }}').getTime();

            function updateTimer() {
                let now = new Date().getTime();
                let elapsed = now - startTime;

                let hours = Math.floor(elapsed / (1000 * 60 * 60));
                let minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

                document.getElementById('cooking-timer').textContent =
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        @endif
    </script>
</x-app-layout>
