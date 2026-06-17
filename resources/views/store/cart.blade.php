@extends('layouts.store')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>

    @if(empty($cart))
        <div class="text-center py-16">
            <p class="text-gray-500 mb-4">Keranjang Anda masih kosong.</p>
            <a href="{{ route('store.index') }}" class="bg-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-pink-600">Belanja Sekarang</a>
        </div>
    @else
        <form method="POST" action="{{ route('store.update-cart') }}">
            @csrf
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Harga</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Jumlah</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600">Subtotal</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($cart as $id => $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item['image'] }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $item['size'] ?? '' }} {{ $item['color'] ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1" class="w-16 px-2 py-1 border rounded text-center">
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="{{ route('store.remove-cart', $id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-700">Update Keranjang</button>
                <div class="text-right">
                    <p class="text-gray-600">Subtotal: <span class="font-bold text-xl text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></p>
                    <a href="{{ route('store.checkout') }}" class="inline-block mt-3 bg-pink-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-pink-600">Checkout</a>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
