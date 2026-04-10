<!DOCTYPE html>
<html>
<head>
    <title>Struk</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 100%; }
        .flex { display: flex; justify-content: space-between; }
        hr { margin: 10px 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="flex">
        <h3>Struk Pembayaran</h3>
        <div>
            {{ $data->tanggal }}
        </div>
    </div>

    <hr>

    <h4>Produk:</h4>
    <ul>
        @foreach($data->items_detail as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>

    <hr>

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
</div>

</body>
</html>
