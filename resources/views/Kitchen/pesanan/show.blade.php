<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-receipt mr-2"></i> Detail Pesanan #{{ $pesanan->pesanan_id }}
            </h2>
            <a href="{{ route('kitchen.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-200 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div class="p-6 sm:px-8 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Meja {{ $pesanan->meja->nomor_meja }}</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Waktu Pesan: {{ $pesanan->waktu_pesanan->format('d M Y, H:i:s') }}
                            </p>
                        </div>
                        <div>
                            @if ($pesanan->status_pesanan == 'antrian')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-base font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-hourglass-start mr-2"></i> Menunggu
                                </span>
                            @elseif ($pesanan->status_pesanan == 'diproses')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-base font-medium bg-yellow-100 text-yellow-800">
                                   <i class="fas fa-sync fa-spin mr-2"></i> Sedang Diproses
                                </span>
                            @elseif ($pesanan->status_pesanan == 'selesai')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-base font-medium bg-green-100 text-green-800">
                                   <i class="fas fa-check-circle mr-2"></i> Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:px-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Item yang Dipesan:</h4>
                    <div class="space-y-4">
                        @foreach ($pesanan->detailPesanan as $detail)
                            <div class="p-4 border rounded-lg flex items-start space-x-4">
                                <div class="font-bold text-lg text-blue-600 bg-blue-50 rounded-full h-10 w-10 flex items-center justify-center">
                                    {{ $detail->jumlah }}x
                                </div>
                                <div class="flex-grow">
                                    <p class="font-semibold text-gray-900">{{ $detail->menu->nama_menu }}</p>
                                    @if ($detail->metodeMasak)
                                        <p class="text-sm text-indigo-700">
                                            Metode: {{ $detail->metodeMasak->nama_metode }}
                                        </p>
                                    @endif
                                    @if ($detail->catatan)
                                        <p class="mt-1 text-sm text-red-600 italic">
                                            Catatan: {{ $detail->catatan }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($pesanan->status_pesanan != 'selesai')
                <div class="p-6 sm:px-8 bg-gray-50 border-t border-gray-200 flex justify-end items-center space-x-3">
                    @if ($pesanan->status_pesanan == 'antrian')
                        <form action="{{ route('kitchen.pesanan.updateStatus', $pesanan->pesanan_id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pesanan" value="diproses">
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-200 ease-in-out">
                                <i class="fas fa-cogs mr-2"></i> Mulai Proses Pesanan
                            </button>
                        </form>
                    @elseif ($pesanan->status_pesanan == 'diproses')
                         <form action="{{ route('kitchen.pesanan.updateStatus', $pesanan->pesanan_id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pesanan" value="selesai">
                             <button type="submit" class="px-5 py-2.5 bg-green-600 text-white font-bold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75 transition duration-200 ease-in-out">
                                <i class="fas fa-check-circle mr-2"></i> Tandai Selesai Dimasak
                            </button>
                        </form>
                    @endif
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
