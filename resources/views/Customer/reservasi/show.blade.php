<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Reservasi Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200 text-green-800" role="alert">
                           <p><span class="font-bold">Berhasil!</span> {{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between items-start mb-4 pb-4 border-b">
                        <div>
                            <p class="text-sm text-gray-500">Reservasi #{{ $reservasi->id }}</p>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Meja {{ $reservasi->meja->nomor_meja ?? 'N/A' }}
                            </h3>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if($reservasi->status == 'dipesan') bg-blue-100 text-blue-800
                            @elseif($reservasi->status == 'selesai') bg-green-100 text-green-800
                            @elseif($reservasi->status == 'batal') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($reservasi->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                        {{-- Sisi Kiri: Waktu & Tamu --}}
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Detail Jadwal</h4>
                            <dl>
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-calendar-alt text-blue-500 text-2xl mr-4 w-8 text-center"></i>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                                        <dd class="text-md font-semibold text-gray-900">{{ \Carbon\Carbon::parse($reservasi->waktu_reservasi)->isoFormat('dddd, D MMMM Y') }}</dd>
                                    </div>
                                </div>
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-clock text-blue-500 text-2xl mr-4 w-8 text-center"></i>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                                        <dd class="text-md font-semibold text-gray-900">{{ \Carbon\Carbon::parse($reservasi->waktu_reservasi)->format('H:i') }} WIB</dd>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-users text-blue-500 text-2xl mr-4 w-8 text-center"></i>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Jumlah Tamu</dt>
                                        <dd class="text-md font-semibold text-gray-900">{{ $reservasi->jumlah_tamu }} orang</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                        {{-- Sisi Kanan: Info Pelanggan --}}
                        <div class="bg-gray-50 p-6 rounded-lg border">
                            <h4 class="text-lg font-semibold mb-4">Data Pemesan</h4>
                             <dl>
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-user text-gray-500 text-2xl mr-4 w-8 text-center"></i>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                        <dd class="text-md font-semibold text-gray-900">{{ $reservasi->nama_pelanggan }}</dd>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-500 text-2xl mr-4 w-8 text-center"></i>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                                        <dd class="text-md font-semibold text-gray-900">{{ $reservasi->telepon }}</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <a href="{{ route('customer.reservasi.index') }}" class="font-semibold text-blue-600 hover:underline">
                            &larr; Kembali ke Riwayat Reservasi
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
