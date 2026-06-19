<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    /**
     * MENAMPILKAN HALAMAN BERANDA TOKO
     * Fungsi ini mengambil data produk dari database dan menampilkannya di halaman utama.
     * - $products: Semua produk aktif, bisa difilter berdasarkan search & kategori
     * - $featured: 4 produk unggulan (is_featured = true)
     * - $categories: Semua kategori aktif untuk dropdown filter
     */
    public function index(Request $request)
    {
        // Ambil semua produk aktif beserta relasi kategorinya
        $query = Product::with('category')->where('is_active', true);

        // Filter berdasarkan kata kunci pencarian (name)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori yang dipilih
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Paginate 12 produk per halaman, urutkan dari terbaru
        $products = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $featured = Product::where('is_active', true)->where('is_featured', true)->take(4)->get();

        return view('store.index', compact('products', 'categories', 'featured'));
    }

    /**
     * MENAMPILKAN DETAIL PRODUK
     * Route Model Binding: Laravel otomatis mencari Product berdasarkan ID dari URL
     * Juga menampilkan 4 produk terkait (kategori sama, bukan produk yang sama)
     */
    public function show(Product $product)
    {
        // Load relasi kategori
        $product->load('category');
        // Cari 4 produk lain dengan kategori yang sama (produk terkait)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('store.show', compact('product', 'related'));
    }

    /**
     * MENAMBAHKAN PRODUK KE KERANJANG (SESSION)
     * Produk disimpan di session dengan key format: {product_id}|{size}|{color}
     * Ini memungkinkan produk yang sama dengan varian berbeda dianggap item berbeda
     * Contoh: kaos hitam size M dan kaos hitam size L adalah 2 item keranjang berbeda
     */
    public function addToCart(Request $request, Product $product)
    {
        // Validasi input: quantity wajib, size & color opsional
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
        ]);

        $size = $request->input('size', '');
        $color = $request->input('color', '');
        // Buat key unik untuk setiap varian produk
        $cartKey = $product->id . '|' . $size . '|' . $color;

        // Ambil data keranjang dari session (array kosong jika belum ada)
        $cart = session('cart', []);

        // Jika produk dengan varian yang sama sudah ada, tambah quantity-nya
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += (int) $request->input('quantity', 1);
        } else {
            // Jika belum ada, buat item keranjang baru
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->effective_price, // Pakai sale_price jika sedang diskon
                'image' => $product->image,
                'quantity' => (int) $request->input('quantity', 1),
                'size' => $size,
                'color' => $color,
            ];
        }

        // Simpan kembali keranjang ke session
        session(['cart' => $cart]);

        return redirect()->route('store.cart')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    /**
     * MENAMPILKAN HALAMAN KERANJANG
     * Mengambil data cart dari session dan menghitung subtotal semua item
     */
    public function cart()
    {
        $cart = session('cart', []);
        // Hitung subtotal: jumlah dari (harga * quantity) setiap item
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('store.cart', compact('cart', 'subtotal'));
    }

    /**
     * MEMPERBARUI QUANTITY ITEM DI KERANJANG
     * Menerima array quantities dengan format: {cart_key => quantity_baru}
     * Ada penanganan khusus jika data dikirim sebagai JSON string (dari cache browser lama)
     */
    public function updateCart(Request $request)
    {
        $cart = session('cart', []);

        $quantities = $request->input('quantities', []);
        // Cegah error: jika quantities terkirim sebagai string JSON (dari cache browser)
        // ubah ke array dulu sebelum di-loop
        if (is_string($quantities)) {
            $quantities = json_decode($quantities, true) ?? [];
        }

        // Loop setiap item, update quantity-nya (minimal 1)
        foreach ($quantities as $key => $qty) {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = max(1, (int)$qty);
            }
        }

        session(['cart' => $cart]);

        // Setelah update, redirect ke halaman checkout
        return redirect()->route('store.checkout');
    }

    /**
     * MENGHAPUS ITEM DARI KERANJANG
     * $id = cart key (format: {product_id}|{size}|{color})
     * Cukup unset array dan simpan ulang ke session
     */
    public function removeFromCart($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]); // Hapus item berdasarkan key-nya
        session(['cart' => $cart]);

        return redirect()->route('store.cart')->with('success', 'Produk dihapus dari keranjang!');
    }

    /**
     * MENAMPILKAN HALAMAN CHECKOUT
     * Cek apakah keranjang kosong, kalau kosong redirect kembali
     * Hitung subtotal, ongkir (fixed 15.000), dan total
     */
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'Keranjang kosong!');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = 15000; // Ongkos kirim tetap Rp 15.000
        $total = $subtotal + $shipping;

        return view('store.checkout', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    /**
     * MEMPROSES PESANAN (CREATE ORDER)
     * Ini adalah fungsi paling penting - membuat pesanan baru di database.
     * Untuk Transfer Bank & E-Wallet: status langsung 'processing' + 'paid'
     * Untuk COD: status 'pending' + 'unpaid' (bayar pas diterima)
     * Juga generate nomor referensi Virtual Account atau Transaction ID palsu
     */
    public function placeOrder(Request $request)
    {
        // Validasi input dari form checkout
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:transfer,cod,e-wallet',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'Keranjang kosong!');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = 15000;
        $total = $subtotal + $shipping;

        $paymentMethod = $validated['payment_method'];
        // Transfer & E-Wallet dianggap sebagai pembayaran online
        $isOnlinePayment = in_array($paymentMethod, ['transfer', 'e-wallet']);

        $paymentReference = null;
        if ($isOnlinePayment) {
            // Generate nomor referensi palsu (simulasi pembayaran)
            if ($paymentMethod === 'transfer') {
                $paymentReference = 'VA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
            } else {
                $paymentReference = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
            }
        }

        // Simpan order ke database
        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'discount' => 0,
            'total' => $total,
            'status' => $isOnlinePayment ? 'processing' : 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => $isOnlinePayment ? 'paid' : 'unpaid',
            'payment_reference' => $paymentReference,
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Simpan setiap item dari keranjang ke tabel order_items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
            ]);
        }

        // Hapus keranjang dari session karena sudah jadi order
        session()->forget('cart');

        return redirect()->route('store.orders.show', $order)->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * MENAMPILKAN DAFTAR PESANAN USER
     * Hanya menampilkan pesanan milik user yang sedang login
     * Diurutkan dari yang terbaru, 10 per halaman
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items') // Load relasi items biar tidak N+1 query
            ->latest()
            ->paginate(10);

        return view('store.orders', compact('orders'));
    }

    /**
     * MENAMPILKAN DETAIL PESANAN
     * Route Model Binding: $order langsung dari URL
     * Proteksi: user hanya bisa lihat pesanan miliknya sendiri (403 jika bukan miliknya)
     */
    public function showOrder(Order $order)
    {
        // Cek apakah order ini milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        $order->load('items');

        return view('store.order-show', compact('order'));
    }

    /**
     * MENAMPILKAN HALAMAN PROFIL USER
     */
    public function profile()
    {
        return view('store.profile', ['user' => Auth::user()]);
    }

    /**
     * MEMPERBARUI DATA PROFIL
     * Validasi: name wajib, phone & address opsional
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('store.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * MENGUBAH PASSWORD USER
     * Validasi: password lama harus cocok, password baru minimal 6 karakter
     * Password baru di-hash menggunakan Bcrypt sebelum disimpan
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Cek apakah password lama sesuai
        if (!Hash::check($validated['current_password'], Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Hash password baru sebelum disimpan
        Auth::user()->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('store.profile')->with('success', 'Password berhasil diubah!');
    }
}
