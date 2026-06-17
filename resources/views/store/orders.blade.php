@extends('layouts.store')
@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pesanan Saya</h1>

    @if($orders->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 mb-4">Anda belum memiliki pesanan.</p>
            <a href="{{ route('store.index') }}" class="bg-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-pink-600">Belanja Sekarang</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex flex-wrap justify-between items-center mb-4">
                    <div>
                        <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $order->status_color }}">{{ $order->status_label }}</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $order->payment_status_label }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 mb-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-2 pr-4">
                        <img src="{{ $item->product->image ?? 'https://picsum.photos/seed/placeholder/60' }}" class="w-10 h-10 rounded object-cover">
                        <div class="text-sm">
                            <p class="font-semibold text-gray-700">{{ $item->product_name }}</p>
                            <p class="text-gray-500">x{{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center">
                    <a href="{{ route('store.orders.show', $order) }}" class="text-pink-500 hover:text-pink-700 text-sm font-semibold">Lihat Detail</a>
                    <p class="font-bold text-gray-800">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
