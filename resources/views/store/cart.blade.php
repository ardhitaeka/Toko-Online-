{{-- Halaman Keranjang Belanja --}}
@extends('layouts.store')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- === HEADER KERANJANG === --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h1>
            {{-- Tampilkan jumlah item di keranjang --}}
            <p class="text-sm text-gray-500 mt-1">{{ count($cart) > 0 ? count($cart) . ' item di keranjang' : 'Belanja yuk!' }}</p>
        </div>
        {{-- Link lanjut belanja (hanya muncul jika keranjang tidak kosong) --}}
        @if(!empty($cart))
        <a href="{{ route('store.index') }}" class="hidden sm:inline-flex items-center gap-2 text-sm text-pink-500 hover:text-pink-600 font-medium">
            + Lanjut Belanja
        </a>
        @endif
    </div>

    {{-- === KERANJANG KOSONG === --}}
    @if(empty($cart))
        <div class="text-center py-20">
            {{-- Icon keranjang kosong --}}
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Keranjang Anda Kosong</h2>
            <p class="text-gray-500 mb-6">Yuk, isi dengan produk fashion terbaru!</p>
            <a href="{{ route('store.index') }}" class="inline-flex bg-pink-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-pink-600 transition shadow-lg shadow-pink-200">Belanja Sekarang</a>
        </div>
    @else
        {{-- === FORM UPDATE KERANJANG === --}}
        <form method="POST" action="{{ route('store.update-cart') }}" id="cartForm">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- === DAFTAR ITEM DI KERANJANG (Kolom kiri, 2/3 lebar) === --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $key => $item)
                    {{-- Key adalah index unik dari session cart (contoh: product_id.size.color) --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex gap-4 hover:shadow-md transition" data-cart-key="{{ $key }}">
                        {{-- Gambar Produk --}}
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover object-center">
                        </div>

                        {{-- Info Produk --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <div class="min-w-0">
                                    {{-- Nama Produk --}}
                                    <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $item['name'] }}</h3>
                                    {{-- Badge Ukuran & Warna (jika ada) --}}
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @if($item['size'])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-50 text-pink-600">{{ $item['size'] }}</span>
                                        @endif
                                        @if($item['color'])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-600">{{ $item['color'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Harga per item --}}
                                <p class="font-semibold text-gray-900 text-sm whitespace-nowrap">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>

                            <div class="flex justify-between items-center mt-3">
                                {{-- === INPUT JUMLAH (Quantity) === --}}
                                <div class="flex items-center border border-gray-200 rounded-lg">
                                    <button type="button" onclick="changeQty(this, -1)" class="px-2.5 py-1.5 text-gray-400 hover:text-pink-500 hover:bg-pink-50 transition rounded-l-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <input type="number" name="quantities[{{ $key }}]" value="{{ $item['quantity'] }}" min="1"
                                        class="w-10 text-center py-1.5 text-xs font-semibold border-x border-gray-200 focus:outline-none pointer-events-none">
                                    <button type="button" onclick="changeQty(this, 1)" class="px-2.5 py-1.5 text-gray-400 hover:text-pink-500 hover:bg-pink-50 transition rounded-r-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>

                                <div class="flex items-center gap-3">
                                    {{-- Subtotal per item (price * quantity) --}}
                                    <p class="text-sm font-bold text-gray-900 item-subtotal">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    {{-- Tombol Hapus item dari keranjang --}}
                                    <a href="{{ route('store.remove-cart', $key) }}"
                                        onclick="event.preventDefault(); if(confirm('Hapus item ini?')) document.getElementById('remove-{{ md5($key) }}').submit();"
                                        class="text-gray-300 hover:text-red-500 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- === SIDEBAR: RINGKASAN BELANJA (Kolom kanan, 1/3 lebar) === --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-5 pb-4 border-b border-gray-100">Ringkasan Belanja</h3>

                        <div class="space-y-3 text-sm mb-5" id="orderSummary">
                            {{-- Subtotal semua item --}}
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal (<span id="itemCount">{{ collect($cart)->sum('quantity') }}</span> item)</span>
                                <span class="font-semibold text-gray-900" id="subtotalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            {{-- Ongkos Kirim (fixed Rp 15.000) --}}
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="font-semibold text-gray-900">Rp 15.000</span>
                            </div>
                            {{-- Total Keseluruhan (subtotal + ongkir) --}}
                            <div class="border-t border-gray-100 pt-3 flex justify-between">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-lg text-pink-500" id="totalDisplay">Rp {{ number_format($subtotal + 15000, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            {{-- Tombol update cart & lanjut ke checkout --}}
                            <button type="submit" class="w-full bg-pink-500 text-white text-center py-3 rounded-xl font-bold text-sm hover:bg-pink-600 transition shadow-lg shadow-pink-200">
                                Perbarui & Checkout
                            </button>
                            <a href="{{ route('store.index') }}" class="block w-full text-center py-3 rounded-xl font-semibold text-sm text-gray-600 hover:text-pink-500 hover:bg-pink-50 transition border-2 border-gray-200 hover:border-pink-200">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- === FORM HAPUS (tersembunyi) untuk setiap item === --}}
        {{-- Dibutuhkan karena Laravel tidak support DELETE via link biasa --}}
        @foreach($cart as $key => $item)
        <form method="POST" action="{{ route('store.remove-cart', $key) }}" id="remove-{{ md5($key) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endforeach
    @endif
</div>

{{-- === JAVASCRIPT UNTUK INTERAKSI KERANJANG (update qty & hitung ulang total) === --}}
@push('scripts')
<script>
// Fungsi: Ubah jumlah item (tombol +/-)
function changeQty(btn, delta) {
    const input = btn.parentElement.querySelector('input[type=number]');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1; // Minimal 1
    input.value = val;
    updateItemDisplay(btn, val); // Update tampilan subtotal
}

// Fungsi: Update tampilan subtotal per item dan total keseluruhan
function updateItemDisplay(btn, qty) {
    const card = btn.closest('[data-cart-key]');
    // Ambil harga dari text (misal: "Rp 50.000" -> 50000)
    const priceText = card.querySelector('.font-semibold.text-gray-900.text-sm.whitespace-nowrap').textContent;
    const price = parseInt(priceText.replace(/[^0-9]/g, ''));
    const subtotal = price * qty;
    card.querySelector('.item-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    recalculateTotal(); // Hitung ulang total semua item
}

// Fungsi: Hitung ulang total semua item di keranjang
function recalculateTotal() {
    const items = document.querySelectorAll('[data-cart-key]');
    let totalQty = 0;
    let subtotal = 0;
    items.forEach(card => {
        const qty = parseInt(card.querySelector('input[type=number]').value);
        const priceText = card.querySelector('.font-semibold.text-gray-900.text-sm.whitespace-nowrap').textContent;
        const price = parseInt(priceText.replace(/[^0-9]/g, ''));
        totalQty += qty;
        subtotal += price * qty;
    });
    // Update tampilan jumlah item, subtotal, dan total
    document.getElementById('itemCount').textContent = totalQty;
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('totalDisplay').textContent = 'Rp ' + (subtotal + 15000).toLocaleString('id-ID');
}
</script>
@endpush
@endsection
