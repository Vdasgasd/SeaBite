<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kasir') }}
        </h2>
    </x-slot>
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

                    <!-- Info Pesanan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Informasi Pesanan</h3>
                            <div class="space-y-2">
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

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Total Pembayaran</h3>
                            <div class="text-3xl font-bold text-green-600">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </div>

                            @if ($pesanan->status_pesanan == 'selesai')
                                <form action="{{ route('kasir.invoice.store') }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="pesanan_id" value="{{ $pesanan->pesanan_id }}">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode
                                            Pembayaran</label>
                                        <select name="metode_pembayaran" class="w-full border rounded px-3 py-2"
                                            required>
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option value="tunai">Tunai</option>
                                            <option value="kartu_debit">Kartu Debit</option>
                                            <option value="kartu_kredit">Kartu Kredit</option>
                                        </select>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Proses Pembayaran
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Menu -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Detail Menu</h3>
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Menu</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Metode Masak</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah/Berat</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Catatan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pesanan->detailPesanan as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $detail->menu->nama_menu }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $detail->metodeMasak->nama_metode ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if ($detail->jumlah)
                                                    {{ $detail->jumlah }} porsi
                                                @else
                                                    {{ $detail->berat_gram }}g
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $detail->catatan ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                            Total:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Aksi -->
                    <div class="flex justify-end space-x-3">
                        @if ($pesanan->status_pesanan == 'antrian')
                            <form action="{{ route('kasir.pesanan.destroy', $pesanan) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
