@extends('layouts.store')
@section('title', 'Beranda - FashionStore')

@section('content')
<!-- Hero -->
<div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Koleksi Fashion Terbaru</h1>
        <p class="text-lg opacity-90 mb-6">Temukan gaya terbaik untuk setiap kesempatan</p>
        <a href="#products" class="inline-block bg-white text-pink-500 px-8 py-3 rounded-full font-semibold hover:bg-pink-50 transition">Belanja Sekarang</a>
    </div>
</div>

<!-- Featured Products -->
@if($featured->count())
<div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Unggulan</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featured as $product)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
            <a href="{{ route('store.show', $product) }}">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
            </a>
            <div class="p-4">
                <p class="text-xs text-pink-500 font-semibold mb-1">{{ $product->category->name ?? '' }}</p>
                <a href="{{ route('store.show', $product) }}" class="text-gray-800 font-semibold hover:text-pink-500">{{ $product->name }}</a>
                <div class="mt-2 flex items-center gap-2">
                    @if($product->is_on_sale)
                        <span class="text-pink-500 font-bold">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                        <span class="text-gray-400 text-sm line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-gray-800 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>
                <form method="POST" action="{{ route('store.add-cart', $product) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-pink-600 transition">Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- All Products -->
<div id="products" class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Semua Produk</h2>
        <form method="GET" action="{{ route('store.index') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-pink-600">Filter</button>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-12 text-gray-500">Tidak ada produk ditemukan.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                <a href="{{ route('store.show', $product) }}">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                </a>
                <div class="p-4">
                    <p class="text-xs text-pink-500 font-semibold mb-1">{{ $product->category->name ?? '' }}</p>
                    <a href="{{ route('store.show', $product) }}" class="text-gray-800 font-semibold hover:text-pink-500">{{ $product->name }}</a>
                    <div class="mt-2 flex items-center gap-2">
                        @if($product->is_on_sale)
                            <span class="text-pink-500 font-bold">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-gray-400 text-sm line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-gray-800 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('store.add-cart', $product) }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-pink-600 transition">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</div>
@endsection
