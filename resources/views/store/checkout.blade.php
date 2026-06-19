{{-- Halaman Checkout dengan 3 Step (Alamat → Bayar → Review) --}}
@extends('layouts.store')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    {{-- Breadcrumb navigasi --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('store.cart') }}" class="hover:text-pink-500">Keranjang</a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-800 font-medium">Checkout</span>
    </nav>

    {{-- === PROGRESS BAR: 3 Langkah Checkout === --}}
    <div class="mb-8">
        <div class="flex items-center justify-between max-w-2xl mx-auto">
            {{-- Step 1: Alamat --}}
            <div class="flex items-center">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-pink-500 text-white" data-step="1">1</div>
                <span class="ml-3 text-sm font-semibold text-gray-900 step-label" data-step="1">Alamat</span>
            </div>
            <div class="flex-1 mx-4 h-0.5 bg-gray-200 step-line" data-step="1"></div>
            {{-- Step 2: Bayar --}}
            <div class="flex items-center">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500" data-step="2">2</div>
                <span class="ml-3 text-sm font-semibold text-gray-400 step-label" data-step="2">Bayar</span>
            </div>
            <div class="flex-1 mx-4 h-0.5 bg-gray-200 step-line" data-step="2"></div>
            {{-- Step 3: Review --}}
            <div class="flex items-center">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500" data-step="3">3</div>
                <span class="ml-3 text-sm font-semibold text-gray-400 step-label" data-step="3">Review</span>
            </div>
        </div>
    </div>

    {{-- Form Checkout utama --}}
    <form method="POST" action="{{ route('store.order') }}" id="checkoutForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- ==================================================================== --}}
                {{-- STEP 1: ALAMAT PENGIRIMAN --}}
                {{-- ==================================================================== --}}
                <div class="step-panel" id="step1">
                    <div class="bg-white rounded-xl border border-gray-100 p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-sm font-bold">1</div>
                            <h2 class="font-bold text-gray-900">Alamat Pengiriman</h2>
                        </div>
                        <p class="text-sm text-gray-500 mb-5 ml-10">Pastikan alamat pengiriman sudah benar</p>

                        <div class="space-y-4 ml-10">
                            {{-- Input alamat --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-pink-400 transition text-sm"
                                    placeholder="Masukkan alamat lengkap (jalan, kota, provinsi, kode pos)...">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                                {{-- Tampilkan error validasi --}}
                                @error('shipping_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Tombol lanjut ke Step 2 --}}
                            <button type="button" onclick="goToStep(2)" class="bg-pink-500 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-pink-600 transition shadow-lg shadow-pink-200">
                                Lanjut ke Pembayaran
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ==================================================================== --}}
                {{-- STEP 2: METODE PEMBAYARAN --}}
                {{-- ==================================================================== --}}
                <div class="step-panel hidden" id="step2">
                    <div class="bg-white rounded-xl border border-gray-100 p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-sm font-bold">2</div>
                            <h2 class="font-bold text-gray-900">Metode Pembayaran</h2>
                        </div>
                        <p class="text-sm text-gray-500 mb-5 ml-10">Pilih metode pembayaran yang kamu inginkan</p>

                        <div class="space-y-4 ml-10">
                            {{-- 3 Opsi Pembayaran --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" id="paymentMethods">
                                {{-- Transfer Bank --}}
                                <label class="payment-option relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-pink-300 transition has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
                                    <input type="radio" name="payment_method" value="transfer" class="sr-only" onchange="selectPayment(this)" checked required>
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mb-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">Transfer Bank</span>
                                        <span class="text-xs text-gray-500 mt-0.5">BCA / Mandiri / BNI</span>
                                    </div>
                                </label>
                                {{-- E-Wallet --}}
                                <label class="payment-option relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-pink-300 transition has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
                                    <input type="radio" name="payment_method" value="e-wallet" class="sr-only" onchange="selectPayment(this)">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center mb-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">E-Wallet</span>
                                        <span class="text-xs text-gray-500 mt-0.5">GoPay / OVO / Dana</span>
                                    </div>
                                </label>
                                {{-- COD (Cash on Delivery) --}}
                                <label class="payment-option relative border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-pink-300 transition has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
                                    <input type="radio" name="payment_method" value="cod" class="sr-only" onchange="selectPayment(this)">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center mb-2">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">COD</span>
                                        <span class="text-xs text-gray-500 mt-0.5">Bayar di Tempat</span>
                                    </div>
                                </label>
                            </div>
                            {{-- Error validasi payment method --}}
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Tombol navigasi step --}}
                            <div class="flex gap-3">
                                <button type="button" onclick="goToStep(1)" class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 hover:text-pink-500 border-2 border-gray-200 hover:border-pink-200 transition">
                                    Kembali
                                </button>
                                <button type="button" onclick="goToStep(3)" class="bg-pink-500 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-pink-600 transition shadow-lg shadow-pink-200">
                                    Review Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ==================================================================== --}}
                {{-- STEP 3: REVIEW PESANAN (Konfirmasi sebelum order) --}}
                {{-- ==================================================================== --}}
                <div class="step-panel hidden" id="step3">
                    {{-- === KARTU INVOICE === --}}
                    <div class="bg-white rounded-xl border-2 border-gray-100 p-6">
                        {{-- Header Invoice --}}
                        <div class="text-center mb-6 pb-6 border-b border-gray-100">
                            <div class="text-3xl mb-2">&#9827;</div>
                            <h2 class="text-xl font-bold text-gray-900">FashionStore</h2>
                            <p class="text-sm text-gray-500">Invoice Pesanan</p>
                        </div>

                        {{-- Info Pengiriman & Pembayaran --}}
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-sm font-semibold text-gray-700">Dikirim ke:</span>
                            </div>
                            {{-- Alamat dikirim (diisi oleh JS dari input step 1) --}}
                            <p class="text-sm text-gray-600 ml-6" id="reviewAddress"></p>
                            <div class="flex items-center gap-2 mt-2 ml-6">
                                <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                {{-- Metode bayar (diisi oleh JS dari step 2) --}}
                                <span class="text-sm text-gray-600" id="reviewPayment"></span>
                            </div>
                        </div>

                        {{-- === DAFTAR ITEM PESANAN === --}}
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Pesanan Anda</h3>
                            <div class="divide-y divide-gray-100">
                                @foreach($cart as $item)
                                <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                                    {{-- Gambar item --}}
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-50 flex-shrink-0">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        {{-- Nama item --}}
                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $item['name'] }}</p>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                            {{-- Ukuran & Warna --}}
                                            @if($item['size'])<span>Size: {{ $item['size'] }}</span>@endif
                                            @if($item['color'])<span>{{ $item['size'] ? '|' : '' }} Color: {{ $item['color'] }}</span>@endif
                                            {{-- Jumlah --}}
                                            <span>x{{ $item['quantity'] }}</span>
                                        </div>
                                    </div>
                                    {{-- Subtotal per item --}}
                                    <p class="font-semibold text-gray-900 text-sm whitespace-nowrap">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- === CATATAN (opsional) === --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan (opsional)</label>
                            <textarea name="notes" rows="2" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-pink-400 transition text-sm" placeholder="Catatan untuk penjual...">{{ old('notes') }}</textarea>
                        </div>

                        {{-- === RINGKASAN HARGA === --}}
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal ({{ collect($cart)->sum('quantity') }} item)</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 flex justify-between">
                                <span class="font-bold text-gray-900">Total Pesanan</span>
                                <span class="font-bold text-lg text-pink-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="flex gap-3">
                            <button type="button" onclick="goToStep(2)" class="px-6 py-3 rounded-xl font-semibold text-sm text-gray-600 hover:text-pink-500 border-2 border-gray-200 hover:border-pink-200 transition">
                                Kembali
                            </button>
                            {{-- Tombol konfirmasi & buat pesanan --}}
                            <button type="submit" class="flex-1 bg-pink-500 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-pink-600 transition shadow-lg shadow-pink-200">
                                Konfirmasi & Buat Pesanan
                            </button>
                        </div>

                        {{-- === INFO PEMBAYARAN (berubah sesuai metode) === --}}
                        {{-- Untuk Transfer/E-Wallet: auto-paid, untuk COD: bayar ditempat --}}
                        <div id="paymentInfo" class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700 flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="font-semibold" id="paymentInfoTitle">Pembayaran akan diverifikasi otomatis</p>
                                <p class="text-xs mt-0.5 text-blue-600" id="paymentInfoDesc">Untuk Transfer Bank / E-Wallet,status pembayaran akan langsung terkonfirmasi setelah pesanan dibuat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === SIDEBAR: RINGKASAN BELANJA === --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4 pb-4 border-b border-gray-100">Ringkasan Belanja</h3>
                    <div class="space-y-3 text-sm mb-5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkir</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3">
                            <div class="flex justify-between items-baseline">
                                <span class="font-bold text-gray-900">Total</span>
                                <span class="font-bold text-xl text-pink-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 border-t border-gray-100 pt-4">
                        <span class="text-xs text-gray-400">Aman & Terpercaya</span>
                        <span class="text-xs text-gray-400">Pembayaran Mudah</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- === JAVASCRIPT: Multi-step Checkout === --}}
{{-- Mengatur navigasi antar step, validasi, dan update tampilan --}}
@push('scripts')
<script>
let currentStep = 1;

// Fungsi: Pindah ke step tertentu
function goToStep(step) {
    // Validasi: Step 2 & 3 butuh alamat sudah diisi
    if (step === 2 || step === 3) {
        const addr = document.getElementById('shipping_address').value.trim();
        if (!addr) {
            alert('Silakan isi alamat pengiriman terlebih dahulu');
            document.getElementById('shipping_address').focus();
            return;
        }
    }

    currentStep = step;

    // Sembunyikan semua panel, lalu tampilkan panel yang dituju
    document.querySelectorAll('.step-panel').forEach(el => el.classList.add('hidden'));
    document.getElementById('step' + step).classList.remove('hidden');

    // Update lingkaran indikator step
    document.querySelectorAll('.step-indicator').forEach(el => {
        const s = parseInt(el.dataset.step);
        if (s < step) {
            // Step yang sudah dilewati: hijau dengan centang
            el.className = 'step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-green-500 text-white';
            el.textContent = '\u2713';
        } else if (s === step) {
            // Step aktif: pink
            el.className = 'step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-pink-500 text-white';
            el.textContent = s;
        } else {
            // Step selanjutnya: abu-abu
            el.className = 'step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-gray-200 text-gray-500';
            el.textContent = s;
        }
    });

    // Update label step
    document.querySelectorAll('.step-label').forEach(el => {
        const s = parseInt(el.dataset.step);
        if (s <= step) {
            el.className = 'ml-3 text-sm font-semibold text-gray-900 step-label';
        } else {
            el.className = 'ml-3 text-sm font-semibold text-gray-400 step-label';
        }
    });

    // Update garis penghubung antar step
    document.querySelectorAll('.step-line').forEach(el => {
        const s = parseInt(el.dataset.step);
        if (s < step) {
            el.className = 'flex-1 mx-4 h-0.5 bg-green-500 step-line';
        } else {
            el.className = 'flex-1 mx-4 h-0.5 bg-gray-200 step-line';
        }
    });

    // Jika masuk step 3 (Review), isi info alamat & pembayaran
    if (step === 3) {
        document.getElementById('reviewAddress').textContent = document.getElementById('shipping_address').value;
        const paymentSelected = document.querySelector('input[name="payment_method"]:checked');
        if (paymentSelected) {
            const labels = {
                'transfer': 'Transfer Bank (BCA / Mandiri / BNI)',
                'e-wallet': 'E-Wallet (GoPay / OVO / Dana)',
                'cod': 'COD (Bayar di Tempat)'
            };
            document.getElementById('reviewPayment').textContent = labels[paymentSelected.value] || paymentSelected.value;

            // Update info pembayaran berdasarkan metode yang dipilih
            const payInfoTitle = document.getElementById('paymentInfoTitle');
            const payInfoDesc = document.getElementById('paymentInfoDesc');
            if (paymentSelected.value === 'cod') {
                payInfoTitle.textContent = 'Bayar saat pesanan diterima';
                payInfoDesc.textContent = 'Pembayaran dilakukan secara tunai saat kurir sampai di lokasi Anda.';
                document.getElementById('paymentInfo').className = 'mt-4 bg-orange-50 border border-orange-100 rounded-xl p-4 text-sm text-orange-700 flex items-start gap-3';
            } else {
                // Transfer & E-Wallet: auto-paid (simulasi pembayaran otomatis)
                payInfoTitle.textContent = 'Pembayaran terverifikasi otomatis';
                payInfoDesc.textContent = 'Pembayaran ' + labels[paymentSelected.value] + ' akan langsung terkonfirmasi. Status pesanan langsung diproses.';
                document.getElementById('paymentInfo').className = 'mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700 flex items-start gap-3';
            }
        }
    }

    // Scroll ke atas halaman
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Fungsi: Pilih metode pembayaran (tandai card yang dipilih)
function selectPayment(radio) {
    document.querySelectorAll('.payment-option').forEach(el => {
        el.classList.remove('border-pink-500', 'bg-pink-50');
        el.classList.add('border-gray-200');
    });
    radio.closest('.payment-option').classList.remove('border-gray-200');
    radio.closest('.payment-option').classList.add('border-pink-500', 'bg-pink-50');
}

// Auto-select metode pembayaran pertama saat halaman dimuat
selectPayment(document.querySelector('input[name="payment_method"]:checked') || document.querySelector('input[name="payment_method"]'));
</script>
@endpush
@endsection
