<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Reservasi Anda') }}
            </h2>
            <a href="{{ route('customer.reservasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                + Buat Reservasi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200 text-green-800" role="alert">
                           <p><span class="font-bold">Berhasil!</span> {{ session('success') }}</p>
                        </div>
                    @endif

                    @if($reservasi->isEmpty())
                         <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-lg border">
                            <i class="far fa-calendar-alt fa-3x mb-3 text-gray-400"></i>
                            <h4 class="text-lg font-semibold text-gray-600 mb-2">Anda Belum Punya Reservasi</h4>
                            <p>Sepertinya Anda belum pernah melakukan reservasi meja.</p>
                            <a href="{{ route('customer.reservasi.create') }}" class="mt-4 inline-block bg-red-500 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:bg-red-600 transition">
                                Buat Reservasi Sekarang
                            </a>
                        </div>
                    @else
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 rounded-l-lg">
                                            Waktu Reservasi
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Detail
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 rounded-r-lg">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservasi as $item)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <div>{{ \Carbon\Carbon::parse($item->waktu_reservasi)->isoFormat('dddd, D MMM Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->waktu_reservasi)->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700">
                                            <div>Meja {{ $item->meja->nomor_meja ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->jumlah_tamu }} orang</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($item->status == 'dipesan') bg-blue-100 text-blue-800
                                                @elseif($item->status == 'selesai') bg-green-100 text-green-800
                                                @elseif($item->status == 'batal') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('customer.reservasi.show', $item) }}" class="font-medium text-blue-600 hover:underline">Lihat Detail</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
