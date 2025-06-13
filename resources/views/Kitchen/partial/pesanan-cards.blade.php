@forelse ($pesananMasuk as $pesanan)
    <div
        class="pesanan-card bg-white border-2 border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h4 class="text-xl font-bold text-gray-900">
                    Pesanan #{{ $pesanan->pesanan_id }}
                </h4>
                <p class="text-gray-600">
                    <i class="fas fa-chair mr-1"></i>
                    Meja {{ $pesanan->meja->nomor_meja }}
                </p>
                <p class="text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $pesanan->waktu_pesanan->format('H:i:s') }}
                </p>
            </div>
            <div class="flex flex-col items-end">
                @if ($pesanan->status_pesanan === 'antrian')
                    <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">
                        <i class="fas fa-clock mr-1"></i>
                        Antrian
                    </span>
                @elseif($pesanan->status_pesanan === 'diproses')
                    <span class="bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                        <i class="fas fa-fire mr-1"></i>
                        Sedang Dimasak
                    </span>
                @endif
                <p class="text-xs text-gray-500 mt-2">
                    {{ $pesanan->waktu_pesanan->diffForHumans() }}
                </p>
            </div>
        </div>

        <!-- Detail Menu -->
        <div class="space-y-3 mb-6">
            <h5 class="font-semibold text-gray-800 border-b pb-2">Detail Pesanan:</h5>
            @foreach ($pesanan->detailPesanan as $detail)
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $detail->menu->nama_menu }}</p>
                        @if ($detail->metodeMasak)
                            <p class="text-sm text-blue-600">
                                <i class="fas fa-utensils mr-1"></i>
                                {{ $detail->metodeMasak->nama_metode }}
                            </p>
                        @endif
                        @if ($detail->catatan)
                            <p class="text-sm text-gray-600 italic">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ $detail->catatan }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-2.5 py-1 rounded">
                            {{ $detail->jumlah }}x
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            @if ($pesanan->status_pesanan === 'antrian')
                <button type="button" onclick="updateStatusPesanan({{ $pesanan->pesanan_id }}, 'diproses')"
                    class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-fire mr-2"></i>
                    Mulai Memasak
                </button>
            @elseif($pesanan->status_pesanan === 'diproses')
                <button type="button" onclick="updateStatusPesanan({{ $pesanan->pesanan_id }}, 'selesai')"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-check mr-2"></i>
                    Selesai Dimasak
                </button>
            @endif
            <button type="button" onclick="showDetailPesanan({{ $pesanan->pesanan_id }})"
                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
@empty
    <div class="text-center py-12">
        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-medium text-gray-600 mb-2">Tidak ada pesanan</h3>
        <p class="text-gray-500">Semua pesanan sudah selesai diproses.</p>
    </div>
@endforelse

<script>
    function updateStatusPesanan(pesananId, status) {
        // Confirmation dialog
        const message = status === 'diproses' ?
            'Mulai memasak pesanan ini?' :
            'Tandai pesanan ini sebagai selesai dimasak?';

        if (!confirm(message)) {
            return;
        }

        // CORRECT: The fetch URL must target the specific pesanan status route.
        fetch(`/kitchen/pesanan/${pesananId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status_pesanan: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Use the custom notification function if available
                    if (typeof showNotification === 'function') {
                        showNotification('success', data.message);
                    } else {
                        alert(data.message);
                    }
                    // Reload the page to see the changes
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // Handle potential validation errors or other failures
                    const errorMessage = data.message || 'Gagal mengubah status pesanan.';
                    if (typeof showNotification === 'function') {
                        showNotification('error', errorMessage);
                    } else {
                        alert(errorMessage);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showNotification === 'function') {
                    showNotification('error', 'Terjadi kesalahan saat berkomunikasi dengan server.');
                } else {
                    alert('Terjadi kesalahan saat berkomunikasi dengan server.');
                }
            });
    }

    function showDetailPesanan(pesananId) {
        // Tampilkan modal
        const modal = document.getElementById('detailPesananModal');
        const loading = document.getElementById('modalLoading');
        const content = document.getElementById('modalContent');
        const error = document.getElementById('modalError');
        const footer = document.getElementById('modalFooter');

        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        content.classList.add('hidden');
        error.classList.add('hidden');
        footer.classList.add('hidden');

        // Fetch detail pesanan
        fetch(`/kitchen/pesanan/${pesananId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Sembunyikan loading
                loading.classList.add('hidden');

                // Update content
                updateModalContent(data);

                // Tampilkan content dan footer
                content.classList.remove('hidden');
                footer.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);

                // Sembunyikan loading
                loading.classList.add('hidden');

                // Tampilkan error
                error.classList.remove('hidden');
            });
    }

    // Function untuk menampilkan notifikasi
    function showNotification(type, message) {
        // Buat elemen notifikasi
        const notification = document.createElement('div');
        notification.className =
            `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
        } else {
            notification.classList.add('bg-red-500', 'text-white');
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
        }

        document.body.appendChild(notification);

        // Animasi masuk
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);

        // Auto hide setelah 3 detik
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>
