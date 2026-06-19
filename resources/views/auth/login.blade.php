{{-- Halaman Login --}}
{{-- Mewarisi layout auth khusus (tanpa navbar toko) --}}
@extends('auth.layout')
@section('title', 'Masuk')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    {{-- === HEADER LOGIN === --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <span class="text-pink-500">&#9827;</span> FashionStore
        </h1>
        <p class="text-gray-500 mt-2">Masuk ke akun Anda</p>
    </div>

    {{-- === TAMPILKAN ERROR LOGIN (jika gagal) === --}}
    @if($errors->any())
        <div class="bg-red-50 text-red-600 text-sm p-4 rounded-lg mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- === FORM LOGIN === --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        {{-- Input Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
        </div>
        {{-- Input Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
        </div>
        {{-- Checkbox Ingat Saya --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-500">
                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>
        {{-- Tombol Submit --}}
        <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-lg font-semibold hover:bg-pink-600 transition">
            Masuk
        </button>
    </form>

    {{-- === LINK REGISTER === --}}
    <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
            Belum punya akun? <a href="{{ route('register') }}" class="text-pink-500 font-semibold hover:underline">Daftar di sini</a>
        </p>
    </div>

    {{-- === INFO DEMO LOGIN (untuk testing) === --}}
    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
        <p class="text-xs text-gray-500 font-semibold mb-2">Demo Login:</p>
        <p class="text-xs text-gray-500">Admin: admin@fashionstore.id / password</p>
        <p class="text-xs text-gray-500">Customer: rina@email.com / password</p>
    </div>
</div>
@endsection
