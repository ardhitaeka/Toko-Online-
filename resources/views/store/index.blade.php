{{-- MEMPERLUAS LAYOUT 'layouts.store' --}}
{{-- Artinya file ini akan mengisi bagian @yield('content') di layout utama --}}
@extends('layouts.store')

{{-- JUDUL HALAMAN --}}
@section('title', 'Beranda - FashionStore')

{{-- KONTEN UTAMA --}}
@section('content')

{{-- ============================================================ --}}
{{-- SECTION 1: HERO / BANNER UTAMA                              --}}
{{-- Menampilkan gambar toko baju sebagai background dengan       --}}
{{-- overlay gradien gelap pink-purple agar teks terbaca          --}}
{{-- ============================================================ --}}
<div class="relative text-white overflow-hidden" style="background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(219,39,119,0.4) 50%, rgba(88,28,135,0.6) 100%), url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;">
    
    {{-- Elemen dekoratif: lingkaran transparan untuk efek visual --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="absolute top-1/2 left-1/4 w-4 h-4 bg-white/20 rounded-full"></div>
    <div class="absolute top-1/4 right-1/3 w-3 h-3 bg-white/20 rounded-full"></div>
    <div class="absolute bottom-1/3 right-1/4 w-5 h-5 bg-white/20 rounded-full"></div>

    <div class="relative max-w-7xl mx-auto px-4 py-20 md:py-28">
        <div class="text-center max-w-3xl mx-auto">
            {{-- Badge "New Collection" dengan efek transparan --}}
            <span class="inline-block bg-white/20 text-white text-sm px-4 py-1.5 rounded-full mb-4 backdrop-blur-sm">&#9733; New Collection 2026</span>
            
            {{-- Judul utama dengan aksen kuning pada kata "Fashion" --}}
            <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">Koleksi <span class="text-yellow-300">Fashion</span> Terbaru</h1>
            
            {{-- Deskripsi singkat --}}
            <p class="text-lg md:text-xl text-pink-100 mb-8 max-w-2xl mx-auto">Temukan gaya terbaik untuk setiap kesempatan. Dari casual hingga formal, kami punya semuanya.</p>
            
            {{-- Tombol CTA (Call to Action) --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#products" class="bg-white text-pink-600 px-8 py-3.5 rounded-full font-semibold hover:bg-pink-50 hover:shadow-lg hover:shadow-white/25 transition-all duration-300">Belanja Sekarang</a>
                <a href="{{ route('store.orders') }}" class="border-2 border-white/40 text-white px-8 py-3.5 rounded-full font-semibold hover:bg-white/10 hover:border-white/60 transition-all duration-300">Lihat Pesanan</a>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SECTION 2: PRODUK UNGGULAN (FEATURED PRODUCTS)               --}}
{{-- Hanya tampil jika ada produk dengan is_featured = true        --}}
{{-- Tampilan: grid 4 kolom dengan card yang punya hover effect   --}}
{{-- ============================================================ --}}
@if($featured->count())
<div class="max-w-7xl mx-auto px-4 py-16">
    {{-- Header section dengan link "Lihat Semua" --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Produk Unggulan</h2>
            <p class="text-gray-500 text-sm mt-1">Koleksi pilihan terbaik untukmu</p>
        </div>
        <a href="#products" class="hidden sm:flex items-center gap-1 text-pink-500 font-semibold text-sm hover:text-pink-600 transition">
            Lihat Semua
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- Grid produk: 1 kolom di HP, 2 di tablet, 4 di desktop --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featured as $product)
        {{-- CARD PRODUK: efek hover shadow & border pink --}}
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-pink-100">
            {{-- Gambar dengan efek zoom saat hover --}}
            <div class="relative overflow-hidden">
                <a href="{{ route('store.show', $product) }}">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
                {{-- Badge SALE jika produk sedang diskon --}}
                @if($product->is_on_sale)
                    <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">SALE</span>
                @endif
                {{-- Overlay gelap saat hover --}}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                {{-- Tombol "lihat detail" yang muncul saat hover (icon mata) --}}
                <a href="{{ route('store.show', $product) }}" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-pink-500 p-2.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0 shadow-lg hover:bg-pink-500 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
            </div>
            {{-- Informasi produk: kategori, nama, harga, tombol keranjang --}}
            <div class="p-5">
                <p class="text-xs text-pink-500 font-semibold mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'General' }}</p>
                <a href="{{ route('store.show', $product) }}" class="text-gray-800 font-semibold hover:text-pink-500 transition line-clamp-1">{{ $product->name }}</a>
                
                {{-- Harga: tampilkan sale_price jika diskon, dengan harga asli dicoret --}}
                <div class="mt-3 flex items-baseline gap-2">
                    @if($product->is_on_sale)
                        <span class="text-pink-500 font-bold text-lg">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                        <span class="text-gray-400 text-sm line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @else
                        <span class="text-gray-800 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>

                {{-- Form tambah ke keranjang (langsung 1 klik, quantity = 1) --}}
                {{-- @csrf = token keamanan Laravel untuk mencegah CSRF attack --}}
                <form method="POST" action="{{ route('store.add-cart', $product) }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:from-pink-600 hover:to-pink-700 transition-all duration-300 shadow-md hover:shadow-pink-200">+ Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ============================================================ --}}
{{-- SECTION 3: PROMO BANNER                                      --}}
{{-- Banner promosi dengan gradien warna-warni, selalu tampil     --}}
{{-- ============================================================ --}}
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="relative bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 rounded-3xl overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative px-8 py-12 md:py-16 md:px-12 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="text-yellow-300 font-semibold text-sm uppercase tracking-wider">Limited Offer</span>
                    <h3 class="text-2xl md:text-3xl font-bold mt-2">Diskon Spesial Hingga 50%</h3>
                    <p class="text-pink-100 mt-2 max-w-lg">Dapatkan fashion terbaik dengan harga spesial. Periode terbatas, jangan sampai kelewatan!</p>
                </div>
                <a href="#products" class="flex-shrink-0 bg-white text-pink-600 px-8 py-3.5 rounded-full font-semibold hover:bg-pink-50 hover:shadow-lg transition-all duration-300 shadow-lg">Belanja Sekarang</a>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- SECTION 4: SEMUA PRODUK                                      --}}
{{-- Menampilkan semua produk dengan fitur pencarian & filter      --}}
{{-- ============================================================ --}}
<div id="products" class="max-w-7xl mx-auto px-4 py-12">
    {{-- Header dengan form search & filter kategori --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Semua Produk</h2>
            <p class="text-gray-500 text-sm mt-1">Jelajahi koleksi lengkap kami</p>
        </div>
        
        {{-- Form filter: method GET agar parameter muncul di URL (bisa di-bookmark) --}}
        <form method="GET" action="{{ route('store.index') }}" class="flex flex-wrap gap-3">
            {{-- Input pencarian dengan icon search di dalamnya --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500 focus:border-transparent bg-gray-50 w-full md:w-48">
            </div>
            
            {{-- Dropdown filter kategori --}}
            <select name="category" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-gradient-to-r from-pink-500 to-pink-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:from-pink-600 hover:to-pink-700 transition-all duration-300 shadow-md hover:shadow-pink-200">Filter</button>
        </form>
    </div>

    {{-- Jika tidak ada produk --}}
    @if($products->isEmpty())
        <div class="text-center py-20">
            {{-- Icon kotak kosong --}}
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            <p class="text-gray-400 text-lg">Tidak ada produk ditemukan.</p>
            <a href="{{ route('store.index') }}" class="inline-block mt-3 text-pink-500 font-semibold hover:text-pink-600 transition">Reset Filter</a>
        </div>
    @else
        {{-- Grid produk (sama seperti featured) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-pink-100">
                <div class="relative overflow-hidden">
                    <a href="{{ route('store.show', $product) }}">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                    @if($product->is_on_sale)
                        <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">SALE</span>
                    @endif
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    <a href="{{ route('store.show', $product) }}" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-pink-500 p-2.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0 shadow-lg hover:bg-pink-500 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                </div>
                <div class="p-5">
                    <p class="text-xs text-pink-500 font-semibold mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'General' }}</p>
                    <a href="{{ route('store.show', $product) }}" class="text-gray-800 font-semibold hover:text-pink-500 transition line-clamp-1">{{ $product->name }}</a>
                    <div class="mt-3 flex items-baseline gap-2">
                        @if($product->is_on_sale)
                            <span class="text-pink-500 font-bold text-lg">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-gray-400 text-sm line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-gray-800 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('store.add-cart', $product) }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:from-pink-600 hover:to-pink-700 transition-all duration-300 shadow-md hover:shadow-pink-200">+ Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Navigasi halaman (pagination) --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
