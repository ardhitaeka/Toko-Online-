@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Pesanan ' . $order->order_number)
@section('page-subtitle', 'Detail informasi pesanan')
@section('nav-orders', 'bg-gray-800 text-white')

@section('content')
@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Item Pesanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Qty</th>
                            <th class="px-6 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product_name }}" class="w-12 h-12 rounded-lg object-cover shrink-0">
                                    @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($item->size)Size: {{ $item->size }}@endif
                                            @if($item->color) &middot; Warna: {{ $item->color }}@endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr><td colspan="3" class="px-6 py-3 text-sm text-gray-500 text-right">Subtotal</td><td class="px-6 py-3 text-sm font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                        <tr><td colspan="3" class="px-6 py-3 text-sm text-gray-500 text-right">Ongkir</td><td class="px-6 py-3 text-sm font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                        @if($order->discount > 0)<tr><td colspan="3" class="px-6 py-3 text-sm text-green-600 text-right">Diskon</td><td class="px-6 py-3 text-sm font-medium text-green-600">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td></tr>@endif
                        <tr class="bg-gray-50"><td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-900 text-right">Total</td><td class="px-6 py-4 text-lg font-bold text-pink-600">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Catatan</h3>
            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Order Info Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Info Pesanan</h3>
            <dl class="space-y-3">
                <div class="flex justify-between"><dt class="text-sm text-gray-500">No. Order</dt><dd class="text-sm font-medium text-gray-900">{{ $order->order_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Pelanggan</dt><dd class="text-sm font-medium text-gray-900">{{ $order->user->name ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Email</dt><dd class="text-sm text-gray-700">{{ $order->user->email ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Telepon</dt><dd class="text-sm text-gray-700">{{ $order->user->phone ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Tanggal</dt><dd class="text-sm text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</dd></div>
                <div class="flex justify-between"><dt class="text-sm text-gray-500">Metode Bayar</dt><dd class="text-sm font-medium text-gray-900">{{ $order->payment_method_label }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Update Status</h3>
            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="space-y-3">
                @csrf @method('PATCH')
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Update Status</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Status Pembayaran</h3>
            <form method="POST" action="{{ route('orders.update-payment', $order) }}" class="space-y-3">
                @csrf @method('PATCH')
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">Update Pembayaran</button>
            </form>
        </div>

        <a href="{{ route('orders.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300 text-center">Kembali</a>
    </div>
</div>
@endsection
