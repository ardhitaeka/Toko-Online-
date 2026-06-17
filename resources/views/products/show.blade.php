@extends('layouts.app')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')
@section('page-subtitle', $product->name)
@section('nav-products', 'bg-gray-800 text-white')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <!-- Product Image & Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="h-80 md:h-auto bg-gradient-to-br from-pink-50 to-purple-50 relative overflow-hidden">
                    @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @if($product->is_on_sale)
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">SALE</span>
                    @endif
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-20 h-20 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $product->category->name }} &middot; SKU: {{ $product->sku }}</p>
                        </div>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="mt-4 flex items-center space-x-4">
                        <div>
                            <p class="text-sm text-gray-500">Harga</p>
                            <p class="text-xl font-bold text-pink-600">Rp {{ number_format($product->effective_price, 0, ',', '.') }}</p>
                            @if($product->is_on_sale)
                            <p class="text-sm text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <div class="border-l pl-4">
                            <p class="text-sm text-gray-500">Stok</p>
                            <p class="text-xl font-bold {{ $product->stock <= 5 ? 'text-red-600' : 'text-gray-900' }}">{{ $product->stock }}</p>
                        </div>
                    </div>
                    @if($product->description)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 font-medium">Deskripsi</p>
                        <p class="text-sm text-gray-700 mt-1">{{ $product->description }}</p>
                    </div>
                    @endif
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @if($product->size)<div class="bg-gray-50 rounded-lg p-2"><p class="text-xs text-gray-500">Ukuran</p><p class="text-sm font-medium">{{ $product->size }}</p></div>@endif
                        @if($product->color)<div class="bg-gray-50 rounded-lg p-2"><p class="text-xs text-gray-500">Warna</p><p class="text-sm font-medium">{{ $product->color }}</p></div>@endif
                        @if($product->material)<div class="bg-gray-50 rounded-lg p-2"><p class="text-xs text-gray-500">Bahan</p><p class="text-sm font-medium">{{ $product->material }}</p></div>@endif
                        <div class="bg-gray-50 rounded-lg p-2"><p class="text-xs text-gray-500">Unggulan</p><p class="text-sm font-medium">{{ $product->is_featured ? 'Ya' : 'Tidak' }}</p></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Pesanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                            <th class="px-6 py-3">Order</th>
                            <th class="px-6 py-3">Pelanggan</th>
                            <th class="px-6 py-3">Qty</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($product->orderItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm"><a href="{{ route('orders.show', $item->order) }}" class="text-blue-600 hover:underline">{{ $item->order->order_number }}</a></td>
                            <td class="px-6 py-4 text-sm">{{ $item->order->customer->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->order->status_color }}">{{ $item->order->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Detail Produk</h3>
            <dl class="space-y-3">
                @if($product->size)<div class="flex justify-between"><dt class="text-sm text-gray-500">Ukuran</dt><dd class="text-sm font-medium text-gray-900">{{ $product->size }}</dd></div>@endif
                @if($product->color)<div class="flex justify-between"><dt class="text-sm text-gray-500">Warna</dt><dd class="text-sm font-medium text-gray-900">{{ $product->color }}</dd></div>@endif
                @if($product->material)<div class="flex justify-between"><dt class="text-sm text-gray-500">Bahan</dt><dd class="text-sm font-medium text-gray-900">{{ $product->material }}</dd></div>@endif
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Unggulan</dt><dd class="text-sm font-medium text-gray-900">{{ $product->is_featured ? 'Ya' : 'Tidak' }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Dibuat</dt><dd class="text-sm font-medium text-gray-900">{{ $product->created_at->format('d M Y') }}</dd></div>
            </dl>
        </div>
        <div class="flex flex-col space-y-3">
            <a href="{{ route('products.edit', $product) }}" class="w-full px-4 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700 text-center">Edit Produk</a>
            <a href="{{ route('products.index') }}" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300 text-center">Kembali</a>
        </div>
    </div>
</div>
@endsection
