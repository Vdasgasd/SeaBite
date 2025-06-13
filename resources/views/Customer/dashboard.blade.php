<x-app-layout>
    <div x-data="{ isModalOpen: false }">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if($isGuest)
                    {{ __('Dashboard Tamu') }}
                @else
                    {{ __('Dashboard Pelanggan') }}
                @endif
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Header berbeda untuk guest vs logged user --}}
                        @if($isGuest)
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <h3 class="text-2xl font-bold mb-2 text-blue-800">Selamat Datang, Tamu!</h3>
                                <p class="text-blue-600 mb-3">Anda dapat memesan tanpa login. Untuk fitur lengkap seperti riwayat pesanan dan reservasi meja, silakan daftar akun.</p>
                                <div class="flex gap-2">
                                    <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        @else
                            <h3 class="text-2xl font-bold mb-4">Selamat Datang, {{ $user->name }}!</h3>
                        @endif

                        <!-- Tombol Aksi Pesan -->
                        <div class="mb-8 p-6 bg-red-50 rounded-lg border border-red-200 text-center">
                            <h4 class="text-xl font-semibold text-red-800 mb-2">Siap untuk memesan?</h4>
                            <p class="text-red-600 mb-4">Pilih meja yang tersedia dan nikmati hidangan kami!</p>

                            @if (!$daftarMejaTersedia->isEmpty())
                                <button @click="isModalOpen = true"
                                    class="inline-block bg-red-500 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-red-600 transition-transform transform hover:scale-105">
                                    <i class="fas fa-utensils mr-2"></i> Pilih Meja & Pesan
                                </button>
                            @else
                                <p class="font-semibold text-red-700 bg-red-100 p-3 rounded-lg">
                                    Saat ini semua meja sedang penuh. Silakan kembali lagi nanti.
                                </p>
                            @endif
                        </div>

                        <!-- Status Pesanan Aktif -->
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4 border-b pb-2">Status Pesanan Aktif</h4>
                            @if ($pesananAktif)
                                <div id="pesanan-aktif-card" class="bg-blue-50 p-6 rounded-lg border border-blue-200"
                                    data-pesanan-id="{{ $pesananAktif->pesanan_id }}">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-500">ID Pesanan: <span
                                                    class="font-bold">#{{ $pesananAktif->pesanan_id }}</span></p>
                                            <p class="text-sm text-gray-500">Meja: <span
                                                    class="font-bold">{{ $pesananAktif->meja->nomor_meja }}</span></p>
                                        </div>
                                        <div id="status-badge-container">
                                            <span class='px-3 py-1 text-sm font-semibold rounded-full
                                                @if($pesananAktif->status_pesanan == "antrian") bg-yellow-100 text-yellow-800
                                                @elseif($pesananAktif->status_pesanan == "diproses") bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif'>
                                                {{ ucfirst($pesananAktif->status_pesanan) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4 border-t pt-4">
                                        <p class="font-semibold mb-2">Detail Pesanan:</p>
                                        <ul class="list-disc list-inside text-gray-700">
                                            @foreach ($pesananAktif->detailPesanan as $detail)
                                                <li>{{ $detail->menu->nama_menu }} (x{{ $detail->jumlah }})
                                                    @if($detail->berat_gram)
                                                        - {{ $detail->berat_gram }}g
                                                    @endif
                                                    @if($detail->catatan)
                                                        <span class="text-sm text-gray-500">- {{ $detail->catatan }}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-3 pt-3 border-t">
                                            <p class="font-bold text-lg">Total: Rp {{ number_format($pesananAktif->total_harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div id="pesanan-aktif-card"
                                    class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                                    <i class="fas fa-receipt fa-2x mb-2"></i>
                                    <p>Anda tidak memiliki pesanan aktif saat ini.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Reservasi Meja (hanya untuk user login) -->
                        @if(!$isGuest)
                        <div class="mb-8">
                            <div class="flex justify-between items-center border-b pb-2 mb-4">
                                <h4 class="text-xl font-semibold">Reservasi Meja</h4>
                                <a href="{{ route('customer.reservasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    + Buat Reservasi
                                </a>
                            </div>

                            @isset($reservasiTerakhir)
                                <div class="bg-gray-50 p-4 rounded-lg border">
                                    <div class="flex flex-wrap justify-between items-center gap-4">
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                @if($reservasiTerakhir->waktu_reservasi > now())
                                                    Reservasi Mendatang
                                                @else
                                                    Reservasi Terakhir
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Meja {{ $reservasiTerakhir->meja->nomor_meja }} &bull; {{ \Carbon\Carbon::parse($reservasiTerakhir->waktu_reservasi)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($reservasiTerakhir->status == 'dipesan') bg-blue-100 text-blue-800
                                                @elseif($reservasiTerakhir->status == 'selesai') bg-green-100 text-green-800
                                                @elseif($reservasiTerakhir->status == 'batal') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($reservasiTerakhir->status) }}
                                            </span>
                                        </div>
                                    </div>
                                     <a href="{{ route('customer.reservasi.index') }}" class="text-sm font-semibold text-blue-600 hover:underline mt-4 inline-block">
                                         Lihat Semua Riwayat Reservasi &rarr;
                                     </a>
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border">
                                    <i class="far fa-calendar-times fa-2x mb-3 text-gray-400"></i>
                                    <p>Anda belum memiliki riwayat reservasi.</p>
                                </div>
                            @endisset
                        </div>
                        @endif

                        <!-- Riwayat Pemesanan (hanya untuk user login) -->
                        @if(!$isGuest)
                            <div>
                                <h4 class="text-xl font-semibold mb-4 border-b pb-2">Riwayat Pemesanan</h4>
                                <div class="space-y-4">
                                    @forelse ($riwayatPesanan as $pesanan)
                                        <div class="bg-gray-50 p-4 rounded-lg border">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="font-bold">ID Pesanan: #{{ $pesanan->pesanan_id }}</p>
                                                    <p class="text-sm text-gray-500">Tanggal:
                                                        {{ $pesanan->waktu_pesanan->format('d M Y, H:i') }}</p>
                                                    <p class="text-sm text-gray-500">Meja: {{ $pesanan->meja->nomor_meja }}</p>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ ucfirst($pesanan->status_pesanan) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <p>Belum ada riwayat pemesanan.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Paginasi -->
                                @if($riwayatPesanan instanceof \Illuminate\Pagination\LengthAwarePaginator && $riwayatPesanan->hasPages())
                                    <div class="mt-6">
                                        {{ $riwayatPesanan->links() }}
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Info untuk guest --}}
                            <div class="bg-gray-50 p-6 rounded-lg border text-center">
                                <i class="fas fa-info-circle fa-2x mb-2 text-gray-400"></i>
                                <h4 class="text-lg font-semibold text-gray-600 mb-2">Riwayat Pemesanan</h4>
                                <p class="text-gray-500 mb-3">Untuk melihat riwayat pemesanan, silakan login atau daftar akun terlebih dahulu.</p>
                                <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                    Daftar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pilih Meja -->
        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            @keydown.escape.window="isModalOpen = false" x-cloak>

            <div @click.outside="isModalOpen = false"
                class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="p-4 border-b flex justify-between items-center">
                    <h5 class="text-xl font-bold">Pilih Meja yang Tersedia</h5>
                    <button @click="isModalOpen = false" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @forelse ($daftarMejaTersedia as $meja)
                            <a href="{{ route('order.show', $meja->nomor_meja) }}"
                                class="block p-4 border rounded-lg text-center hover:bg-red-50 hover:border-red-500 transition-all duration-200">
                                <div class="text-3xl text-red-500 mb-2">
                                    <i class="fas fa-chair"></i>
                                </div>
                                <p class="font-bold text-lg text-gray-800">{{ $meja->nomor_meja }}</p>
                                <p class="text-sm text-gray-500">Kapasitas: {{ $meja->kapasitas }} orang</p>
                            </a>
                        @empty
                            <p class="col-span-full text-center text-gray-500">Tidak ada meja yang tersedia saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 border-t bg-gray-50 text-right rounded-b-lg">
                    <button @click="isModalOpen = false"
                        class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk polling status pesanan --}}
    @if ($pesananAktif)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pesananCard = document.getElementById('pesanan-aktif-card');
                const statusBadgeContainer = document.getElementById('status-badge-container');

                if (pesananCard) {
                    const pesananId = pesananCard.dataset.pesananId;
                    const fetchUrl = @json(route('api.customer.pesanan.status'));

                    const intervalId = setInterval(() => {
                        fetch(fetchUrl, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'found' && data.pesanan_id == pesananId) {
                                    // Update status badge
                                    statusBadgeContainer.innerHTML = data.status_html;
                                } else if (data.status === 'not_found') {
                                    // Pesanan selesai
                                    pesananCard.innerHTML = `
                                        <div class="text-center py-8 text-gray-500 bg-green-50 rounded-lg">
                                            <i class="fas fa-check-circle fa-2x mb-2 text-green-500"></i>
                                            <p>Pesanan Anda telah selesai!</p>
                                            @if($isGuest)
                                                <p class="text-sm mt-2">Terima kasih telah memesan. Silakan lakukan pembayaran di kasir.</p>
                                            @endif
                                        </div>
                                    `;
                                    clearInterval(intervalId);
                            })
                            .catch(error => {
                                console.error('Gagal memuat status pesanan:', error);
                            });
                    }, 5000);
                }
            });
        </script>
    @endif
</x-app-layout>
