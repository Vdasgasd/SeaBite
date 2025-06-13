<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-blue-500 text-3xl mr-4"></i>
                            <div>
                                <h3 class="text-2xl font-bold">Detail Reservasi</h3>
                                <p class="text-gray-600">Informasi lengkap reservasi pelanggan</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.reservasi.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Reservasi</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-user text-gray-400 mr-3"></i>
                                        <span class="text-gray-900">{{ $reservasi->nama_pelanggan }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-phone text-gray-400 mr-3"></i>
                                        <span class="text-gray-900">{{ $reservasi->telepon }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Orang</label>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-users text-gray-400 mr-3"></i>
                                        <span class="text-gray-900">{{ $reservasi->jumlah_tamu }} orang</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Reservasi</label>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-clock text-gray-400 mr-3"></i>
                                        <div>
                                            <span class="text-gray-900 block">{{ \Carbon\Carbon::parse($reservasi->waktu_reservasi)->format('d F Y, H:i') }}</span>
                                            <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($reservasi->waktu_reservasi)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meja</label>
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-chair text-gray-400 mr-3"></i>
                                        <div>
                                            @if($reservasi->meja)
                                                <span class="text-gray-900 block">Meja {{ $reservasi->meja->nomor_meja }}</span>
                                                <span class="text-gray-500 text-sm">Kapasitas: {{ $reservasi->meja->kapasitas }} orang</span>
                                            @else
                                                <span class="text-gray-500">Belum ditentukan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($reservasi->catatan)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">{{ $reservasi->catatan }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status & Actions -->
                <div class="lg:col-span-1">
                    <!-- Status Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Status Reservasi</h4>

                            <div class="text-center mb-4">
                                @switch($reservasi->status)
                                    @case('pending')
                                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg">
                                            <i class="fas fa-clock text-2xl mr-3"></i>
                                            <div>
                                                <p class="font-semibold">Pending</p>
                                                <p class="text-sm">Menunggu konfirmasi</p>
                                            </div>
                                        </div>
                                        @break
                                    @case('dikonfirmasi')
                                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                                            <i class="fas fa-check-circle text-2xl mr-3"></i>
                                            <div>
                                                <p class="font-semibold">Dikonfirmasi</p>
                                                <p class="text-sm">Reservasi dikonfirmasi</p>
                                            </div>
                                        </div>
                                        @break
                                    @case('hadir')
                                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                                            <i class="fas fa-user-check text-2xl mr-3"></i>
                                            <div>
                                                <p class="font-semibold">Hadir</p>
                                                <p class="text-sm">Pelanggan hadir</p>
                                            </div>
                                        </div>
                                        @break
                                    @case('batal')
                                        <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg">
                                            <i class="fas fa-times-circle text-2xl mr-3"></i>
                                            <div>
                                                <p class="font-semibold">Batal</p>
                                                <p class="text-sm">Reservasi dibatalkan</p>
                                            </div>
                                        </div>
                                        @break
                                @endswitch
                            </div>

                            <!-- Status Update Form -->
                            <form method="POST" action="{{ route('admin.reservasi.update', $reservasi) }}">
                                @csrf
                                @method('PUT')

                                <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status</label>
                                <select name="status" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-4">
                                    <option value="pending" {{ $reservasi->status === 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="dikonfirmasi" {{ $reservasi->status === 'dikonfirmasi' ? 'selected' : '' }}>
                                        Dikonfirmasi
                                    </option>
                                    <option value="hadir" {{ $reservasi->status === 'hadir' ? 'selected' : '' }}>
                                        Hadir
                                    </option>
                                    <option value="batal" {{ $reservasi->status === 'batal' ? 'selected' : '' }}>
                                        Batal
                                    </option>
                                </select>

                                <button type="submit"
                                        class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                                    <i class="fas fa-save mr-2"></i> Update Status
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h4>

                            <div class="space-y-3">
                                @if($reservasi->status === 'pending')
                                <form method="POST" action="{{ route('admin.reservasi.update', $reservasi) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="dikonfirmasi">
                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200">
                                        <i class="fas fa-check mr-2"></i> Konfirmasi Reservasi
                                    </button>
                                </form>
                                @endif

                                @if($reservasi->status === 'dikonfirmasi')
                                <form method="POST" action="{{ route('admin.reservasi.update', $reservasi) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="hadir">
                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                                        <i class="fas fa-user-check mr-2"></i> Tandai Hadir
                                    </button>
                                </form>
                                @endif

                                <form method="POST" action="{{ route('admin.reservasi.destroy', $reservasi) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus reservasi ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">
                                        <i class="fas fa-trash mr-2"></i> Hapus Reservasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline/History (Optional) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat pada</label>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-calendar-plus text-gray-400 mr-3"></i>
                                <span class="text-gray-900">{{ $reservasi->created_at->format('d F Y, H:i') }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Terakhir diupdate</label>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-calendar-edit text-gray-400 mr-3"></i>
                                <span class="text-gray-900">{{ $reservasi->updated_at->format('d F Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
