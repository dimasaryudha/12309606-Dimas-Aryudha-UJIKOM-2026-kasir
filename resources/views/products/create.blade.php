@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Tambah Produk</h2>

<a href="{{ route('products.index') }}" class="text-blue-500 mb-4 inline-block">
    Kembali
</a>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow w-full max-w-lg">
    @csrf

    <div class="mb-4">
        <label class="block mb-1">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name') }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan nama produk">

        @error('name')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Harga</label>
        <input type="text" id="price" name="price" value="{{ old('price') }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan harga">

        @error('price')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Stok</label>
        <input type="number" name="stock" value="{{ old('stock') }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan stok">

        @error('stock')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Gambar Produk</label>
        <input type="file" name="image" class="w-full">

        @error('image')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Simpan
    </button>

</form>

<script>
document.getElementById('price').addEventListener('keyup', function(){
    let value = this.value.replace(/\D/g, '');
    this.value = new Intl.NumberFormat('id-ID').format(value);
});
</script>

@endsection
