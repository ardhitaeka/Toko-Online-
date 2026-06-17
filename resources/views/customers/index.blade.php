@extends('layouts.app')

@section('title', 'Pelanggan')
@section('page-title', 'Pelanggan')
@section('page-subtitle', 'Kelola data pelanggan')
@section('nav-customers', 'bg-gray-800 text-white')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('customers.index') }}" class="flex items-center space-x-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, telepon..." class="pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500 w-64">
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>
    <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700 flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Pelanggan
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
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Telepon</th>
                    <th class="px-6 py-3">Alamat</th>
                    <th class="px-6 py-3">Pesanan</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center text-sm font-bold text-pink-600 mr-3">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                            <span class="text-sm font-medium text-gray-900">{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($customer->address, 30) ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->orders_count }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('customers.edit', $customer) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada pelanggan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $customers->withQueryString()->links() }}</div>
</div>
@endsection
