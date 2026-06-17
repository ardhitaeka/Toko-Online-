@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Analisis performa toko')
@section('nav-reports', 'bg-gray-800 text-white')

@section('content')
<!-- Period Filter -->
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="flex items-center space-x-3">
        <select name="period" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini ({{ now()->format('d M Y') }})</option>
            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulan Ini ({{ now()->format('F Y') }})</option>
            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahun Ini ({{ now()->year }})</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Filter</button>
    </form>
    <a href="{{ route('reports.export-excel', ['period' => $period]) }}"
       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Download Excel
    </a>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <p class="text-sm font-medium text-gray-500">Rata-rata Nilai Order</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <!-- Top Products -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Produk Terlaris</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Qty Terjual</th>
                        <th class="px-6 py-3">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topProducts as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->product_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $item->total_qty }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Kategori Terlaris</h3>
        </div>
        <div class="p-6 space-y-5">
            @forelse($topCategories as $category)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                    <span class="text-sm text-gray-500">{{ $category->total_qty }} terjual</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full bg-pink-500" style="width: {{ $topCategories->first() ? ($category->total_qty / $topCategories->first()->total_qty * 100) : 0 }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-500 text-center py-4">Belum ada data</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Recent Paid Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru (Lunas)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                        <th class="px-6 py-3">Order</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm"><a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">{{ $order->order_number }}</a></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Stok Menipis</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ $lowStockProducts->count() }} produk</span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($lowStockProducts as $product)
            <div class="p-6 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                    <p class="text-xs text-gray-500">{{ $product->category->name }} &middot; SKU: {{ $product->sku }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold {{ $product->stock <= 3 ? 'text-red-600' : 'text-orange-600' }}">{{ $product->stock }} sisa</p>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-sm text-gray-500">Semua stok aman</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
