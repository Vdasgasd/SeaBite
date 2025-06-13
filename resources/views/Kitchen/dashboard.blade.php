<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-fire-burner mr-2"></i> {{ __('Dashboard Dapur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- KOLOM PESANAN MASUK -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-inbox text-blue-500 mr-3"></i>
                                Pesanan Masuk
                                <span id="pesanan-count"
                                    class="ml-2 bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                    {{ $pesananMasuk->count() }}
                                </span>
                            </h3>
                            <div id="pesanan-masuk-container" class="space-y-6">
                                @include('kitchen.partial.pesanan-cards', [
                                    'pesananMasuk' => $pesananMasuk,
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM RIWAYAT SELESAI -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm sm:rounded-lg sticky top-24">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-history text-green-500 mr-3"></i>
                                Selesai Dimasak
                            </h3>
                            <div class="space-y-3">
                                @forelse ($pesananSiap as $pesanan)
                                    <div class="bg-green-50 p-3 rounded-md border border-green-200">
                                        <p class="font-semibold text-green-800">Pesanan #{{ $pesanan->pesanan_id }}
                                            (Meja {{ $pesanan->meja->nomor_meja }})
                                        </p>
                                        <div class="mt-2">
                                            @foreach ($pesanan->detailPesanan as $detail)
                                                <span
                                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                                    {{ $detail->menu->nama_menu }} ({{ $detail->jumlah }}x)
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">Belum ada pesanan yang diselesaikan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    @include('kitchen.partial.detail-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('pesanan-masuk-container');
            const pesananCountSpan = document.getElementById('pesanan-count');

            function updatePesananCount(count) {
                pesananCountSpan.textContent = count;
            }

            function fetchNewOrders() {
                fetch('{{ route('kitchen.api.getNewOrders') }}')
                    .then(response => response.json())
                    .then(data => {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.html;

                        const newCards = tempDiv.querySelectorAll('.pesanan-card');
                        const currentCards = container.querySelectorAll('.pesanan-card');

                        container.innerHTML = data.html;
                        updatePesananCount(newCards.length);
                    })
                    .catch(error => {
                        console.error('Gagal memuat pesanan:', error);
                    });
            }

            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Mulai polling
            setInterval(fetchNewOrders, 10000);
        });
    </script>

</x-app-layout>
