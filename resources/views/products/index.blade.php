@extends('layouts.app')

@section('title', 'Produk')
@section('page-title', 'Produk Fashion')
@section('page-subtitle', 'Kelola semua produk toko')
@section('nav-products', 'bg-gray-800 text-white')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 w-48">
        <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>
    <a href="{{ route('products.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Produk
    </a>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
    @forelse($products as $product)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="h-48 bg-gradient-to-br from-pink-50 to-purple-50 flex items-center justify-center relative overflow-hidden">
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            @else
                <svg class="w-16 h-16 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            @endif
            @if($product->is_on_sale)
            <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
            @endif
        </div>
        <div class="p-4">
            <div class="flex items-start justify-between mb-1">
                <h3 class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</h3>
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} ml-2 shrink-0">
                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }} &middot; SKU: {{ $product->sku }}</p>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-bold text-pink-600">Rp {{ number_format($product->effective_price, 0, ',', '.') }}</span>
                    @if($product->is_on_sale)
                    <span class="text-xs text-gray-400 line-through ml-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>
                <span class="text-xs {{ $product->stock <= 5 ? 'text-red-600 font-semibold' : 'text-gray-500' }}">Stok: {{ $product->stock }}</span>
            </div>
            @if($product->size || $product->color)
            <div class="mt-2 flex items-center space-x-2">
                @if($product->size)<span class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $product->size }}</span>@endif
                @if($product->color)<span class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $product->color }}</span>@endif
            </div>
            @endif
            <div class="mt-3 flex items-center space-x-2">
                <a href="{{ route('products.show', $product) }}" class="flex-1 text-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200">Detail</a>
                <a href="{{ route('products.edit', $product) }}" class="flex-1 text-center px-3 py-1.5 bg-pink-50 text-pink-600 rounded-lg text-xs hover:bg-pink-100">Edit</a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-sm text-gray-500">Belum ada produk</div>
    @endforelse
</div>

<div class="mt-6">
    {{ $products->withQueryString()->links() }}
</div>
@endsection
