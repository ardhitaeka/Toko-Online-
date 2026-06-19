{{-- ============================================================ --}}
{{-- LAYOUT UTAMA TOKO (Store Layout) --}}
{{-- Template dasar yang digunakan oleh semua halaman toko --}}
{{-- Berisi: Navbar, Alert Messages, Main Content, Footer --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Judul halaman dinamis (diisi dari @section('title') di child view) --}}
    <title>@yield('title', 'Toko') - FashionStore</title>
    {{-- Vite: memuat CSS dan JS yang sudah di-compile --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- ============================================================ --}}
    {{-- NAVBAR (Sticky di atas) --}}
    {{-- ============================================================ --}}
    <nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- === LOGO === --}}
                <a href="{{ route('store.index') }}" class="text-xl font-bold text-gray-800 flex items-center gap-2 group">
                    <span class="bg-gradient-to-br from-pink-500 to-purple-600 text-white w-9 h-9 rounded-xl flex items-center justify-center text-lg group-hover:shadow-lg group-hover:shadow-pink-200 transition-all duration-300">&#9827;</span>
                    <span class="hidden sm:inline">FashionStore</span>
                </a>

                {{-- === NAVIGASI DESKTOP === --}}
                <div class="hidden md:flex items-center gap-1">
                    {{-- Beranda --}}
                    <a href="{{ route('store.index') }}" class="relative px-4 py-2 text-sm font-medium {{ request()->routeIs('store.index') ? 'text-pink-600' : 'text-gray-600 hover:text-pink-500' }} transition-colors duration-200">
                        Beranda
                        @if(request()->routeIs('store.index'))
                            {{-- Indikator halaman aktif (garis bawah) --}}
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full"></span>
                        @endif
                    </a>
                    {{-- Pesanan Saya --}}
                    <a href="{{ route('store.orders') }}" class="relative px-4 py-2 text-sm font-medium {{ request()->routeIs('store.orders*') ? 'text-pink-600' : 'text-gray-600 hover:text-pink-500' }} transition-colors duration-200">
                        Pesanan
                        @if(request()->routeIs('store.orders*'))
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full"></span>
                        @endif
                    </a>

                    {{-- Icon Keranjang dengan badge jumlah item --}}
                    <a href="{{ route('store.cart') }}" class="relative p-2.5 rounded-xl {{ request()->routeIs('store.cart') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-500 hover:bg-pink-50' }} transition-all duration-200">
                        @php $cartQty = collect(session('cart', []))->sum('quantity'); @endphp
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        @if($cartQty > 0)
                            {{-- Badge merah dengan jumlah item --}}
                            <span class="absolute -top-1 -right-1 bg-gradient-to-br from-pink-500 to-red-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-md shadow-pink-200 animate-pulse">{{ $cartQty }}</span>
                        @endif
                    </a>

                    {{-- Pemisah vertikal --}}
                    <div class="w-px h-6 bg-gray-200 mx-2"></div>

                    {{-- Profile User --}}
                    <a href="{{ route('store.profile') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl {{ request()->routeIs('store.profile') ? 'text-pink-600 bg-pink-50' : 'text-gray-600 hover:text-pink-500 hover:bg-pink-50' }} transition-all duration-200">
                        {{-- Avatar lingkaran dengan inisial user --}}
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="max-w-[100px] truncate text-sm">{{ auth()->user()->name }}</span>
                    </a>

                    {{-- Tombol Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span class="hidden lg:inline">Keluar</span>
                        </button>
                    </form>
                </div>

                {{-- === NAVIGASI MOBILE === --}}
                <div class="flex md:hidden items-center gap-2">
                    {{-- Icon keranjang mobile --}}
                    <a href="{{ route('store.cart') }}" class="relative p-2.5">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        @php $cartQtyMobile = collect(session('cart', []))->sum('quantity'); @endphp
                        @if($cartQtyMobile > 0)
                            <span class="absolute -top-1 -right-1 bg-gradient-to-br from-pink-500 to-red-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-md shadow-pink-200">{{ $cartQtyMobile }}</span>
                        @endif
                    </a>
                    {{-- Tombol hamburger menu --}}
                    <button id="mobileMenuBtn" class="p-2.5 rounded-xl text-gray-700 hover:text-pink-500 hover:bg-pink-50 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- === MOBILE MENU (Dropdown) === --}}
            <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 overflow-hidden transition-all duration-300">
                <div class="py-3 space-y-1">
                    <a href="{{ route('store.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('store.index') ? 'text-pink-600 bg-pink-50' : 'text-gray-700 hover:text-pink-500 hover:bg-pink-50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Beranda
                    </a>
                    <a href="{{ route('store.orders') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('store.orders*') ? 'text-pink-600 bg-pink-50' : 'text-gray-700 hover:text-pink-500 hover:bg-pink-50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Pesanan Saya
                    </a>
                    <a href="{{ route('store.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('store.profile') ? 'text-pink-600 bg-pink-50' : 'text-gray-700 hover:text-pink-500 hover:bg-pink-50' }} transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ auth()->user()->name }}
                    </a>
                    <hr class="my-2 border-gray-100">
                    {{-- Logout di mobile menu --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- === ALERT SUKSES (flash message) === --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- === ALERT ERROR (flash message) === --}}
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- === MAIN CONTENT (diisi oleh child view via @yield) === --}}
    <main>
        @yield('content')
    </main>

    {{-- ============================================================ --}}
    {{-- FOOTER --}}
    {{-- ============================================================ --}}
    <footer class="bg-gray-900 text-gray-400 mt-16">
        {{-- Garis dekorasi gradient di atas footer --}}
        <div class="h-2 bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500"></div>

        <div class="max-w-7xl mx-auto px-4 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">
                {{-- === BRAND & SOSIAL MEDIA === --}}
                <div class="lg:col-span-1">
                    <div class="text-xl font-bold text-white flex items-center gap-2 mb-4">
                        <span class="bg-gradient-to-br from-pink-500 to-purple-600 text-white w-9 h-9 rounded-xl flex items-center justify-center text-lg">&#9827;</span>
                        <span>FashionStore</span>
                    </div>
                    {{-- Deskripsi toko --}}
                    <p class="text-sm text-gray-500 leading-relaxed mb-5">Toko fashion online terpercaya dengan koleksi terbaru dan stylish untuk setiap kesempatan.</p>
                    {{-- Icon Sosial Media --}}
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-pink-500 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-pink-500 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.11 2.525c.636-.247 1.363-.416 2.427-.465C8.83 2.012 9.185 2 11.615 2h.7zm-.08 1.802h-.468c-2.456 0-2.784.011-3.776.058-.994.046-1.653.203-2.26.433a3.065 3.065 0 00-1.107.72 3.065 3.065 0 00-.72 1.108c-.23.607-.387 1.266-.433 2.26-.047.992-.058 1.32-.058 3.776v.468c0 2.456.011 2.784.058 3.776.046.994.203 1.653.433 2.26.184.48.41.84.72 1.107.267.31.627.536 1.108.72.607.23 1.266.387 2.26.433.992.047 1.32.058 3.776.058h.468c2.455 0 2.784-.011 3.776-.058.994-.046 1.653-.203 2.26-.433a3.065 3.065 0 001.107-.72 3.065 3.065 0 00.72-1.108c.23-.607.387-1.266.433-2.26.047-.992.058-1.32.058-3.776v-.468c0-2.455-.011-2.784-.058-3.776-.046-.994-.203-1.653-.433-2.26a3.065 3.065 0 00-.72-1.107 3.065 3.065 0 00-1.108-.72c-.607-.23-1.266-.387-2.26-.433-.992-.047-1.32-.058-3.776-.058zm-.08 4.794a4.5 4.5 0 110 9 4.5 4.5 0 010-9zm0 1.8a2.7 2.7 0 100 5.4 2.7 2.7 0 000-5.4zm5.256-.216a1.08 1.08 0 110 2.16 1.08 1.08 0 010-2.16z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-pink-500 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                    </div>
                </div>

                {{-- === MENU === --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Menu</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('store.index') }}" class="text-gray-500 hover:text-pink-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gray-600"></span>Beranda</a></li>
                        <li><a href="{{ route('store.orders') }}" class="text-gray-500 hover:text-pink-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gray-600"></span>Pesanan</a></li>
                        <li><a href="{{ route('store.cart') }}" class="text-gray-500 hover:text-pink-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gray-600"></span>Keranjang</a></li>
                        <li><a href="{{ route('store.profile') }}" class="text-gray-500 hover:text-pink-400 transition-colors duration-200 flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-gray-600"></span>Profil</a></li>
                    </ul>
                </div>

                {{-- === KONTAK === --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3 text-gray-500">
                            <svg class="w-4 h-4 mt-0.5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>admin@fashionstore.id</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-500">
                            <svg class="w-4 h-4 mt-0.5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>+62 812 3456 7890</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-500">
                            <svg class="w-4 h-4 mt-0.5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>

                {{-- === JAM OPERASIONAL === --}}
                <div>
                    <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Jam Operasional</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li class="flex justify-between">
                            <span>Sen - Jum</span>
                            <span class="text-gray-400">08:00 - 20:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span class="text-gray-400">09:00 - 18:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu</span>
                            <span class="text-gray-400">09:00 - 15:00</span>
                        </li>
                    </ul>
                    <div class="mt-5 pt-5 border-t border-gray-800">
                        <p class="text-xs text-gray-600">&#9733; Pelayanan terbaik untuk Anda</p>
                    </div>
                </div>
            </div>

            {{-- === BOTTOM BAR (Copyright) === --}}
            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} FashionStore. All rights reserved.
                </p>
                <div class="flex items-center gap-4 text-xs text-gray-600">
                    <a href="#" class="hover:text-pink-400 transition-colors">Kebijakan Privasi</a>
                    <span class="w-1 h-1 rounded-full bg-gray-700"></span>
                    <a href="#" class="hover:text-pink-400 transition-colors">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- === JAVASCRIPT: Mobile Menu Toggle === --}}
    @push('scripts')
<script>
// Mobile Menu Toggle with Animation - membuka/menutup menu navigasi di HP
// Menggunakan maxHeight untuk animasi slide yang smooth
document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
    const menu = document.getElementById('mobileMenu');
    if (menu.classList.contains('hidden')) {
        // Buka menu: hilangkan hidden dulu, set maxHeight 0, lalu animate ke scrollHeight
        menu.classList.remove('hidden');
        menu.style.maxHeight = '0px';
        requestAnimationFrame(() => {
            menu.style.maxHeight = menu.scrollHeight + 'px';
        });
    } else {
        // Tutup menu: animate maxHeight ke 0, lalu sembunyikan setelah selesai
        menu.style.maxHeight = '0px';
        setTimeout(() => menu.classList.add('hidden'), 300);
    }
});
</script>
@endpush

    {{-- Memuat semua script yang di-push dari child view --}}
    @stack('scripts')
</body>

</html>
