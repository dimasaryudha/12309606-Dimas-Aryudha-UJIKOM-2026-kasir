@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="grid grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-xl shadow flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7l9-4 9 4-9 4-9-4zm0 7l9 4 9-4" />
                </svg>
            </div>
            <div>
                <h2 class="text-gray-500">Produk Terjual</h2>
                <p class="text-2xl font-bold">{{ $totalProduk }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.364 4.56 9 9 0 015.121 17.804z" />
                </svg>
            </div>
            <div>
                <h2 class="text-gray-500">Member</h2>
                <p class="text-2xl font-bold">{{ $totalMember }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow flex items-center gap-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9a3 3 0 11-6 0 3 3 0 016 0zM6 20h12M9 16h6" />
                </svg>
            </div>
            <div>
                <h2 class="text-gray-500">Non Member</h2>
                <p class="text-2xl font-bold">{{ $totalNonMember }}</p>
            </div>
        </div>

    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="text-base font-semibold mb-3 text-center">Grafik Penjualan Bulanan</h2>

            <div class="h-48">
                <canvas id="chartBulanan"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="text-base font-semibold mb-3 text-center">Grafik Produk Terjual</h2>

            <div class="h-48">
                <canvas id="chartProduk"></canvas>
            </div>

            <div id="detailProduk" class="mt-4 hidden bg-gray-50 p-3 rounded text-center">
                <h3 id="namaProduk" class="font-bold"></h3>
                <p id="jumlahProduk" class="text-gray-600"></p>
            </div>
        </div>

    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">

        <div class="bg-white p-4 rounded-xl shadow flex flex-col h-full">
            <h2 class="text-lg font-bold mb-4">Penjualan Per Hari</h2>

            <div class="overflow-auto flex-1">
                <table class="w-full text-sm border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-3 py-2 text-left">Tanggal</th>
                            <th class="border px-3 py-2 text-left">Produk Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataHarian as $d)
                        <tr>
                            <td class="border px-3 py-2">
                                {{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}
                            </td>
                            <td class="border px-3 py-2">
                                {{ $d->detail_produk }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow flex flex-col h-full">
            <h2 class="text-lg font-bold mb-4">Data Perminggu</h2>

            <div class="overflow-auto flex-1">
                <table class="w-full text-sm border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-3 py-2 text-left">Hari</th>
                            <th class="border px-3 py-2 text-left">Jumlah Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataPerHari as $hari => $total)
                        <tr>
                            <td class="border px-3 py-2">{{ $hari }}</td>
                            <td class="border px-3 py-2">{{ $total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const chartData = @json($chartData);
    const produkList = @json($produkList);

    const bulanLabels = [
        'Jan','Feb','Mar','Apr','Mei','Jun',
        'Jul','Agu','Sep','Okt','Nov','Des'
    ];

    const produkLabels = Object.keys(produkList);
    const produkData = Object.values(produkList);

    const ctxBulanan = document.getElementById('chartBulanan');

    new Chart(ctxBulanan, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Produk Terjual',
                data: chartData,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10,
                        callback: function(value) {
                            return value;
                        }
                    }
                }
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const nama = produkLabels[index];
                    const jumlah = produkData[index];

                    tampilDetail(nama, jumlah);
                }
            }
        }
    });

    const ctxProduk = document.getElementById('chartProduk');

    new Chart(ctxProduk, {
        type: 'bar',
        data: {
            labels: produkLabels,
            datasets: [{
                label: 'Jumlah Terjual',
                data: produkData,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,        // 🔥 maksimal 100
                    ticks: {
                        stepSize: 10, // 🔥 per 10
                        callback: function(value) {
                            return value; // tanpa koma
                        }
                    }
                }
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const nama = produkLabels[index];
                    const jumlah = produkData[index];

                    tampilDetail(nama, jumlah);
                }
            }
        }
    });

    function tampilDetail(nama, jumlah) {
        document.getElementById('detailProduk').classList.remove('hidden');
        document.getElementById('namaProduk').innerText = nama;
        document.getElementById('jumlahProduk').innerText = 'Terjual: ' + jumlah;
    }
</script>

@endsection
