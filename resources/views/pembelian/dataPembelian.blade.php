@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">Data Pembeli</h2>

<a href="{{ route('pembelian.create') }}" class="text-blue-500 mb-4 inline-block">
    ← Kembali
</a>

<form method="POST" action="{{ route('pembelian.store') }}" class="bg-white p-6 rounded shadow">
@csrf

@endsection
