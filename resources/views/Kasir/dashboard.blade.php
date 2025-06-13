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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-gray-600 font-medium">Total Reservasi (Hari Ini)</h4>
                        <p class="text-2xl font-bold">{{ $totalReservasiHariIni }}</p>
                    </div>
                </div>
            </div>
<div class="flex justify-end mb-4">
    <a href="{{ route('kasir.invoice.index') }}"
       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
        Lihat Semua Invoice
    </a>
</div>

            {{-- Tabs --}}
            <div class="mb-4 border-b border-gray-200">
                <nav class="flex space-x-8 -mb-px" aria-label="Tabs">
                    {{-- REMOVED onclick and added data-tab attribute --}}
                    <button data-tab="pesanan" id="tab-pesanan"
                        class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Pesanan Siap Bayar
                    </button>
                    {{-- REMOVED onclick and added data-tab attribute --}}
                    <button data-tab="reservasi" id="tab-reservasi"
                        class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Reservasi Aktif
                    </button>
                </nav>
            </div>

            {{-- Tab Content: Pesanan --}}
            <div id="content-pesanan" class="tab-content">
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
                                            <h4 class="font-medium text-gray-900">Pesanan #{{ $pesanan->pesanan_id }}
                                            </h4>
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
                                        {{-- Note: This button should call the JavaScript modal function --}}
                                        <button
                                            onclick="openPaymentModal('{{ $pesanan->pesanan_id }}', '{{ $pesanan->total_harga }}')"
                                            class="text-blue-600 hover:text-blue-900">
                                            Proses Pembayaran
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada pesanan yang perlu dibayar saat ini.
                                </p>
                            @endforelse
                        </div>
                        <div class="mt-6">
                            {{ $pesananSelesai->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Content: Reservasi --}}
            <div id="content-reservasi" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Reservasi Aktif (Menunggu & Dikonfirmasi)</h3>
                            <a href="{{ route('kasir.reservasi.index') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Lihat Semua Reservasi
                            </a>
                        </div>
                        <div class="space-y-4">
                            @php
                                $statusColor = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                    'hadir' => 'bg-green-100 text-green-800',
                                    'batal' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            @forelse ($reservasiAktif as $reservasi)
                                <div class="border rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $reservasi->nama_pelanggan }}</h4>
                                            <p class="text-sm text-gray-500">Meja: {{ $reservasi->meja->nomor_meja }}
                                            </p>
                                            <p class="text-sm text-gray-500">Telepon: {{ $reservasi->telepon }}</p>
                                            <p class="text-sm text-gray-500">Waktu:
                                                {{ \Carbon\Carbon::parse($reservasi->waktu_reservasi)->translatedFormat('d F Y H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-500">Tamu: {{ $reservasi->jumlah_tamu }} orang
                                            </p>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor[$reservasi->status] ?? 'bg-gray-100 text-gray-800' }} mt-2 capitalize">
                                                {{ $reservasi->status }}
                                            </span>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if ($reservasi->status === 'menunggu')
                                                <form
                                                    action="{{ route('kasir.reservasi.update', $reservasi->reservasi_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="dikonfirmasi">
                                                    <button type="submit"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">Konfirmasi</button>
                                                </form>
                                            @endif
                                            <form
                                                action="{{ route('kasir.reservasi.update', $reservasi->reservasi_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="hadir">
                                                <button type="submit"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">Hadir</button>
                                            </form>
                                            <form
                                                action="{{ route('kasir.reservasi.update', $reservasi->reservasi_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="batal">
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">Batalkan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Tidak ada reservasi yang aktif.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Modal --}}
    <div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Proses Pembayaran</h3>
                    <button onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>
                <form id="payment-form" action="{{ route('kasir.invoice.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form-pesanan-id" name="pesanan_id">
                    <div class="mb-4">
                        <p class="block text-gray-700 text-sm font-bold mb-2">
                            Pesanan ID: <span id="payment-pesanan-id" class="font-normal"></span>
                        </p>
                        <p class="block text-gray-700 text-sm font-bold mb-2">
                            Total: Rp <span id="payment-total" class="font-normal"></span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <label for="metode_pembayaran" class="block text-gray-700 text-sm font-bold mb-2">Metode
                            Pembayaran</label>
                        <select id="metode_pembayaran" name="metode_pembayaran"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="tunai">Tunai</option>
                            <option value="kartu_debit">Kartu Debit</option>
                            <option value="kartu_kredit">Kartu Kredit</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closePaymentModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Buat Invoice
                            & Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // All functions are now defined before they are attached to events.

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            // Add active class to selected tab button
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        // Payment modal functions
        function openPaymentModal(pesananId, total) {
            document.getElementById('form-pesanan-id').value = pesananId;
            document.getElementById('payment-pesanan-id').textContent = pesananId;
            document.getElementById('payment-total').textContent = new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('payment-modal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('payment-modal').classList.add('hidden');
        }

        // Wait for the DOM to be fully loaded before running scripts
        document.addEventListener('DOMContentLoaded', function() {
            // Set the initial tab
            showTab('pesanan');

            // Add click event listeners to all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', () => {
                    const tabName = button.dataset.tab; // Get tab name from data attribute
                    showTab(tabName);
                });
            });
        });
    </script>
    <style>
        .tab-button {
            border-color: transparent;
            color: #6b7280;
            /* gray-500 */
        }

        .tab-button:hover {
            color: #374151;
            /* gray-700 */
            border-color: #d1d5db;
            /* gray-300 */
        }

        .tab-button.active {
            color: #3b82f6;
            /* blue-500 */
            border-color: #3b82f6;
            /* blue-500 */
        }
    </style>

</x-app-layout>
