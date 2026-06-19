{{-- Halaman Daftar Pesanan Saya --}}
@extends('layouts.store')
@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- === HEADER HALAMAN === --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
            {{-- Tampilkan total jumlah pesanan --}}
            <p class="text-sm text-gray-500 mt-1">{{ $orders->total() > 0 ? 'Total ' . $orders->total() . ' pesanan' : 'Belum ada pesanan' }}</p>
        </div>
        <a href="{{ route('store.index') }}" class="text-sm text-pink-500 hover:text-pink-600 font-medium">Belanja Lagi</a>
    </div>

    {{-- === KOSONG: Belum ada pesanan === --}}
    @if($orders->isEmpty())
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 mb-6">Ayo mulai belanja fashion terbaru!</p>
            <a href="{{ route('store.index') }}" class="inline-flex bg-pink-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-pink-600 transition shadow-lg shadow-pink-200">
                Belanja Sekarang
            </a>
        </div>
    @else
        {{-- === DAFTAR PESANAN === --}}
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition">
                {{-- Header: Nomor Order & Status --}}
                <div class="px-6 py-4 flex flex-wrap justify-between items-center gap-3 border-b border-gray-50">
                    <div>
                        {{-- Nomor pesanan unik --}}
                        <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                        {{-- Tanggal pesan --}}
                        <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    {{-- Status badge --}}
                    <div class="flex items-center gap-2">
                        {{-- Status pesanan (Pending/Processing/Shipped/Completed/Cancelled) --}}
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $order->status_color }}">{{ $order->status_label }}</span>
                        {{-- Status pembayaran (Paid/Unpaid) --}}
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $order->payment_status_label }}</span>
                    </div>
                </div>

                {{-- Produk dalam pesanan --}}
                <div class="px-6 py-4">
                    <div class="flex flex-wrap gap-3">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-2 pr-4">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-white flex-shrink-0">
                                {{-- Gambar produk (pakai placeholder jika tidak ada) --}}
                                <img src="{{ $item->product->image ?? 'https://picsum.photos/seed/placeholder/60' }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="text-sm min-w-0">
                                {{-- Nama produk --}}
                                <p class="font-semibold text-gray-800 truncate max-w-[180px]">{{ $item->product_name }}</p>
                                {{-- Jumlah, ukuran, warna --}}
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span>x{{ $item->quantity }}</span>
                                    @if($item->size)<span>Size: {{ $item->size }}</span>@endif
                                    @if($item->color)<span>Color: {{ $item->color }}</span>@endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer: Link detail & Total --}}
                <div class="px-6 py-4 bg-gray-50 flex flex-wrap justify-between items-center gap-3">
                    {{-- Tombol lihat detail pesanan --}}
                    <a href="{{ route('store.orders.show', $order) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-pink-500 hover:text-pink-600">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    {{-- Total harga pesanan --}}
                    <p class="font-bold text-gray-900">Total: <span class="text-pink-500">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                </div>
            </div>
            @endforeach
        </div>
        {{-- === PAGINATION === --}}
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
