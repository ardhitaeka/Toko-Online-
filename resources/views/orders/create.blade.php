@extends('layouts.app')

@section('title', 'Buat Pesanan')
@section('page-title', 'Buat Pesanan Baru')
@section('page-subtitle', 'Tambahkan pesanan pelanggan')
@section('nav-orders', 'bg-gray-800 text-white')

@section('content')
<div class="max-w-4xl">
    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <!-- Customer & Payment -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Info Pesanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                                <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>COD</option>
                                <option value="e-wallet" {{ old('payment_method') === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ongkir</label>
                            <input type="number" name="shipping_cost" value="{{ old('shipping_cost', 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" min="0" step="1000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diskon</label>
                            <input type="number" name="discount" value="{{ old('discount', 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" min="0" step="1000">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                            <textarea name="shipping_address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('shipping_address') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-800">Item Pesanan</h3>
                        <button type="button" onclick="addItem()" class="px-3 py-1.5 bg-pink-50 text-pink-600 rounded-lg text-xs hover:bg-pink-100">+ Tambah Item</button>
                    </div>
                    <div id="order-items" class="space-y-4">
                        <div class="item-row grid grid-cols-12 gap-3 items-end">
                            <div class="col-span-5">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Produk</label>
                                <select name="items[0][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->effective_price }}">{{ $product->name }} (Rp {{ number_format($product->effective_price, 0, ',', '.') }}) - Stok: {{ $product->stock }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Qty</label>
                                <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Ukuran</label>
                                <input type="text" name="items[0][size]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Warna</label>
                                <input type="text" name="items[0][color]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                            </div>
                            <div class="col-span-1">
                                <button type="button" onclick="removeItem(this)" class="p-2 text-red-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Ringkasan</h3>
                    <p class="text-sm text-gray-500 mb-6">Isi data pesanan di sebelah kiri, lalu klik "Buat Pesanan".</p>
                    <div class="flex items-center space-x-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700">Buat Pesanan</button>
                        <a href="{{ route('orders.index') }}" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300 text-center">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;
function addItem() {
    const container = document.getElementById('order-items');
    const html = `<div class="item-row grid grid-cols-12 gap-3 items-end">
        <div class="col-span-5">
            <select name="items[${itemIndex}][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (Rp {{ number_format($product->effective_price, 0, ',', '.') }}) - Stok: {{ $product->stock }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
        </div>
        <div class="col-span-2">
            <input type="text" name="items[${itemIndex}][size]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div>
        <div class="col-span-2">
            <input type="text" name="items[${itemIndex}][color]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div>
        <div class="col-span-1">
            <button type="button" onclick="removeItem(this)" class="p-2 text-red-400 hover:text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    itemIndex++;
}
function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) btn.closest('.item-row').remove();
}
</script>
@endsection
