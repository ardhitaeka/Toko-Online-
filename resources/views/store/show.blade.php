@extends('layouts.store')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full rounded-xl object-cover h-[500px]">
        </div>
        <div>
            <p class="text-sm text-pink-500 font-semibold mb-2">{{ $product->category->name ?? '' }}</p>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
            <div class="flex items-center gap-3 mb-4">
                @if($product->is_on_sale)
                    <span class="text-3xl text-pink-500 font-bold">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    <span class="text-xl text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="bg-pink-100 text-pink-600 text-xs font-semibold px-2 py-1 rounded">Sale</span>
                @else
                    <span class="text-3xl text-gray-800 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>

            <div class="space-y-2 text-sm text-gray-600 mb-6">
                <p><span class="font-semibold text-gray-700">SKU:</span> {{ $product->sku }}</p>
                <p><span class="font-semibold text-gray-700">Stok:</span> {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}</p>
                @if($product->size)<p><span class="font-semibold text-gray-700">Ukuran:</span> {{ $product->size }}</p>@endif
                @if($product->color)<p><span class="font-semibold text-gray-700">Warna:</span> {{ $product->color }}</p>@endif
                @if($product->material)<p><span class="font-semibold text-gray-700">Bahan:</span> {{ $product->material }}</p>@endif
            </div>

            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Deskripsi</h3>
                <p class="text-gray-600 text-sm">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            @if($product->stock > 0)
            <form method="POST" action="{{ route('store.add-cart', $product) }}" class="flex gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                        class="w-20 px-3 py-3 border border-gray-300 rounded-lg text-center">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                    <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-lg font-semibold hover:bg-pink-600 transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
            @else
                <p class="text-red-500 font-semibold">Stok habis!</p>
            @endif
        </div>
    </div>

    @if($related->count())
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $rel)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                <a href="{{ route('store.show', $rel) }}">
                    <img src="{{ $rel->image }}" alt="{{ $rel->name }}" class="w-full h-48 object-cover">
                </a>
                <div class="p-3">
                    <p class="text-sm text-gray-800 font-semibold">{{ $rel->name }}</p>
                    <p class="text-pink-500 font-bold text-sm">Rp {{ number_format($rel->effective_price, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
