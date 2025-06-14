<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->


            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Welcome Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-user-shield text-blue-500 text-3xl mr-4"></i>
                            <div>
                                <h3 class="text-2xl font-bold">Welcome, {{ $user->name }}!</h3>
                                <p class="text-gray-600">Administrator Dashboard - SeaBite Resto</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                            <i class="fas fa-money-bill-wave text-green-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Total Pendapatan</h3>
                            <p class="text-2xl font-bold text-green-700 mt-1">
                                {{ number_format($totalPendapatan, 0, ',', '.') }}
                                <span class="text-sm text-gray-500">IDR</span>
                            </p>
                        </div>
                    </div>
                </div>


                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-utensils text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium truncate opacity-90">Total Menu</dt>
                                        <dd class="text-2xl font-bold">{{ $totalMenu }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-fish text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium truncate opacity-90">Jenis Ikan</dt>
                                        <dd class="text-2xl font-bold">{{ $totalIkan }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chair text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium truncate opacity-90">Total Meja</dt>
                                        <dd class="text-2xl font-bold">{{ $totalMeja }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 overflow-hidden shadow-lg rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium truncate opacity-90">Total Users</dt>
                                        <dd class="text-2xl font-bold">{{ $totalUser }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <a href="{{ route('admin.reservasi.index') }}"
                                class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition duration-200">
                                <i class="fas fa-calendar-check text-yellow-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Manager Reservasi</h4>
                                    <p class="text-sm text-gray-600">Lihat semua reservasi</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.menu.index') }}"
                                class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                                <i class="fas fa-utensils text-blue-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Manage Menu</h4>
                                    <p class="text-sm text-gray-600">Add, edit, or remove menu items</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.ikan.index') }}"
                                class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                                <i class="fas fa-fish text-green-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Manage Fish</h4>
                                    <p class="text-sm text-gray-600">Manage fish inventory</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.kategori.index') }}"
                                class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-200">
                                <i class="fas fa-list text-purple-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Categories</h4>
                                    <p class="text-sm text-gray-600">Manage menu categories</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.metode-masak.index') }}"
                                class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition duration-200">
                                <i class="fas fa-fire text-orange-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Cooking Methods</h4>
                                    <p class="text-sm text-gray-600">Manage cooking methods</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.meja.index') }}"
                                class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition duration-200">
                                <i class="fas fa-chair text-red-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Table Management</h4>
                                    <p class="text-sm text-gray-600">Manage restaurant tables</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.user.index') }}"
                                class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-200">
                                <i class="fas fa-users text-indigo-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">User Management</h4>
                                    <p class="text-sm text-gray-600">Manage system users</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.laporan.penjualan') }}"
                                class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition duration-200">
                                <i class="fas fa-chart-line text-yellow-500 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Laporan Penjualan</h4>
                                    <p class="text-sm text-gray-600">Lihat riwayat transaksi dan pendapatan</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</x-app-layout>
