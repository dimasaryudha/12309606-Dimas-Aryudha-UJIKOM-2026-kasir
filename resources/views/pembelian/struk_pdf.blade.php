<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
        }

        .container {
            width: 300px;
            margin: auto;
        }

        .text-center {
            text-align: center;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
        }

        .small {
            font-size: 11px;
            color: #555;
        }

        ul {
            padding-left: 15px;
        }
    </style>
</head>
<body>

@php
    $poin = floor($data->price / 1000);
@endphp

<div class="container">

    <!-- HEADER -->
    <div class="text-center">
        <div class="title">Kasir WIkrama</div>
        <div class="small">Jl. Raya Wangun</div>
        <div class="small">Telp: 0812-9344-8333</div>
    </div>

    <hr>

    <!-- INFO -->
    <div class="flex">
        <span>No Transaksi</span>
        <span>#{{ $data->id }}</span>
    </div>

    <div class="flex">
        <span>Tanggal</span>
        <span>{{ $data->tanggal }}</span>
    </div>

    <hr>

    <!-- ITEM -->
    <b>ITEM PEMBELIAN</b>
    <ul>
        @foreach($data->items_detail as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>

    <hr>

    <!-- TOTAL -->
    <div class="flex">
        <span>Total</span>
        <span>Rp {{ number_format($data->price,0,',','.') }}</span>
    </div>

    <div class="flex">
        <span>Bayar</span>
        <span>Rp {{ number_format($data->bayar,0,',','.') }}</span>
    </div>

    <div class="flex">
        <span>Kembalian</span>
        <span>Rp {{ number_format($data->kembalian,0,',','.') }}</span>
    </div>

    <div class="flex">
        <span>Poin Didapat</span>
        <span>+ {{ $poin }} pts</span>
    </div>

    <hr>

    <!-- FOOTER -->
    <div class="text-center small">
        <p>Terima kasih telah berbelanja</p>
    </div>

</div>

</body>
</html>
