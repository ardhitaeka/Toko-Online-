@extends('layouts.app')

@section('title', 'Pesanan')
@section('page-title', 'Pesanan')
@section('page-subtitle', 'Kelola semua pesanan')
@section('nav-orders', 'bg-gray-800 text-white')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('orders.index') }}" class="flex items-center space-x-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor order / pelanggan..." class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 w-56">
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>
    <a href="{{ route('orders.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Pesanan
    </a>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">No. Order</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Pembayaran</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600"><a href="{{ route('orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a></td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'refunded' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_color }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection
