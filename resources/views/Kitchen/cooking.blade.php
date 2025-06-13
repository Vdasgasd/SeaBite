<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-fire mr-2"></i> {{ __('Pesanan Sedang Dimasak') }}
            </h2>
            <a href="{{ route('kitchen.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Pesanan yang Sedang Diproses
                            <span class="ml-2 bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                {{ $pesananCooking->count() }} pesanan
                            </span>
                        </h3>
                        <button onclick="refreshOrders()" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            <i class="fas fa-sync mr-2"></i>
                            Refresh
                        </button>
                    </div>

                    <div id="cooking-orders-container" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse ($pesananCooking as $pesanan)
                            <div class="cooking-order-card bg-orange-50 border-2 border-orange-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-xl font-bold text-gray-900">
                                            Pesanan #{{ $pesanan->pesanan_id }}
                                        </h4>
                                        <p class="text-gray-600">
                                            <i class="fas fa-chair mr-1"></i>
                                            Meja {{ $pesanan->meja->nomor_meja }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            Mulai: {{ $pesanan->updated_at->format('H:i:s') }}
                                        </p>
                                    </div>
                                    <span class="bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                                        <i class="fas fa-fire mr-1"></i>
                                        Memasak
                                    </span>
                                </div>

                                <!-- Timer -->
                                <div class="mb-4 p-3 bg-orange-100 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-orange-800">Waktu Memasak:</span>
                                        <span class="cooking-timer text-lg font-bold text-orange-600" data-start-time="{{ $pesanan->updated_at->timestamp }}">
                                            00:00:00
                                        </span>
                                    </div>
                                </div>

                                <!-- Detail Menu Ringkas -->
                                <div class="space-y-2 mb-4">
                                    @foreach($pesanan->detailPesanan->take(3) as $detail)
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-700">{{ $detail->menu->nama_menu }}</span>
                                            <span class="bg-blue-100 text-blue-800 font-medium px-2 py-1 rounded">
                                                {{ $detail->jumlah }}x
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($pesanan->detailPesanan->count() > 3)
                                        <p class="text-xs text-gray-500 text-center">
                                            +{{ $pesanan->detailPesanan->count() - 3 }} item lainnya
                                        </p>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <button onclick="markAsReady({{ $pesanan->pesanan_id }})"
                                            class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-check mr-1"></i>
                                        Selesai
                                    </button>
                                    <a href="{{ route('kitchen.pesanan.show', $pesanan->pesanan_id) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-fire text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-600 mb-2">Tidak ada pesanan yang sedang dimasak</h3>
                                <p class="text-gray-500">Semua pesanan sudah selesai diproses.</p>
                                <a href="{{ route('kitchen.dashboard') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAsReady(pesananId) {
            if (!confirm('Apakah pesanan ini sudah selesai dimasak?')) {
                return;
            }

            fetch(`/kitchen/pesanan/${pesananId}/ready`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    location.reload();
                } else {
                    alert('Gagal menandai pesanan sebagai selesai');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }

        function refreshOrders() {
            location.reload();
        }

        // Update all timers
        function updateTimers() {
            document.querySelectorAll('.cooking-timer').forEach(timer => {
                const startTime = parseInt(timer.dataset.startTime) * 1000;
                const now = new Date().getTime();
                const elapsed = now - startTime;

                const hours = Math.floor(elapsed / (1000 * 60 * 60));
                const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

                timer.textContent =
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');

                // Change color based on cooking time
                if (elapsed > 30 * 60 * 1000) { // > 30 minutes
                    timer.classList.add('text-red-600');
                    timer.classList.remove('text-orange-600');
                } else if (elapsed > 15 * 60 * 1000) { // > 15 minutes
                    timer.classList.add('text-yellow-600');
                    timer.classList.remove('text-orange-600');
                }
            });
        }

        // Update timers every second
        setInterval(updateTimers, 1000);
        updateTimers(); // Initial call

        // Auto refresh every 30 seconds
        setInterval(refreshOrders, 30000);
    </script>
</x-app-layout>
