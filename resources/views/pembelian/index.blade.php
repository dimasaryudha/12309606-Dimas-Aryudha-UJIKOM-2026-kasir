@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold">Data Pembelian</h1>
</div>

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between mb-4">

        @if(auth()->user()->role == 'petugas')
        <a href="{{ route('pembelian.create') }}"
        class="bg-blue-600 px-4 py-2 rounded-lg text-white">
            Tambah
        </a>
        @endif

        <a href="{{ route('pembelian.export') }}"
        class="bg-green-600 px-4 py-2 rounded-lg text-white">
            Export Excel
        </a>

    </div>

    <form method="GET" class="flex gap-2 mb-4">

        <input type="date"
            name="from"
            value="{{ request('from') }}"
            onchange="this.form.submit()"
            class="border rounded px-3 py-2 text-sm">

        <input type="date"
            name="to"
            value="{{ request('to') }}"
            onchange="this.form.submit()"
            class="border rounded px-3 py-2 text-sm">

        <a href="{{ route('pembelian.index') }}"
        class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">
            Reset
        </a>

    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-3">#</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Pembelian</th>
                    <th>Total Harga</th>
                    <th>Dibuat Oleh</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

        <tbody class="text-gray-700">
            @forelse($data as $index => $d)
            <tr class="border-b">
                <td class="py-3">{{ $index + 1 }}</td>
                <td>
                    {{ $d->status_member == 'member' ? $d->name : 'NON-MEMBER' }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($d->tanggal)->format('d F Y') }}
                </td>
                <td class="font-medium">
                    Rp {{ number_format($d->price, 0, ',', '.') }}
                </td>
                <td>
                    Petugas
                </td>
                <td class="text-center">
                    <button onclick='openDetailModal(@json($d))'
                        class="bg-blue-600 px-3 py-1 rounded text-sm text-white">
                        Detail
                    </button>
                </td>

            </tr>

            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-500">
                    Data pembelian kosong
                </td>
            </tr>
            @endforelse
        </tbody>

        </table>
    </div>
</div>

@endsection

<!-- MODAL DETAIL PEMBELIAN -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">

    <div class="bg-white p-6 rounded-lg w-[500px]">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Detail Pembelian</h2>
            <button onclick="closeDetailModal()">✖</button>
        </div>

        <!-- INFO -->
        <div class="text-sm mb-4 space-y-1">
            <p><b>Nama:</b> <span id="detailNama"></span></p>
            <p><b>Status:</b> <span id="detailStatus"></span></p>
            <p><b>No HP:</b> <span id="detailPhone"></span></p>
            <p><b>Poin:</b> <span id="detailPoint"></span></p>
        </div>

        <!-- TABLE PRODUK -->
        <table class="w-full text-sm mb-3">
            <thead>
                <tr class="border-b">
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody id="detailItems"></tbody>
        </table>

        <!-- FOOTER -->
        <div class="text-xs text-gray-500 mt-3">
            <p id="detailDate"></p>
        </div>

        <div class="flex justify-end mt-4">
            <button onclick="closeDetailModal()"
                class="px-3 py-1 bg-gray-500 text-white rounded">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function openDetailModal(data) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');

    document.getElementById('detailNama').innerText = data.name;
    document.getElementById('detailStatus').innerText = data.status_member;
    document.getElementById('detailPhone').innerText = data.no_hp ?? '-';
    document.getElementById('detailPoint').innerText = data.poin ?? 0;

    document.getElementById('detailDate').innerText =
        new Date(data.tanggal).toLocaleDateString('id-ID');

    let items = `
        <tr>
            <td>${data.detail_produk ?? '-'}</td>
            <td>Rp ${new Intl.NumberFormat('id-ID').format(data.price)}</td>
            <td>Rp ${new Intl.NumberFormat('id-ID').format(data.bayar)}</td>
            <td>Rp ${new Intl.NumberFormat('id-ID').format(data.kembalian)}</td>
        </tr>
    `;

    document.getElementById('detailItems').innerHTML = items;
}
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>
