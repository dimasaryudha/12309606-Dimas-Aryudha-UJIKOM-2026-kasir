@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold">Produk</h1>
</div>

<div class="bg-white rounded-xl shadow p-6">

    @if(auth()->user()->role == 'admin')
    <div class="flex justify-start mb-4">
        <a href="{{ route('products.create') }}"
           class="bg-blue-600 px-4 py-2 rounded-lg text-white">
            Tambah Produk
        </a>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-3">#</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    @if(auth()->user()->role == 'admin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($products as $index => $p)
                <tr class="border-b">
                    <td class="py-3">{{ $index + 1 }}</td>
                    <td class="flex items-center gap-3 py-3">
                        @if($p->image)
                            <img src="{{ asset('storage/'.$p->image) }}"
                                 class="object-cover rounded" style="width: 150px">
                        @else
                            <div class="bg-gray-200 rounded" style="width: 150px"></div>
                        @endif

                        <span>{{ $p->name }}</span>
                    </td>
                    <td>Rp {{ number_format($p->price,0,',','.') }}</td>
                    <td>{{ $p->stock }}</td>
                    <td class="text-center">
                        @if(auth()->user()->role == 'admin')
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('products.edit',$p->id) }}"
                                class="bg-yellow-400 px-3 py-1 rounded hover:bg-yellow-500 text-sm text-white">
                                Edit
                            </a>
                            <button onclick="openModal({{ $p->id }}, {{ $p->stock }})"
                                class="bg-blue-500 px-3 py-1 rounded hover:bg-blue-600 text-sm text-white">
                                Stok
                            </button>
                            <form action="{{ route('products.destroy',$p->id) }}"
                                method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')"
                                    class="bg-red-500 px-3 py-1 rounded hover:bg-red-600 text-sm text-white">
                                    Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        Data kosong
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="stockModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">

    <div class="bg-white p-6 rounded-lg w-80">
        <h2 class="text-lg font-bold mb-4">Update Stok</h2>

        <form id="stockForm" method="POST">
            @csrf
            <input type="number" name="stock" id="stockInput"
                value="{{ old('stock') }}"
                class="w-full border px-3 py-2 rounded mb-2 @error('stock') border-red-500 @enderror">
            @error('stock')
                <small class="text-red-500">{{ $message }}</small>
            @enderror
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal()"
                    class="px-3 py-1 bg-gray-300 rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-3 py-1 bg-blue-600 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, stock) {
    document.getElementById('stockModal').classList.remove('hidden');
    document.getElementById('stockModal').classList.add('flex');

    if (!"{{ old('stock') }}") {
        document.getElementById('stockInput').value = stock;
    }

    document.getElementById('stockForm').action = `/products/${id}/update-stock`;
}

function closeModal() {
    document.getElementById('stockModal').classList.add('hidden');
}
</script>

@if($errors->has('stock') && session('product_id'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        openModal(
            {{ session('product_id') }},
            {{ old('stock') ?? 0 }}
        );
    });
</script>
@endif

@endsection
