@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Perbarui data produk')
@section('nav-products', 'bg-gray-800 text-white')

@section('content')
<div class="max-w-3xl">
    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" min="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" min="0" step="1000" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Diskon</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500" min="0" step="1000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran</label>
                    <input type="text" name="size" value="{{ old('size', $product->size) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                    <input type="text" name="color" value="{{ old('color', $product->color) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bahan</label>
                    <input type="text" name="material" value="{{ old('material', $product->material) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                    @if($product->image)<p class="mt-1 text-xs text-gray-500">Gambar saat ini: {{ basename($product->image) }}</p>@endif
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="flex items-center space-x-6">
                    <label class="flex items-center"><input type="checkbox" name="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 text-pink-600 rounded mr-2">Aktif</label>
                    <label class="flex items-center"><input type="checkbox" name="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-pink-600 rounded mr-2">Produk Unggulan</label>
                </div>
            </div>
            <div class="flex items-center space-x-3 pt-6 mt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg text-sm hover:bg-pink-700">Perbarui</button>
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
