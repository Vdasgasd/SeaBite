<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meja Tidak Tersedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-10 rounded-lg shadow-xl text-center max-w-lg">
            <div class="text-yellow-500 mb-4">
                <i class="fas fa-exclamation-triangle fa-5x"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Meja Tidak Tersedia</h1>
            <p class="text-gray-600 mb-6">
                Maaf, Meja <span class="font-bold">{{ $meja->nomor_meja }}</span> saat ini sedang <span class="font-bold">{{ $meja->status }}</span>.
            </p>
            <p class="text-gray-500 text-sm">Silakan hubungi pelayan kami untuk bantuan lebih lanjut atau pindah ke meja lain yang tersedia.</p>
            <a href="/" class="mt-6 inline-block bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
