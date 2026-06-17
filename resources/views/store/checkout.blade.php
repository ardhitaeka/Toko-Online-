@extends('layouts.store')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('store.order') }}" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Pengiriman</label>
                    <textarea name="shipping_address" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">{{ auth()->user()->address ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="payment_method" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="transfer">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="cod">COD (Bayar di Tempat)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
                </div>
                <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-lg font-semibold hover:bg-pink-600">Buat Pesanan</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 h-fit">
            <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-3 text-sm">
                @foreach($cart as $item)
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                    <span class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                </div>
                @endforeach
                <hr>
                <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Ongkir</span><span>Rp {{ number_format($shipping, 0, ',', '.') }}</span></div>
                <hr>
                <div class="flex justify-between text-lg font-bold text-pink-500"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
