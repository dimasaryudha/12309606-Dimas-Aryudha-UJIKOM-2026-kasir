@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">

    <div class="flex justify-between mb-4">
        <h2 class="text-lg font-bold">Struk Pembayaran</h2>
        <div>
            {{ $data->tanggal }}
        </div>
    </div>

    <hr class="mb-4">

    <h3 class="font-semibold mb-2">Produk yang Dibeli:</h3>

    @foreach(explode(', ', $data->detail_produk) as $item)
        <div class="flex justify-between mb-1">
            <span>{{ $item }}</span>
        </div>
    @endforeach

    <hr class="my-4">

    <div class="flex justify-between">
        <span>Total</span>
        <span>Rp {{ number_format($data->price,0,',','.') }}</span>
    </div>

    <div class="flex justify-between">
        <span>Bayar</span>
        <span>Rp {{ number_format($data->bayar,0,',','.') }}</span>
    </div>

    <div class="flex justify-between">
        <span>Kembalian</span>
        <span>Rp {{ number_format($data->kembalian,0,',','.') }}</span>
    </div>

    <div class="mt-6 flex gap-2">
        <a href="{{ route('pembelian.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
            Kembali
        </a>

        <a href="{{ route('pembelian.struk_pdf', $data->id) }}"
           class="bg-blue-600 px-4 py-2 rounded text-white">
            Unduh PDF
        </a>
    </div>

</div>

@endsection
