@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Data Pembeli</h2>

<a href="{{ route('pembelian.create') }}" class="text-blue-500 mb-4 inline-block">
    ← Kembali
</a>

<form method="POST" action="{{ route('pembelian.store') }}" class="bg-white p-6 rounded shadow">
@csrf

<div class="mb-6">
    <h3 class="font-semibold mb-2">Produk yang dipilih</h3>

    @foreach($result as $i => $p)
    <div class="flex justify-between border-b py-2">
        <div>
            {{ $p['nama'] }} <br>
            <span class="text-sm text-gray-500">
                Rp {{ number_format($p['harga'],0,',','.') }} x {{ $p['jumlah'] }}
            </span>
        </div>
        <div>
            Rp {{ number_format($p['subtotal'],0,',','.') }}
        </div>
    </div>

    <input type="hidden" name="products[{{ $i }}][id]" value="{{ $p['id'] }}">
    <input type="hidden" name="products[{{ $i }}][jumlah]" value="{{ $p['jumlah'] }}">
    @endforeach

    <!-- TOTAL -->
    <div class="text-right mt-3">
        <div id="before-total" class="text-gray-400 hidden"></div>
        <div id="final-total" class="font-bold text-lg">
            Rp {{ number_format($total,0,',','.') }}
        </div>
    </div>
</div>

<div class="mb-4">
    <label>Status Member</label>
    <select id="status_member" name="status_member" class="border p-2 rounded w-full">
        <option value="non_member">Bukan Member</option>
        <option value="member">Member</option>
    </select>
</div>

<div id="field-member-select" class="mb-4 hidden">
    <label>Pilih Member</label>
    <select id="select-member" class="border p-2 rounded w-full">
        <option value="">-- Pilih Member --</option>
        @foreach($members as $m)
            <option value="{{ $m->no_hp }}" data-nama="{{ $m->name }}">
                {{ $m->name }} ({{ $m->no_hp }})
            </option>
        @endforeach
    </select>
</div>

<div id="field-nama" class="mb-4 hidden">
    <label>Nama</label>
    <input type="text" id="nama" name="nama" class="border p-2 rounded w-full">
</div>

<div id="field-nohp" class="mb-4 hidden">
    <label>No HP</label>
    <input type="text" id="no_hp" name="no_hp" class="border p-2 rounded w-full">
</div>

<div id="info-member" class="mb-4 hidden text-sm text-green-600"></div>

<div id="field-poin" class="mb-4 hidden">
    <label>Gunakan Poin</label>
    <input type="number" id="poin" name="poin" value="0" class="border p-2 rounded w-full" readonly>
</div>

<div class="mb-4">
    <label>Total Bayar</label>
    <input type="number" id="bayar" name="bayar" class="border p-2 rounded w-full" required>
    <div id="warning-bayar" class="text-red-500 text-sm hidden">
        Uang bayar kurang!
    </div>
</div>

<div class="text-right">
    <button type="submit" class="bg-blue-600 px-6 py-2 rounded text-white">
        Pesan
    </button>
</div>

</form>

<script>
let total = {{ $total }};
let maxPoin = 0;
let maxPoinFromTotal = total * 0.01;

document.getElementById('status_member').addEventListener('change', function() {
    let status = this.value;
    document.getElementById('field-member-select').classList.toggle('hidden', status !== 'member');
    document.getElementById('field-nohp').classList.toggle('hidden', status !== 'member');
    document.getElementById('field-nama').classList.toggle('hidden', status !== 'member');
    document.getElementById('field-poin').classList.toggle('hidden', status !== 'member');
});

document.getElementById('select-member').addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];

    let nohp = this.value;
    let nama = selected.getAttribute('data-nama');

    document.getElementById('no_hp').value = nohp;
    document.getElementById('nama').value = nama;

    let poinField = document.getElementById('field-poin');
    let inputPoin = document.getElementById('poin');

    let autoPoin = Math.floor(total * 0.01);

    inputPoin.value = autoPoin;

    poinField.classList.remove('hidden');
    inputPoin.removeAttribute('disabled');

    let final = total - autoPoin;

    document.getElementById('before-total').innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);

    document.getElementById('before-total').classList.remove('hidden');

    document.getElementById('final-total').innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(final);
});

document.getElementById('no_hp').addEventListener('blur', function() {
    let nohp = this.value;

    if (!nohp) return;

        fetch(`/cek-member?no_hp=${nohp}`)
            .then(res => res.json())
            .then(data => {

                let info = document.getElementById('info-member');
                let poinField = document.getElementById('field-poin');
                let inputPoin = document.getElementById('poin');

        if (data.exists) {

            maxPoin = Math.min(data.poin, maxPoinFromTotal);

            info.innerText = `Member ditemukan | Poin: ${data.poin}`;
            info.classList.remove('hidden');

            poinField.classList.remove('hidden');
            let autoPoin = Math.floor(total * 0.01);
            let finalPoin = Math.min(data.poin, autoPoin);

            inputPoin.value = finalPoin;

            if (data.poin == 0) {
                inputPoin.value = 0;
                inputPoin.setAttribute('disabled', true);
                info.innerText += ' (Belum bisa pakai poin)';
            } else {
                inputPoin.removeAttribute('disabled');
            }

            let final = total - finalPoin;

            document.getElementById('before-total').innerText =
                'Rp ' + new Intl.NumberFormat('id-ID').format(total);

            document.getElementById('before-total').classList.remove('hidden');

            document.getElementById('final-total').innerText =
                'Rp ' + new Intl.NumberFormat('id-ID').format(final);
        }
    });
});

document.getElementById('poin').addEventListener('input', function() {
    let poin = parseInt(this.value) || 0;

    if (poin > maxPoin) {
        this.value = Math.floor(maxPoin);
        poin = Math.floor(maxPoin);
    }

    let final = total - poin;

    document.getElementById('before-total').innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);

    document.getElementById('before-total').classList.remove('hidden');

    document.getElementById('final-total').innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(final);
});

document.getElementById('bayar').addEventListener('input', function() {
    let bayar = parseInt(this.value) || 0;
    let poin = parseInt(document.getElementById('poin').value) || 0;
    let final = total - poin;
    let warning = document.getElementById('warning-bayar');

    if (bayar < final) {
        warning.classList.remove('hidden');
    } else {
        warning.classList.add('hidden');
    }
});

document.getElementById('poin').addEventListener('input', function() {
    let poin = parseInt(this.value) || 0;
    let max = Math.floor(total * 0.01);

    if (poin > max) {
        this.value = max;
        poin = max;
    }

    let final = total - poin;

    document.getElementById('before-total').innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);

    document.getElementById('before-total').classList.remove('hidden');

    document.getElementById('final-total').innerText =
        'Rp ' + new Intl.NumberFormat('id-ID').format(final);
});
</script>

@endsection
