@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Pembelian</h2>

<a href="{{ route('pembelian.index') }}" class="text-blue-500 mb-4 inline-block">
    ← Kembali
</a>

<form method="POST" action="{{ route('pembelian.dataPembelian') }}" class="bg-white p-6 rounded shadow">
    @csrf

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @foreach($products as $p)
        <div class="border rounded-xl p-4 text-center shadow-sm">
            <img src="{{ asset('storage/'.$p->image) }}" class="w-24 h-24 object-cover mx-auto mb-3 rounded">
            <div class="font-semibold">{{ $p->name }}</div>
            <div class="text-xs text-gray-500 mb-1">Stok {{ $p->stock }}</div>
            <div class="text-sm mb-3">Rp {{ number_format($p->price,0,',','.') }}</div>

            <div class="flex justify-center items-center gap-2 mb-2">
                <button type="button" onclick="kurang({{ $p->id }})" class="px-2 py-1 bg-gray-300 rounded">-</button>
                <input type="number" id="jumlah-{{ $p->id }}" name="products[{{ $loop->index }}][jumlah]" value="0" min="0" max="{{ $p->stock }}" class="w-12 text-center border rounded">
                <button type="button" onclick="tambah({{ $p->id }}, {{ $p->stock }})" class="px-2 py-1 bg-gray-300 rounded">+</button>
            </div>

            <div class="text-sm text-gray-600">Sub Total: <span id="text-subtotal-{{ $p->id }}">Rp 0</span></div>

            <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $p->id }}">
            <input type="hidden" id="harga-{{ $p->id }}" value="{{ $p->price }}">
        </div>
        @endforeach

    </div>

    <div class="mt-6 text-center">
        <button type="submit" class="bg-blue-600 px-6 py-2 rounded text-white">
            Selanjutnya
        </button>
    </div>
</form>

{{-- SCRIPT --}}
<script>
function tambah(id, stok) {
    let input = document.getElementById('jumlah-' + id);
    let value = parseInt(input.value) || 0;

    if (value < stok) {
        input.value = value + 1;
    }

    updateSubtotal(id);
}

function kurang(id) {
    let input = document.getElementById('jumlah-' + id);
    let value = parseInt(input.value) || 0;

    if (value > 0) {
        input.value = value - 1;
    }

    updateSubtotal(id);
}

function updateSubtotal(id) {
    let jumlah = parseInt(document.getElementById('jumlah-' + id).value) || 0;
    let harga = parseInt(document.getElementById('harga-' + id).value);

    let subtotal = jumlah * harga;

    document.getElementById('text-subtotal-' + id).innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
}

</script>

@endsection
