@extends('layouts.store')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h1>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 text-red-600 text-sm p-4 rounded-lg mb-4">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="font-bold text-gray-800 mb-4">Informasi Akun</h2>
        <form method="POST" action="{{ route('store.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon</label>
                <input type="text" name="phone" value="{{ $user->phone }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">{{ $user->address }}</textarea>
            </div>
            <button type="submit" class="bg-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-pink-600">Simpan Perubahan</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-4">Ubah Password</h2>
        <form method="POST" action="{{ route('store.profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
            </div>
            <button type="submit" class="bg-gray-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700">Ubah Password</button>
        </form>
    </div>
</div>
@endsection
