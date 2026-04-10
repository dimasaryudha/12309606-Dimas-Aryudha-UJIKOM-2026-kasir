@extends('layouts.app')

@section('content')

<div class="flex justify-center p-6">

    <!-- STRUK MODERN -->
    <div class="w-[380px] bg-white shadow-xl border border-dashed border-gray-300 p-5 font-mono text-sm">

        <!-- HEADER TOKO -->
        <div class="text-center mb-3">
            <h1 class="text-lg font-bold tracking-wide">Kasir Wikrama</h1>
            <p class="text-xs text-gray-500">Jl. Raya Wangun</p>
            <p class="text-xs text-gray-500">Telp: 0812-9344-8333</p>
        </div>

        <div class="border-t border-dashed my-3"></div>

        <!-- INFO TRANSAKSI -->
        <div class="flex justify-between text-xs mb-1">
            <span>No Transaksi</span>
            <span>#{{ $data->id }}</span>
        </div>

        <div class="flex justify-between text-xs mb-1">
            <span>Tanggal</span>
            <span>{{ $data->tanggal }}</span>
        </div>

        <div class="border-t border-dashed my-3"></div>

        <!-- ITEM -->
        <div class="font-semibold mb-2">ITEM PEMBELIAN</div>

        @php
            $items = json_decode($data->detail_produk, true) ?? [];
        @endphp

        @foreach($items as $item)
            <div class="flex justify-between text-xs mb-1">
                <span>• {{ $item }}</span>
            </div>
        @endforeach

        <div class="border-t border-dashed my-3"></div>

        <!-- TOTAL -->
        <div class="flex justify-between mb-1">
            <span>Total</span>
            <span>Rp {{ number_format($data->price,0,',','.') }}</span>
        </div>

        <div class="flex justify-between mb-1">
            <span>Bayar</span>
            <span>Rp {{ number_format($data->bayar,0,',','.') }}</span>
        </div>

        <div class="flex justify-between font-bold">
            <span>Kembalian</span>
            <span>Rp {{ number_format($data->kembalian,0,',','.') }}</span>
        </div>

        <div class="flex justify-between font-bold text-green-600">
            <span>Poin Didapat</span>
            <span> {{ $data->poin }}</span>
        </div>

        <div class="border-t border-dashed my-3"></div>

        <!-- FOOTER -->
        <div class="text-center text-xs text-gray-500">
            <p>Terima kasih telah berbelanja</p>
        </div>

        <!-- BUTTON -->
        <div class="mt-4 flex gap-2 justify-center no-print">
            <a href="{{ route('pembelian.index') }}"
               class="bg-gray-600 text-white px-3 py-1 rounded text-xs">
                Kembali
            </a>

            <a href="{{ route('pembelian.struk_pdf', $data->id) }}"
               class="bg-blue-600 text-white px-3 py-1 rounded text-xs">
                Download PDF
            </a>
        </div>

    </div>
</div>

@endsection
