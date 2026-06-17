@extends('layouts.app')

@section('title', 'Kategori')
@section('page-title', 'Kategori Produk')
@section('page-subtitle', 'Kelola kategori produk fashion')
@section('nav-categories', 'bg-gray-800 text-white')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('categories.index') }}" class="flex items-center space-x-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 w-64">
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>
    <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Kategori
    </a>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 gap-5">
    @forelse($categories as $category)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
        <div class="h-40 bg-gradient-to-br from-pink-50 to-purple-50 relative overflow-hidden">
            @if($category->image)
            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-12 h-12 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <span class="absolute bottom-3 left-3 inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-900">{{ $category->name }}</h3>
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $category->description }}</p>
            <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-gray-500">{{ $category->products_count }} produk</span>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('categories.edit', $category) }}" class="text-xs text-blue-600 hover:text-blue-800">Edit</a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-sm text-gray-500">Belum ada kategori</div>
    @endforelse
</div>

<div class="mt-6">
    {{ $categories->withQueryString()->links() }}
</div>
@endsection
