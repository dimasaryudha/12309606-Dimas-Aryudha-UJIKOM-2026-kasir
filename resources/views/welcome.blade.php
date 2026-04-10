<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">

<!-- NAVBAR -->
<nav class="flex justify-between items-center px-8 py-5 bg-white shadow-sm">
    <h1 class="text-xl font-bold text-blue-600">KasirApp</h1>

    <div class="hidden md:flex gap-8 text-sm font-medium">
        <a href="#fitur" class="hover:text-blue-600">Fitur</a>
        <a href="#harga" class="hover:text-blue-600">Harga</a>
        <a href="#kontak" class="hover:text-blue-600">Kontak</a>
    </div>

    <div>
        @auth
            <a href="{{ route('dashboard') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm hover:bg-blue-700">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm hover:bg-blue-700">
                Login
            </a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="grid md:grid-cols-2 gap-10 items-center px-8 mt-16 max-w-6xl mx-auto">

    <!-- TEXT -->
    <div>
        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
            Kelola Toko Jadi <br>
            <span class="text-blue-600">Lebih Mudah</span> & Cepat
        </h1>

        <p class="text-gray-600 mb-8 max-w-md">
            Aplikasi kasir modern untuk membantu UMKM mengelola transaksi,
            stok barang, dan laporan keuangan dalam satu sistem.
        </p>

        <div class="flex gap-4 mb-4">
            <a href=""
               class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold shadow hover:scale-105 transition">
                Mulai Sekarang 
            </a>

            <a href="#fitur"
               class="border border-gray-300 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100">
                Lihat Fitur
            </a>
        </div>
    </div>

    <!-- IMAGE -->
    <div class="hidden md:block">
        <img src="{{ asset('images/kasir.png') }}"
             alt="Kasir App"
             class="w-full max-w-md mx-auto drop-shadow-xl">
    </div>

</section>

<!-- FOOTER -->
<footer class="bg-white mt-16 py-6 text-center text-sm text-gray-500" style="margin-top: 125px">
    © {{ date('Y') }} KasirApp. All rights reserved.
</footer>

</body>
</html>
