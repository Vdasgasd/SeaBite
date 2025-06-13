<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(!request('print'))
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Invoice #INV-{{ $invoice->invoice_id }}</h2>
                    <div class="space-x-2">
                        <a href="{{ route('kasir.invoice.show', $invoice) }}?print=1" target="_blank" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Cetak Invoice
                        </a>
                        <a href="{{ route('kasir.invoice.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Invoice Content -->
                <div class="@if(request('print')) print-content @endif">
                    <!-- Header Invoice -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold">SeaBite Resto</h1>
                        <p class="text-gray-600 mt-2"> Jl. Teuku Umar, Menteng, Jakarta Pusat</p>
                        <p class="text-gray-600">Telp: (021) 1234567</p>
                    </div>

                    <!-- Info Invoice -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Detail Invoice</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Invoice:</span>
                                    <span class="font-medium">#INV-{{ $invoice->invoice_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Pesanan:</span>
                                    <span class="font-medium">#{{ $invoice->pesanan->pesanan_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal:</span>
                                    <span class="font-medium">{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kasir:</span>
                                    <span class="font-medium">{{ $invoice->kasir->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-3">Detail Meja</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Meja:</span>
                                    <span class="font-medium">{{ $invoice->pesanan->meja->nomor_meja }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu Pesanan:</span>
                                    <span class="font-medium">{{ $invoice->pesanan->waktu_pesanan->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Metode Pembayaran:</span>
                                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $invoice->metode_pembayaran)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pesanan -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Detail Pesanan</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Masak</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty/Berat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoice->pesanan->detailPesanan as $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $detail->menu->nama_menu }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $detail->metodeMasak->nama_metode ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($detail->jumlah)
                                                {{ $detail->jumlah }} porsi
                                            @else
                                                {{ $detail->berat_gram }}g
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $detail->catatan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Total Pembayaran -->
                    <div class="border-t-2 border-gray-300 pt-4">
                        <div class="flex justify-end">
                            <div class="w-1/3">
                                <div class="flex justify-between py-2">
                                    <span class="text-lg font-semibold">Total:</span>
                                    <span class="text-lg font-bold">Rp {{ number_format($invoice->total_bayar, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-t">
                                    <span class="text-lg font-semibold">Metode Pembayaran:</span>
                                    <span class="text-lg font-medium">{{ ucfirst(str_replace('_', ' ', $invoice->metode_pembayaran)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-8 pt-4 border-t">
                        <p class="text-gray-600">Terima kasih atas kunjungan Anda!</p>
                        <p class="text-gray-600 text-sm mt-2">Invoice ini adalah bukti pembayaran yang sah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(request('print'))
<style>
    @media print {
        .print-content {
            width: 100%;
            margin: 0;
            padding: 20px;
        }

        body * {
            visibility: hidden;
        }

        .print-content, .print-content * {
            visibility: visible;
        }

        .print-content {
            position: absolute;
            left: 0;
            top: 0;
        }

        @page {
            margin: 0.5in;
        }
    }
</style>

<script>
window.onload = function() {
    window.print();
    window.onafterprint = function() {
        window.close();
    }
}
</script>
@endif
</x-app-layout>
