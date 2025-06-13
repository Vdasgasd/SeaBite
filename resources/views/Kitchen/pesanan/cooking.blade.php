<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-fire mr-2"></i> {{ __('Pesanan Sedang Dimasak') }}
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
                            <i class="fas fa-fire-burner text-orange-500 mr-3"></i>
                            Sedang Dimasak
                            <span class="ml-2 bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                {{ $pesanan->count() }}
                            </span>
                        </h3>
                        <div class="flex space-x-3">
                            <a href="{{ route('kitchen.pesanan.index') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-list mr-2"></i>Semua Pesanan
                            </a>
                            <a href="{{ route('kitchen.dashboard') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse ($pesanan as $item)
                            <div class="bg-white border-2 border-orange-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:border-orange-300">
                                <!-- Header Card -->
                                <div class="p-4 bg-orange-50 border-b border-orange-200">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-fire mr-1"></i>Dimasak
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($item->waktu_pesanan)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Body Card -->
                                <div class="p-4">
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-clock mr-1"></i>
                                            Mulai: {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }}
                                        </p>
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-stopwatch mr-1 text-orange-500"></i>
                                            <span class="text-sm font-medium text-orange-700">
                                                Durasi: {{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans(null, true) }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            <i class="fas fa-money-bill mr-1"></i>
                                            Total: Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Progress Indicator -->
                                    <div class="mb-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-orange-500 h-2 rounded-full animate-pulse" style="width: 65%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 text-center">Sedang diproses...</p>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Menu yang sedang dimasak:</p>
                                        <div class="space-y-2">
                                            @foreach ($item->detailPesanan as $detail)
                                                <div class="bg-gray-50 p-2 rounded border-l-4 border-orange-300">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium text-gray-900">{{ $detail->menu->nama_menu }}</p>
                                                            @if($detail->metodeMasak)
                                                                <p class="text-xs text-orange-600">{{ $detail->metodeMasak->nama_metode }}</p>
                                                            @endif
                                                            @if($detail->catatan)
                                                                <p class="text-xs text-red-600 italic mt-1">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $detail->catatan }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-sm font-bold text-orange-700">{{ $detail->jumlah }}x</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Estimated Time -->
                                    <div class="mb-4 p-2 bg-blue-50 rounded border border-blue-200">
                                        <p class="text-xs text-blue-700">
                                            <i class="fas fa-clock mr-1"></i>
                                            Estimasi selesai: {{ $item->detailPesanan->sum('jumlah') * 3 }} menit lagi
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer Card -->
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('kitchen.pesanan.show', $item) }}"
                                           class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>

                                        <form action="{{ route('kitchen.pesanan.markAsReady', $item) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full bg-green-500 hover:bg-green-700 text-white py-2 px-3 rounded text-sm font-medium transition duration-200">
                                                <i class="fas fa-check mr-1"></i>Selesai
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-fire-flame-simple text-gray-400 text-6xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Tidak ada pesanan yang sedang dimasak.</p>
                                <p class="text-gray-400 text-sm mt-2">Mulai memasak pesanan dari antrian untuk melihatnya di sini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($pesanan->count() > 0)
                        <!-- Summary Stats -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                                <div class="flex items-center">
                                    <i class="fas fa-fire text-orange-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Dimasak</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $pesanan->count() }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="flex items-center">
                                    <i class="fas fa-utensils text-blue-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Item</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $pesanan->sum(function($p) { return $p->detailPesanan->sum('jumlah'); }) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill text-green-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Nilai</p>
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($pesanan->sum('total_harga'), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-yellow-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Rata-rata Durasi</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ round($pesanan->avg(function($p) { return \Carbon\Carbon::parse($p->updated_at)->diffInMinutes(now()); })) }} menit
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
