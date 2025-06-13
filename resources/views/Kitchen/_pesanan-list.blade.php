<div class="space-y-6">
    @forelse ($pesananMasuk as $pesanan)
        <div id="pesanan-{{ $pesanan->pesanan_id }}"
            class="bg-white shadow-lg rounded-xl overflow-hidden border {{ $pesanan->status_pesanan == 'diproses' ? 'border-yellow-400' : 'border-gray-200' }}">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <a href="{{ route('kitchen.pesanan.show', $pesanan->pesanan_id) }}"
                        class="hover:text-blue-600 hover:underline">
                        <h4 class="text-xl font-bold text-gray-800">Pesanan #{{ $pesanan->pesanan_id }}</h4>
                    </a>
                    <p class="text-sm text-gray-600">Meja: {{ $pesanan->meja->nomor_meja }} &bull;
                        {{ $pesanan->waktu_pesanan->diffForHumans() }}</p>
                </div>
                <div>
                    @if ($pesanan->status_pesanan == 'antrian')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-hourglass-start mr-2"></i> Menunggu
                        </span>
                    @elseif ($pesanan->status_pesanan == 'diproses')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-sync fa-spin mr-2"></i> Sedang Diproses
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($pesanan->detailPesanan as $detail)
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="font-semibold text-gray-900">{{ $detail->menu->nama_menu }}</p>
                            <p class="text-sm text-gray-700">Jumlah: <span
                                    class="font-bold">{{ $detail->jumlah }}x</span></p>
                            @if ($detail->metodeMasak)
                                <p
                                    class="text-xs text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-full inline-block mt-1">
                                    {{ $detail->metodeMasak->nama_metode }}
                                </p>
                            @endif
                            @if ($detail->catatan)
                                <p class="text-xs text-red-700 mt-2">
                                    <i class="fas fa-quote-left mr-1"></i>
                                    <em>{{ $detail->catatan }}</em>
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center space-x-3">
                @if ($pesanan->status_pesanan == 'antrian')
                    <form action="{{ route('kitchen.pesanan.updateStatus', $pesanan->pesanan_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_pesanan" value="diproses">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-200 ease-in-out">
                            <i class="fas fa-cogs mr-2"></i> Proses Pesanan
                        </button>
                    </form>
                @elseif ($pesanan->status_pesanan == 'diproses')
                    <form action="{{ route('kitchen.pesanan.updateStatus', $pesanan->pesanan_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_pesanan" value="selesai">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75 transition duration-200 ease-in-out">
                            <i class="fas fa-check-circle mr-2"></i> Selesaikan Pesanan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-12 bg-white shadow-sm sm:rounded-lg">
            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
            <h4 class="text-xl font-semibold text-gray-700">Tidak Ada Pesanan Aktif</h4>
            <p class="text-gray-500 mt-1">Semua pesanan sudah selesai dimasak. Kerja bagus! 👍</p>
        </div>
    @endforelse
</div>
