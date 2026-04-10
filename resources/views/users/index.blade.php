@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold">Data User</h1>
</div>

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-start mb-4">
        <a href="{{ route('users.create') }}"
           class="bg-blue-600 px-4 py-2 rounded-lg text-white">
            Tambah User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <thead class="text-gray-500 border-b">
                <tr>
                    <th class="py-3">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @forelse($users as $index => $u)
                <tr class="border-b">
                    <td class="py-3">{{ $index + 1 }}</td>
                    <td class="py-3 font-medium">
                        {{ $u->name }}
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @if($u->role == 'admin')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                Admin
                            </span>
                        @else
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                Petugas
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('users.edit',$u->id) }}"
                               class="bg-yellow-400 px-3 py-1 rounded text-sm text-white">
                                Edit
                            </a>
                            <form action="{{ route('users.destroy',$u->id) }}"
                                  method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin hapus user?')"
                                    class="bg-red-500 px-3 py-1 rounded text-sm text-white">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        Data user kosong
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection
