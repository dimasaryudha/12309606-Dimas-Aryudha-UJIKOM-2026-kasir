@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Edit Produk</h2>

<a href="{{ route('products.index') }}" class="text-blue-500 mb-4 inline-block">
    Kembali
</a>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow w-full max-w-lg">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-1">Nama Produk</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan nama produk">

        @error('name')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Harga</label>
        <input type="text" id="price" name="price"
            value="{{ old('price', number_format($product->price,0,',','.')) }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan harga">

        @error('price')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Stok</label>
        <input type="number"
            value="{{ $product->stock }}"
            class="w-full border px-3 py-2 rounded bg-gray-100 cursor-not-allowed"
            readonly>
        <small class="text-gray-500">Stok hanya bisa diubah melalui tombol "Update Stok"</small>
    </div>

    <div class="mb-4">
        <label class="block mb-1">Gambar Saat Ini</label>

        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}"
                 class="w-20 h-20 object-cover rounded border mb-2">
        @else
            <div class="w-20 h-20 bg-gray-200 rounded mb-2"></div>
        @endif
    </div>

    <div class="mb-4">
        <label class="block mb-1">Ganti Gambar (Opsional)</label>
        <input type="file" name="image" class="w-full">

        @error('image')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Update
    </button>

</form>

<script>
document.getElementById('price').addEventListener('keyup', function(){
    let value = this.value.replace(/\D/g, '');
    this.value = new Intl.NumberFormat('id-ID').format(value);
});
</script>

@endsection
