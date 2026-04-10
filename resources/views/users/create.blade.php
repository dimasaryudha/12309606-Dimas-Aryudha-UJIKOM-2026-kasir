@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold">Tambah User</h1>
</div>

<a href="{{ route('users.index') }}" class="text-blue-500 mb-4 inline-block">
    Kembali
</a>

<form method="POST" action="{{ route('users.store') }}"
      class="bg-white p-6 rounded-xl shadow w-full max-w-lg">
@csrf

    <div class="mb-4">
        <label class="block mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan nama">

        @error('name')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan email">

        @error('email')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Role</label>
        <select name="role" class="w-full border px-3 py-2 rounded">
            <option value="">Pilih Role</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
        </select>

        @error('role')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-1">Password</label>
        <input type="password" name="password"
            class="w-full border px-3 py-2 rounded"
            placeholder="Masukkan password">

        @error('password')
            <small class="text-red-500">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit"
        class="bg-blue-500 px-4 py-2 rounded">
        Simpan
    </button>

</form>

@endsection
