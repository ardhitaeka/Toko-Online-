@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di FashionStore Admin!')
@section('nav-dashboard', 'bg-gray-800 text-white')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Total Produk -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Produk</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_products']) }}</p>
                <p class="text-xs text-green-600 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    +12 bulan ini
                </p>
            </div>
            <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
    </div>

    <!-- Total Pesanan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_orders']) }}</p>
                <p class="text-xs text-green-600 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    +8% dari kemarin
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
    </div>

    <!-- Pendapatan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pendapatan</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <p class="text-xs text-green-600 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    +15% dari kemarin
                </p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Pelanggan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pelanggan</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_customers']) }}</p>
                <p class="text-xs text-green-600 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    +24 bulan ini
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <!-- Recent Orders Table -->
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
            <a href="{{ route('orders.index') }}" class="text-sm text-pink-600 hover:text-pink-700 font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm"><a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">{{ $order->order_number }}</a></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $order->items->count() }} item</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Kategori Terlaris</h3>
        </div>
        <div class="p-6 space-y-4">
            @foreach($topCategories as $index => $category)
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0">
                    @if(isset($category['image']) && $category['image'])
                    <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                        <span class="text-xs font-bold text-pink-600">{{ strtoupper(substr($category['name'], 0, 1)) }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 truncate">{{ $category['name'] }}</span>
                        <span class="text-sm text-gray-500">{{ $category['total'] }} produk</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $index === 0 ? 'bg-pink-500' : ($index === 1 ? 'bg-blue-500' : ($index === 2 ? 'bg-purple-500' : ($index === 3 ? 'bg-yellow-500' : 'bg-gray-400'))) }}" style="width: {{ $category['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Low Stock Alert -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Stok Menipis</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                {{ count($lowStockProducts) }} produk
            </span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($lowStockProducts as $product)
            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0">
                        @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-orange-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold {{ $product->stock <= 3 ? 'text-red-600' : 'text-orange-600' }}">{{ $product->stock }} sisa</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions & Revenue Chart Placeholder -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('products.create') }}" class="flex items-center p-4 bg-pink-50 hover:bg-pink-100 rounded-xl transition-colors group">
                    <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-pink-700">Tambah Produk</span>
                </a>
                <a href="{{ route('orders.index') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors group">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Lihat Pesanan</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors group">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Daftar Produk</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors group">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Laporan</span>
                </a>
            </div>
        </div>

        <!-- Revenue Summary -->
        <div class="bg-gradient-to-br from-pink-500 to-pink-700 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Ringkasan Penjualan</h3>
                <span class="text-xs bg-white/20 px-2.5 py-1 rounded-full">Bulan Ini</span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-pink-100 text-sm">Penjualan Online</span>
                    <span class="font-semibold">Rp 62.500.000</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-pink-100 text-sm">Penjualan Offline</span>
                    <span class="font-semibold">Rp 25.000.000</span>
                </div>
                <div class="border-t border-pink-400 pt-3 flex justify-between items-center">
                    <span class="font-medium">Total Pendapatan</span>
                    <span class="text-xl font-bold">Rp 87.500.000</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
