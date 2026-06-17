@extends('layouts.store')
@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('store.orders') }}" class="text-pink-500 hover:text-pink-700">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap justify-between items-start mb-4">
            <div>
                <p class="text-xl font-bold text-gray-800">{{ $order->order_number }}</p>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $order->status_color }}">{{ $order->status_label }}</span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $order->payment_status_label }}</span>
            </div>
        </div>
        <div class="text-sm text-gray-600 space-y-1">
            <p><span class="font-semibold">Metode Bayar:</span> {{ $order->payment_method_label }}</p>
            <p><span class="font-semibold">Alamat:</span> {{ $order->shipping_address }}</p>
            @if($order->notes)<p><span class="font-semibold">Catatan:</span> {{ $order->notes }}</p>@endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Harga</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->size }} {{ $item->color }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr><td colspan="3" class="px-6 py-2 text-right text-gray-600">Subtotal</td><td class="px-6 py-2 text-right font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                <tr><td colspan="3" class="px-6 py-2 text-right text-gray-600">Ongkir</td><td class="px-6 py-2 text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                @if($order->discount > 0)<tr><td colspan="3" class="px-6 py-2 text-right text-gray-600">Diskon</td><td class="px-6 py-2 text-right text-green-600">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td></tr>@endif
                <tr class="bg-pink-50"><td colspan="3" class="px-6 py-3 text-right font-bold text-gray-800">Total</td><td class="px-6 py-3 text-right font-bold text-xl text-pink-500">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
