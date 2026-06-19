{{-- Halaman Detail Pesanan --}}
@extends('layouts.store')
@section('title', 'Detail Pesanan ' . $order->order_number)

{{-- === KONFIGURASI STATUS PESANAN (untuk progress timeline) === --}}
@php
    // Definisi setiap tahap status dengan label, deskripsi, dan icon SVG
    $statusSteps = [
        'pending' => ['label' => 'Pesanan Dibuat', 'desc' => 'Pesanan berhasil dibuat', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'processing' => ['label' => 'Diproses', 'desc' => 'Pesanan sedang diproses', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
        'shipped' => ['label' => 'Dikirim', 'desc' => 'Pesanan dalam perjalanan', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1m2 1h10m2-4l3-3m0 0l-3-3m3 3H10'],
        'completed' => ['label' => 'Selesai', 'desc' => 'Pesanan telah diterima', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    // Urutan status dari awal sampai selesai
    $statusOrder = ['pending', 'processing', 'shipped', 'completed'];
    // Cari posisi status pesanan saat ini dalam urutan
    $currentIdx = array_search($order->status, $statusOrder);
@endphp

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- === HEADER: Tombol Kembali + Judul === --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('store.orders') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-pink-500 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <span class="text-gray-300">|</span>
        <h1 class="text-xl font-bold text-gray-900">Detail Pesanan</h1>
    </div>

    {{-- === KARTU INFO PESANAN === --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <div class="flex flex-wrap justify-between items-start gap-3">
            <div>
                {{-- Nomor Pesanan --}}
                <p class="text-lg font-bold text-gray-900">{{ $order->order_number }}</p>
                {{-- Tanggal pesanan dibuat --}}
                <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            {{-- Status Badges --}}
            <div class="flex items-center gap-2">
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $order->status_color }}">{{ $order->status_label }}</span>
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $order->payment_status_label }}</span>
            </div>
        </div>

        {{-- Grid Info Tambahan --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100 text-sm">
            {{-- Metode Pembayaran --}}
            <div>
                <p class="text-gray-500 mb-0.5">Metode Bayar</p>
                <p class="font-semibold text-gray-900">{{ $order->payment_method_label }}</p>
            </div>
            {{-- Alamat Pengiriman --}}
            <div>
                <p class="text-gray-500 mb-0.5">Alamat</p>
                <p class="font-semibold text-gray-900 text-xs">{{ $order->shipping_address }}</p>
            </div>
            {{-- Status Bayar --}}
            <div>
                <p class="text-gray-500 mb-0.5">Status Bayar</p>
                <p class="font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ $order->payment_status_label }}</p>
            </div>
            {{-- Referensi Pembayaran (jika ada, untuk transfer/e-wallet) --}}
            @if($order->payment_reference)
            <div>
                <p class="text-gray-500 mb-0.5">Ref. Pembayaran</p>
                <p class="font-semibold text-gray-900 font-mono text-xs tracking-wider">{{ $order->payment_reference }}</p>
            </div>
            @endif
            {{-- Catatan Pesanan (jika ada) --}}
            @if($order->notes)
            <div>
                <p class="text-gray-500 mb-0.5">Catatan</p>
                <p class="font-semibold text-gray-900">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- === TIMELINE STATUS PESANAN (Progress Bar) === --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6">Status Pesanan</h3>
        
        {{-- Tampilkan timeline hanya untuk status yg ada di urutan (pending → completed) --}}
        @if(in_array($order->status, ['pending', 'processing', 'shipped', 'completed']))
        <div class="relative">
            {{-- Garis vertikal penghubung antar step --}}
            <div class="absolute top-6 left-6 w-0.5 h-[calc(100%-3rem)] bg-gray-200"></div>
            
            <div class="space-y-8 relative">
                @foreach($statusSteps as $key => $step)
                @php
                    $stepIdx = array_search($key, $statusOrder);
                    $isComplete = $stepIdx <= $currentIdx; // Step sudah dilewati?
                    $isCurrent = $key === $order->status; // Step yg sedang aktif?
                @endphp
                <div class="flex items-start gap-4 relative">
                    {{-- Lingkaran Icon --}}
                    <div class="relative z-10 flex-shrink-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $isComplete ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-400' }} {{ $isCurrent ? 'ring-4 ring-pink-100' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/></svg>
                        </div>
                    </div>
                    {{-- Konten label & deskripsi --}}
                    <div class="pt-2.5">
                        <p class="font-semibold {{ $isComplete ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                        <p class="text-sm {{ $isCurrent ? 'text-pink-500 font-medium' : ($isComplete ? 'text-gray-600' : 'text-gray-300') }}">
                            {{ $isCurrent ? 'Sedang dalam proses ini' : ($isComplete ? $step['desc'] : 'Menunggu') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- Status Dibatalkan --}}
        @elseif($order->status === 'cancelled')
        <div class="text-center py-8">
            <div class="inline-flex w-16 h-16 rounded-full bg-red-100 items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="font-bold text-red-600 text-lg">Pesanan Dibatalkan</p>
            <p class="text-sm text-gray-500 mt-1">Pesanan ini telah dibatalkan.</p>
        </div>
        @endif
    </div>

    {{-- === ITEM PESANAN === --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Item Pesanan</h3>
        </div>
        
        <div class="divide-y divide-gray-50">
            @foreach($order->items as $item)
            <div class="px-6 py-4 flex items-center gap-4">
                {{-- Gambar produk --}}
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-50 flex-shrink-0">
                    <img src="{{ $item->product->image ?? 'https://picsum.photos/seed/placeholder/60' }}" 
                         alt="{{ $item->product_name }}" 
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    {{-- Nama produk --}}
                    <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                        {{-- Badge ukuran --}}
                        @if($item->size)<span class="px-2 py-0.5 bg-pink-50 text-pink-600 rounded-full font-medium">{{ $item->size }}</span>@endif
                        {{-- Badge warna --}}
                        @if($item->color)<span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded-full font-medium">{{ $item->color }}</span>@endif
                        {{-- Jumlah --}}
                        <span>x{{ $item->quantity }}</span>
                    </div>
                </div>
                {{-- Harga & subtotal --}}
                <div class="text-right">
                    <p class="text-sm text-gray-500">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- === RINGKASAN TOTAL === --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 space-y-1.5 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Ongkos Kirim</span>
                <span class="font-semibold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            {{-- Diskon (jika ada) --}}
            @if($order->discount > 0)
            <div class="flex justify-between">
                <span class="text-gray-500">Diskon</span>
                <span class="font-semibold text-green-600">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            {{-- Total --}}
            <div class="flex justify-between pt-2 border-t border-gray-200">
                <span class="font-bold text-gray-900">Total Pesanan</span>
                <span class="font-bold text-lg text-pink-500">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
