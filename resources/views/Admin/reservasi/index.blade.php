<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-blue-500 text-3xl mr-4"></i>
                            <div>
                                <h3 class="text-2xl font-bold">Manajemen Reservasi</h3>
                                <p class="text-gray-600">Kelola semua reservasi pelanggan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Reservasi</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $reservasi->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('admin.reservasi.index') }}"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-200">
                    <div class="p-4 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-list text-xl mr-3"></i>
                            <div>
                                <p class="text-sm opacity-90">Semua</p>
                                <p class="text-lg font-bold">{{ $reservasi->total() }}</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reservasi.index', ['status' => 'pending']) }}"
                    class="bg-gradient-to-r from-orange-500 to-orange-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-200">
                    <div class="p-4 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-xl mr-3"></i>
                            <div>
                                <p class="text-sm opacity-90">Pending</p>
                                <p class="text-lg font-bold">{{ $reservasi->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reservasi.index', ['status' => 'dikonfirmasi']) }}"
                    class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-200">
                    <div class="p-4 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-check text-xl mr-3"></i>
                            <div>
                                <p class="text-sm opacity-90">Dikonfirmasi</p>
                                <p class="text-lg font-bold">{{ $reservasi->where('status', 'dikonfirmasi')->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reservasi.index', ['status' => 'hadir']) }}"
                    class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition duration-200">
                    <div class="p-4 text-white">
                        <div class="flex items-center">
                            <i class="fas fa-user-check text-xl mr-3"></i>
                            <div>
                                <p class="text-sm opacity-90">Hadir</p>
                                <p class="text-lg font-bold">{{ $reservasi->where('status', 'hadir')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Reservasi Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Reservasi</h3>
                        <div class="flex space-x-2">
                            <!-- Search Form -->
                            <form method="GET" class="flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama atau nomor telepon..."
                                    class="px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelanggan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu Reservasi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Meja
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Orang
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservasi as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->nama_pelanggan }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->telepon }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($item->waktu_reservasi)->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($item->waktu_reservasi)->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $item->meja ? $item->meja->nomor_meja : 'Belum ditentukan' }}
                                            </div>
                                            @if ($item->meja)
                                                <div class="text-sm text-gray-500">
                                                    Kapasitas: {{ $item->meja->kapasitas }} orang
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->jumlah_tamu }} orang
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @switch($item->status)
                                                @case('pending')
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i> Pending
                                                    </span>
                                                @break

                                                @case('dikonfirmasi')
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i> Dikonfirmasi
                                                    </span>
                                                @break

                                                @case('hadir')
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <i class="fas fa-user-check mr-1"></i> Hadir
                                                    </span>
                                                @break

                                                @case('batal')
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-times mr-1"></i> Batal
                                                    </span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.reservasi.show', $item) }}"
                                                class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition duration-200">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>

                                            @if ($item->status === 'pending')
                                                <form method="POST"
                                                    action="{{ route('admin.reservasi.update', $item) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="dikonfirmasi">
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition duration-200">
                                                        <i class="fas fa-check mr-1"></i> Konfirmasi
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST"
                                                action="{{ route('admin.reservasi.destroy', $item) }}" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus reservasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition duration-200">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                                                    <p class="text-gray-500 text-lg">Tidak ada reservasi</p>
                                                    <p class="text-gray-400">Belum ada reservasi yang dibuat</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($reservasi->hasPages())
                            <div class="mt-6">
                                {{ $reservasi->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
