<div id="detailPesananModal"
    class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 transition-opacity"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                        <i class="fas fa-receipt mr-2"></i> Detail Pesanan
                    </h3>
                    <button onclick="closeModal()" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <i class="fas fa-times fa-lg"></i>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="mt-4">
                    <div id="modalLoading" class="text-center py-10">
                        <i class="fas fa-spinner fa-spin fa-3x text-blue-500"></i>
                        <p class="mt-2 text-gray-600">Memuat detail pesanan...</p>
                    </div>

                    <div id="modalError" class="hidden text-center py-10">
                        <i class="fas fa-exclamation-triangle fa-3x text-red-500"></i>
                        <p class="mt-2 text-red-600">Gagal memuat detail pesanan. Silakan coba lagi.</p>
                    </div>

                    <div id="modalContent" class="hidden space-y-4">
                    </div>
                </div>
            </div>

            <div id="modalFooter" class="hidden bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="window.print()" type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
                <button onclick="closeModal()" type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('detailPesananModal');

    function closeModal() {
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Function to update the modal content dynamically
    function updateModalContent(data) {
        const contentDiv = document.getElementById('modalContent');
        let detailsHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <p><strong>ID Pesanan:</strong> #${data.pesanan_id}</p>
                <p><strong>Meja:</strong> ${data.meja.nomor_meja}</p>
                <p><strong>Waktu Pesanan:</strong> ${new Date(data.waktu_pesanan).toLocaleString()}</p>
                <p><strong>Status:</strong> <span class="font-semibold">${data.status_pesanan}</span></p>
            </div>
            <hr class="my-3">
            <h4 class="font-bold text-lg mb-2">Item Pesanan:</h4>
            <div class="space-y-3">
        `;

        data.detail_pesanan.forEach(detail => {
            detailsHtml += `
                <div class="p-3 bg-gray-50 rounded-lg border">
                    <div class="flex justify-between items-center">
                        <p class="font-semibold text-gray-800">${detail.menu.nama_menu}</p>
                        <span class="bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded">${detail.jumlah}x</span>
                    </div>
                    ${detail.metode_masak ? `<p class="text-xs text-blue-600"><i class="fas fa-utensils mr-1"></i> ${detail.metode_masak.nama_metode}</p>` : ''}
                    ${detail.catatan ? `<p class="text-xs text-yellow-800 italic bg-yellow-100 p-1 rounded mt-1"><i class="fas fa-sticky-note mr-1"></i> ${detail.catatan}</p>` : ''}
                </div>
            `;
        });

        detailsHtml += `
            </div>
            <hr class="my-3">
            <div class="text-right">
                <p class="text-xl font-bold">Total: Rp ${new Intl.NumberFormat('id-ID').format(data.total)}</p>
            </div>
        `;
        contentDiv.innerHTML = detailsHtml;
    }

    // Close modal if user clicks outside of the content
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });
</script>
