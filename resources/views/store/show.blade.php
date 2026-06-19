{{-- Halaman Detail Produk --}}
{{-- Mewarisi layout store (navbar + footer) --}}
@extends('layouts.store')
{{-- Judul halaman diambil dari nama produk --}}
@section('title', $product->name)

{{-- === PERSIAPAN DATA VARIASI PRODUK === --}}
{{-- Ukuran & Warna disimpan sebagai string (dipisah koma), kita ubah ke array --}}
@php
    // Ubah string ukuran jadi array, handle pemisah koma dan garis miring
    $sizes = $product->size ? array_map('trim', explode(',', str_replace('/', ',', $product->size))) : [];
    // Ubah string warna jadi array
    $colors = $product->color ? array_map('trim', explode(',', str_replace('/', ',', $product->color))) : [];
    // Cek apakah produk punya variasi (ukuran/warna)
    $hasVariants = !empty($sizes) || !empty($colors);
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- === BREADCRUMB (Navigasi halaman) === --}}
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="{{ route('store.index') }}" class="hover:text-pink-500">Beranda</a>
        <span class="mx-2">/</span>
        <a href="{{ route('store.index', ['category' => $product->category_id]) }}" class="hover:text-pink-500">{{ $product->category->name ?? 'Produk' }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">{{ $product->name }}</span>
    </nav>

    {{-- === LAYOUT 2 KOLOM: Gambar (kiri) + Info Produk (kanan) === --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
        {{-- === KOLOM KIRI: GAMBAR PRODUK === --}}
        <div class="relative">
            {{-- Container gambar dengan background gradient --}}
            <div class="bg-gradient-to-br from-pink-50 via-white to-purple-50 rounded-2xl overflow-hidden border border-gray-100">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-[400px] md:h-[500px] object-cover">
            </div>
            {{-- Badge SALE jika produk sedang diskon --}}
            @if($product->is_on_sale)
                <span class="absolute top-4 left-4 bg-red-500 text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-lg">SALE {{ number_format((1 - $product->sale_price / $product->price) * 100, 0) }}%</span>
            @endif
            {{-- Badge "Pilihan" jika produk featured --}}
            @if($product->is_featured)
                <span class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-1.5 rounded-full shadow-lg">Pilihan</span>
            @endif
        </div>

        {{-- === KOLOM KANAN: INFORMASI PRODUK === --}}
        <div>
            {{-- Nama Kategori --}}
            <p class="text-sm text-pink-500 font-semibold tracking-wide uppercase mb-1">{{ $product->category->name ?? '' }}</p>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

            {{-- === TAMPILAN HARGA: Tampilkan sale_price jika diskon, price jika normal === --}}
            <div class="flex items-center gap-3 mb-6">
                @if($product->is_on_sale)
                    {{-- Harga setelah diskon (lebih besar & warna pink) --}}
                    <span class="text-3xl font-bold text-pink-500">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    {{-- Harga asli dicoret --}}
                    <span class="text-xl text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @else
                    {{-- Harga normal --}}
                    <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>

            {{-- === INDIKATOR STOK: Hijau jika tersedia, Orange jika <= 5, Merah jika habis === --}}
            <div class="flex items-center gap-2 mb-6">
                @if($product->stock > 0)
                    <span class="w-2.5 h-2.5 rounded-full {{ $product->stock <= 5 ? 'bg-orange-400' : 'bg-green-400' }}"></span>
                    <span class="text-sm {{ $product->stock <= 5 ? 'text-orange-600 font-medium' : 'text-green-600' }}">
                        {{ $product->stock <= 5 ? 'Stok tersisa ' . $product->stock . ' buah' : 'Stok tersedia' }}
                    </span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                    <span class="text-sm text-red-600 font-medium">Stok habis</span>
                @endif
            </div>

            {{-- === FORM TAMBAH KE KERANJANG (hanya tampil jika stok > 0) === --}}
            @if($product->stock > 0)
            <form method="POST" action="{{ route('store.add-cart', $product) }}" id="addToCartForm">
                @csrf

                {{-- === PILIH UKURAN (jika produk punya variasi ukuran) === --}}
                @if(!empty($sizes))
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2" id="sizeOptions">
                        @foreach($sizes as $size)
                        {{-- Tombol pilih ukuran, pakai JS selectOption() untuk toggle class aktif --}}
                        <button type="button"
                            class="size-btn px-5 py-2.5 border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-pink-300 hover:text-pink-500 transition-all"
                            data-value="{{ $size }}"
                            onclick="selectOption(this, 'size')">
                            {{ $size }}
                        </button>
                        @endforeach
                    </div>
                    {{-- Hidden input untuk nyimpan ukuran yang dipilih --}}
                    <input type="hidden" name="size" id="selectedSize" value="">
                </div>
                @endif

                {{-- === PILIH WARNA (jika produk punya variasi warna) === --}}
                @if(!empty($colors))
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2" id="colorOptions">
                        @foreach($colors as $color)
                        {{-- Tombol pilih warna --}}
                        <button type="button"
                            class="color-btn px-5 py-2.5 border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:border-pink-300 hover:text-pink-500 transition-all"
                            data-value="{{ $color }}"
                            onclick="selectOption(this, 'color')">
                            {{ $color }}
                        </button>
                        @endforeach
                    </div>
                    {{-- Hidden input untuk nyimpan warna yang dipilih --}}
                    <input type="hidden" name="color" id="selectedColor" value="">
                </div>
                @endif

                {{-- === INPUT JUMLAH BARANG (Quantity) === --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                    <div class="flex items-center border-2 border-gray-200 rounded-lg w-fit">
                        {{-- Tombol Kurang --}}
                        <button type="button" onclick="changeQty(-1)" class="px-4 py-2.5 text-gray-500 hover:text-pink-500 hover:bg-pink-50 transition rounded-l-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ $product->stock }}"
                            class="w-16 text-center py-2.5 border-x-2 border-gray-200 text-sm font-semibold focus:outline-none">
                        {{-- Tombol Tambah --}}
                        <button type="button" onclick="changeQty(1)" class="px-4 py-2.5 text-gray-500 hover:text-pink-500 hover:bg-pink-50 transition rounded-r-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>

                {{-- === TOMBOL SUBMIT: Tambah ke Keranjang === --}}
                {{-- Tombol akan disable jika variasi (size/color) belum dipilih, dicek oleh JS checkForm() --}}
                <button type="submit" id="addToCartBtn"
                    class="w-full bg-pink-500 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-pink-600 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-pink-200">
                    <span id="btnText">Tambah ke Keranjang</span>
                </button>
            </form>
            @else
                {{-- Jika stok habis, tampilkan pesan peringatan --}}
                <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                    <p class="text-red-600 font-semibold">Maaf, produk ini sedang habis.</p>
                    <p class="text-sm text-red-500 mt-1">Silakan cek kembali nanti.</p>
                </div>
            @endif

            {{-- === DESKRIPSI PRODUK === --}}
            @if($product->description)
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-2">Deskripsi Produk</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $product->description }}</p>
            </div>
            @endif

            {{-- === DETAIL PRODUK (SKU, Bahan, Ukuran, Warna) === --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-3">Detail Produk</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    {{-- SKU (Kode unik produk) --}}
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500">SKU</p>
                        <p class="font-medium text-gray-800">{{ $product->sku }}</p>
                    </div>
                    {{-- Bahan (jika ada) --}}
                    @if($product->material)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500">Bahan</p>
                        <p class="font-medium text-gray-800">{{ $product->material }}</p>
                    </div>
                    @endif
                    {{-- Ukuran tersedia --}}
                    @if(!empty($sizes))
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500">Ukuran</p>
                        <p class="font-medium text-gray-800">{{ implode(', ', $sizes) }}</p>
                    </div>
                    @endif
                    {{-- Warna tersedia --}}
                    @if(!empty($colors))
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-gray-500">Warna</p>
                        <p class="font-medium text-gray-800">{{ implode(', ', $colors) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- === PRODUK TERKAIT (Related Products) dari kategori yang sama === --}}
    @if($related->count())
    <div class="mt-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Produk Terkait</h2>
            <a href="{{ route('store.index', ['category' => $product->category_id]) }}" class="text-sm text-pink-500 hover:text-pink-600 font-medium">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($related as $rel)
            {{-- Card produk terkait, bisa diklik ke halaman detail --}}
            <a href="{{ route('store.show', $rel) }}" class="group bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all hover:-translate-y-1">
                <div class="aspect-[4/3] bg-gradient-to-br from-pink-50 to-purple-50 overflow-hidden">
                    <img src="{{ $rel->image }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-3">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-pink-500 truncate">{{ $rel->name }}</p>
                    {{-- effective_price adalah accessor yang otomatis pilih sale_price atau price --}}
                    <p class="text-sm text-pink-500 font-bold mt-1">Rp {{ number_format($rel->effective_price, 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- === JAVASCRIPT UNTUK INTERAKSI HALAMAN === --}}
@push('scripts')
<script>
// Fungsi: Memilih opsi variasi (ukuran/warna) dengan toggle class CSS
function selectOption(btn, type) {
    // Hapus class 'terpilih' dari semua tombol di grup yang sama
    document.querySelectorAll('.' + type + '-btn').forEach(b => {
        b.classList.remove('border-pink-500', 'bg-pink-50', 'text-pink-600');
        b.classList.add('border-gray-200', 'text-gray-600');
    });
    // Tandai tombol yang diklik sebagai terpilih
    btn.classList.remove('border-gray-200', 'text-gray-600');
    btn.classList.add('border-pink-500', 'bg-pink-50', 'text-pink-600');
    // Simpan nilai pilihan ke hidden input
    document.getElementById('selected' + type.charAt(0).toUpperCase() + type.slice(1)).value = btn.dataset.value;
    checkForm(); // Validasi form setelah pilih
}

// Fungsi: Ubah jumlah barang ( + / - )
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1; // Minimal 1
    if (val > {{ $product->stock }}) val = {{ $product->stock }}; // Maksimal stok
    input.value = val;
}

// Fungsi: Validasi form sebelum submit (pastikan ukuran/warna sudah dipilih kalau ada)
function checkForm() {
    const btn = document.getElementById('addToCartBtn');
    const needsSize = {{ !empty($sizes) ? 'true' : 'false' }};
    const needsColor = {{ !empty($colors) ? 'true' : 'false' }};
    const sizeOk = !needsSize || document.getElementById('selectedSize').value !== '';
    const colorOk = !needsColor || document.getElementById('selectedColor').value !== '';
    btn.disabled = !(sizeOk && colorOk);
}

// Enable form check on load
checkForm();
</script>
@endpush
@endsection
