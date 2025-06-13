<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-10 rounded-lg shadow-xl text-center max-w-lg">
            <div class="text-green-500 mb-4">
                <i class="fas fa-check-circle fa-5x"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Berhasil Diterima!</h1>
            <p class="text-gray-600 mb-6">Terima kasih telah memesan. Pesanan Anda dengan ID <span
                    class="font-bold">#{{ $pesanan->pesanan_id }}</span> sedang kami siapkan.</p>

            <div class="border-t border-b my-6 py-4 text-left">
                <h3 class="font-bold text-lg mb-2">Ringkasan Pesanan:</h3>
                <ul class="list-disc list-inside text-gray-700">
                    @foreach ($pesanan->detailPesanan as $detail)
                        <li>{{ $detail->menu->nama_menu }} (x{{ $detail->jumlah }})</li>
                    @endforeach
                </ul>
            </div>

            <p class="text-gray-500 text-sm">Silakan tunggu, pelayan kami akan segera mengkonfirmasi pesanan Anda.</p>
            <p class="text-gray-600 mt-6">
                Anda akan dialihkan ke <span class="font-bold">Dashboard</span> dalam <span id="countdown"
                    class="font-bold">10</span> detik.
            </p>
            <p class="mt-2 text-blue-600 hover:underline cursor-pointer" onclick="redirectToDashboard()">Klik di sini
                untuk
                kembali sekarang</p>
        </div>

    </div>
</body>
<script>
    let seconds = 10;
    const countdownElement = document.getElementById("countdown");

    const interval = setInterval(() => {
        seconds--;
        countdownElement.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            redirectToDashboard();
        }
    }, 1000);

    function redirectToDashboard() {
        window.location.href = "/dashboard"; // Ganti sesuai dengan route dashboard Anda
    }
</script>

</html>
