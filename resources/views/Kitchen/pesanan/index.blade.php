<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-list-ul mr-2"></i> {{ __('Daftar Pesanan') }}
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

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-clipboard-list text-blue-500 mr-3"></i>
                            Semua Pesanan
                            <span class="ml-2 bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                {{ $pesanan->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('kitchen.dashboard') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($pesanan as $item)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                                <!-- Header Card -->
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">
                                                Pesanan #{{ $item->pesanan_id }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-table mr-1"></i>
                                                Meja {{ $item->meja->nomor_meja }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @if($item->status_pesanan == 'antrian')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Antrian
                                                </span>
                                            @elseif($item->status_pesanan == 'diproses')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-fire mr-1"></i>Diproses
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Body Card -->
                                <div class="p-4">
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item->waktu_pesanan)->format('H:i, d M Y') }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-900">
                                            <i class="fas fa-money-bill mr-1"></i>
                                            Total: Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Menu:</p>
                                        <div class="space-y-1">
                                            @foreach ($item->detailPesanan as $detail)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">
                                                        {{ $detail->menu->nama_menu }}
                                                        @if($detail->metodeMasak)
                                                            <span class="text-xs text-blue-600">({{ $detail->metodeMasak->nama_metode }})</span>
                                                        @endif
                                                    </span>
                                                    <span class="font-medium">{{ $detail->jumlah }}x</span>
                                                </div>
                                                @if($detail->catatan)
                                                    <p class="text-xs text-gray-500 italic">Note: {{ $detail->catatan }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Card -->
                                <div class="px-4 py-3 bg-gray-50 rounded-b-lg">
                                    <div class="flex justify-between space-x-2">
                                        <a href="{{ route('kitchen.pesanan.show', $item) }}"
                                           class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>

                                        @if($item->status_pesanan == 'antrian')
                                            <form action="{{ route('kitchen.pesanan.updateStatus', $item) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status_pesanan" value="diproses">
                                                <button type="submit"
                                                        class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-3 rounded text-sm font-medium">
                                                    <i class="fas fa-play mr-1"></i>Mulai
                                                </button>
                                            </form>
                                        @elseif($item->status_pesanan == 'diproses')
                                            <form action="{{ route('kitchen.pesanan.markAsReady', $item) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="w-full bg-orange-500 hover:bg-orange-700 text-white py-2 px-3 rounded text-sm font-medium">
                                                    <i class="fas fa-check mr-1"></i>Selesai
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Tidak ada pesanan yang perlu diproses.</p>
                                <p class="text-gray-400 text-sm mt-2">Pesanan baru akan muncul di sini secara otomatis.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
