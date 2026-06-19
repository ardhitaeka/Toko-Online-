
# 🛍️ Toko Online - FashionStore

**Toko Online** adalah aplikasi web **e-commerce fashion** berbasis **Laravel 12** yang menghadirkan pengalaman belanja online lengkap dengan varian produk (ukuran/warna), sistem keranjang berbasis session, multi-metode pembayaran (Transfer Bank, E-Wallet, COD), serta panel admin yang powerful untuk manajemen produk, pesanan, pelanggan, dan laporan penjualan.

> **Tujuan Projek:** Tugas akhir mata kuliah Pemrograman Web Lanjut — Sistem e-commerce fashion dengan fitur checkout multi-langkah, auto-konfirmasi pembayaran online, dan laporan penjualan yang dapat diekspor ke Excel.

---

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Struktur Database](#struktur-database)
- [Flow Aplikasi](#flow-aplikasi)
  - [Flow User (Pembeli)](#flow-user-pembeli)
  - [Flow Admin](#flow-admin)
- [Struktur Route](#struktur-route)
- [Instalasi & Setup](#instalasi--setup)
- [Akun Demo](#akun-demo)
- [Screenshot Halaman](#screenshot-halaman)
- [Lisensi](#lisensi)

---

## ✨ Fitur Utama

### 👤 Fitur User (Pembeli)
| Fitur | Deskripsi |
|-------|-----------|
| **Registrasi & Login** | Daftar akun baru atau login sebagai pelanggan |
| **Katalog Produk** | Lihat semua produk, filter berdasarkan kategori & pencarian |
| **Produk Unggulan** | Menampilkan 4 produk featured di halaman depan |
| **Detail Produk** | Pilih **ukuran (Size)** & **warna (Color)** sebelum masuk keranjang |
| **Harga Diskon** | Menampilkan sale price jika produk sedang diskon |
| **Keranjang Belanja** | Simpan keranjang di **session**, update quantity, hapus item |
| **Checkout 3 Langkah** | Review pesanan → isi alamat → pilih pembayaran |
| **Pembayaran** | **Transfer Bank** (auto-VA), **E-Wallet** (auto-TRX ID), **COD** |
| **Riwayat Pesanan** | Lihat daftar & detail pesanan dengan **timeline status** |
| **Profil & Password** | Edit profil dan ganti password |

### 👑 Fitur Admin
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik ringkas: total produk, pesanan, pendapatan, pelanggan |
| **Manajemen Kategori** | CRUD kategori, upload gambar, aktif/nonaktifkan |
| **Manajemen Produk** | CRUD produk, upload gambar, atur harga/stok/varian/diskon |
| **Manajemen Pelanggan** | Lihat & kelola data pelanggan dari panel admin |
| **Manajemen Pesanan** | Lihat semua pesanan, filter status, **update status & pembayaran** |
| **Buat Pesanan Manual** | Admin dapat membuat pesanan untuk pelanggan |
| **Laporan Penjualan** | Grafik & tabel, filter **Hari Ini / Bulan Ini / Tahun Ini** |
| **Ekspor Excel** | Download laporan penjualan ke format `.xlsx` |
| **Stok Menipis** | Notifikasi produk dengan stok ≤ 10 |

---

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Framework** | Laravel 12 |
| **PHP** | PHP 8.2+ |
| **Database** | MySQL / MariaDB |
| **Frontend** | Blade Templating + Tailwind CSS + JavaScript |
| **Build Tool** | Vite (Laravel Vite) |
| **UI Library** | FontAwesome 5 (icons) |
| **Excel Export** | PhpSpreadsheet |
| **Image** | Upload ke `storage/app/public/` atau URL eksternal |

---

## 🗄 Struktur Database

### Entity Relationship Diagram

```
┌─────────────┐     ┌──────────────┐     ┌─────────────┐
│   Users     │1──N│    Orders    │1──N│  OrderItems  │
├─────────────┤     ├──────────────┤     ├─────────────┤
│ id          │     │ id           │     │ id          │
│ name        │     │ order_number │     │ order_id    │
│ email       │     │ user_id (FK) │────▶│ product_id  │
│ password    │     │ subtotal     │     │ product_name│
│ role        │     │ shipping_cost│     │ price       │
│  (admin/    │     │ discount     │     │ quantity    │
│   customer) │     │ total        │     │ subtotal    │
│ phone       │     │ status       │     │ size        │
│ address     │     │  (pending/   │     │ color       │
└─────────────┘     │   processing/│     └─────────────┘
                    │   shipped/   │
┌─────────────┐     │   completed/ │     ┌─────────────┐
│  Categories │1──N│   cancelled)  │     │  Customers  │
├─────────────┤     │ payment_method│     ├─────────────┤
│ id          │     │  (transfer/  │     │ id          │
│ name        │     │   cod/       │     │ name        │
│ slug        │     │   e-wallet)  │     │ email       │
│ description │     │ payment_     │     │ phone       │
│ image       │     │  status      │     │ address     │
│ is_active   │     │  (unpaid/    │     │ city        │
└─────────────┘     │   paid/      │     │ province    │
                    │   refunded)  │     │ postal_code │
┌─────────────┐     │ payment_     │     │ is_active   │
│   Products  │N──1│  reference   │     └─────────────┘
├─────────────┤     │ shipping_    │
│ id          │     │  address     │
│ category_id │     │ notes        │
│ name        │     │ created_at   │
│ slug        │     └──────────────┘
│ sku         │
│ price       │
│ sale_price  │
│ stock       │
│ size        │
│ color       │
│ material    │
│ image       │
│ is_active   │
│ is_featured │
└─────────────┘
```

### Tabel & Deskripsi

| Tabel | Fungsi |
|-------|--------|
| **users** | Data user (admin & customer) dengan kolom `role`, `phone`, `address` |
| **customers** | Data pelanggan tambahan (city, province, postal_code) |
| **categories** | Kategori produk, mendukung upload gambar |
| **products** | Produk dengan harga normal & diskon, stok, varian (size/color/material) |
| **orders** | Pesanan dengan subtotal, ongkir, diskon, total, status, metode pembayaran |
| **order_items** | Item dalam pesanan (snapshot nama produk, harga, varian saat checkout) |

---

## 🔄 Flow Aplikasi

### Flow User (Pembeli)

```
┌──────────┐
│  LANDING │── (belum login) → Halaman Login/Register
│   PAGE   │── (sudah login)
└────┬─────┘
     │
     ▼
┌──────────────┐      ┌───────────────────┐
│   DAFTAR /   │ ──▶  │  REGISTRASI AKUN  │
│    LOGIN     │      │ (nama, email,     │
└──────┬───────┘      │  password, dll)   │
       │              └───────────────────┘
       ▼
┌──────────────────────────────────────┐
│         HALAMAN UTAMA TOKO            │
│  • Produk Unggulan (Featured)        │
│  • Semua Produk (12/halaman)         │
│  • Filter Kategori & Pencarian       │
└──────────────┬───────────────────────┘
               │
               ▼
┌──────────────────────────────────────┐
│         DETAIL PRODUK                │
│  • Nama, Harga, Diskon, Stok         │
│  • PILIH UKURAN (Size)               │
│  • PILIH WARNA (Color)               │
│  • Atur Jumlah (Quantity)            │
│  • Produk Terkait (Related)          │
│  ┌──────────────┐                    │
│  │ TAMBAH KE   │                    │
│  │ KERANJANG   │                    │
│  └──────┬───────┘                    │
└─────────┼────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────┐
│         KERANJANG BELANJA            │
│  • List item (gambar, nama, varian)  │
│  • Tombol + / - quantity             │
│  • Tombol Hapus item                 │
│  • Subtotal                          │
│  ┌──────────────┐                    │
│  │  CHECKOUT    │  atau terus        │
│  └──────┬───────┘  belanja           │
└─────────┼────────────────────────────┘
          │
          ▼
┌──────────────────────────────────────────┐
│          CHECKOUT (3 Langkah)            │
│                                          │
│  Langkah 1: Review Pesanan               │
│  • Daftar barang dengan varian & harga   │
│                                          │
│  Langkah 2: Alamat Pengiriman            │
│  • Isi alamat (pre-filled dari profil)   │
│  • Catatan (opsional)                    │
│                                          │
│  Langkah 3: Metode Pembayaran            │
│  • 🔵 Transfer Bank → VA otomatis       │
│  • 🟢 E-Wallet → TRX ID otomatis        │
│  • 🟡 COD (Bayar di Tempat)              │
│                                          │
│  Ringkasan: Subtotal + Ongkir (Rp15k)    │
│  ┌──────────────┐                        │
│  │  BUAT PESANAN│                        │
│  └──────┬───────┘                        │
└─────────┼────────────────────────────────┘
          │
          ├──────────────────────────────────────┐
          │                                      │
          ▼                                      ▼
┌────────────────────┐            ┌──────────────────────────┐
│ TRANSFER / E-WALLET│            │          COD             │
│ Auto-bayar ✅      │            │ Bayar saat diterima      │
│ Status: Processing │            │ Status: Pending           │
│ Payment: Paid      │            │ Payment: Unpaid           │
│ Ref: VA-XXX /      │            │                          │
│ TRX-XXX            │            │                          │
└─────────┬──────────┘            └──────────┬───────────────┘
          │                                  │
          └──────────┬───────────────────────┘
                     │
                     ▼
┌───────────────────────────────────────────┐
│              KONFIRMASI PESANAN            │
│  • Nomor Pesanan: ORD-XXXXXX             │
│  • Referensi Pembayaran (jika online)    │
│  • Status Pesanan (timeline)             │
│  • Rincian Barang & Alamat               │
│  • Tombol: Lihat Semua Pesanan            │
└───────────────────────────────────────────┘
                     │
                     ▼
┌───────────────────────────────────────────┐
│           DAFTAR PESANAN SAYA             │
│  • Semua pesanan (10/halaman)            │
│  • Card: gambar, nama, status badge      │
│  • Status Timeline warna-warni           │
│  • Klik → lihat detail                   │
└───────────────────────────────────────────┘
                     │
                     ▼
┌───────────────────────────────────────────┐
│               PROFIL SAYA                  │
│  • Edit Nama, No. HP, Alamat             │
│  • Ganti Password                         │
└───────────────────────────────────────────┘
```

**Alur Status Pesanan (User Side):**
```
COD:   Pending ─▶ Processing ─▶ Shipped ─▶ Completed
       (unpaid)    (unpaid)      (unpaid)    (paid)

Online: Processing ─▶ Shipped ─▶ Completed
        (paid)       (paid)       (paid)

Semua: ─▶ Cancelled (stok dikembalikan)
```

---

### Flow Admin

```
┌──────────┐
│  LOGIN   │── (email: admin@fashionstore.id, password: password)
└────┬─────┘
     │
     ▼
┌────────────────────────────────────────────┐
│              DASHBOARD ADMIN                │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────────┐ │
│  │Produk│ │Pesanan│ │Revenue│ │Pelanggan │ │
│  │  24  │ │  35  │ │Rp15jt│ │    150    │ │
│  └──────┘ └──────┘ └──────┘ └──────────┘ │
│                                            │
│  📊 Kategori Terpopuler (bar chart)       │
│  📦 Pesanan Terbaru (5 terakhir)          │
│  ⚠️ Stok Menipis (≤10)                    │
└────────────────────────────────────────────┘
         │
         ├──────────────────────────────────────────┐
         │                  │                       │
         ▼                  ▼                       ▼
┌─────────────────┐ ┌──────────────────┐ ┌─────────────────────┐
│  KATEGORI        │ │   PRODUK         │ │   PELANGGAN         │
│  CRUD            │ │   CRUD           │ │   CRUD              │
├─────────────────┤ ├──────────────────┤ ├─────────────────────┤
│ • List/search   │ │ • List/search/   │ │ • List/search       │
│ • Nama, deskripsi│ │   filter kategori│ │ • Nama, email, phone │
│ • Upload gambar  │ │ • Nama, SKU,     │ │ • Buat/edit/hapus   │
│ • Aktif/nonaktif │ │   harga, stok    │ │ • Lihat jumlah order│
│ • Lihat jumlah   │ │ • Sale price     │ │                     │
│   produk        │ │ • Ukuran, warna  │ └─────────────────────┘
└─────────────────┘ │   bahan          │
                    │ • Upload gambar   │
         ┌──────────│ • Aktif/Featured  │──────────┐
         ▼          └──────────────────┘          ▼
┌─────────────────────┐              ┌────────────────────────┐
│    PESANAN          │              │    LAPORAN             │
│    (CRUD + Status)  │              │    (View & Export)     │
├─────────────────────┤              ├────────────────────────┤
│ • List semua pesanan│              │ • Filter Periode:      │
│   dengan filter:    │              │   • Hari Ini           │
│   • Search (nomor/  │              │   • Bulan Ini          │
│     nama pelanggan) │              │   • Tahun Ini          │
│   • Filter status   │              │                        │
│   • Filter payment  │              │ 📊 Ringkasan:          │
├─────────────────────┤              │ • Total Revenue        │
│ [ID] Lihat detail:  │              │ • Total Orders         │
│ • Info pelanggan    │              │ • Rata-rata Order      │
│ • Items pesanan     │              │                        │
│ • History status    │              │ 🏆 Produk Terlaris     │
│ • Tombol aksi:      │              │ 📂 Kategori Terpopuler │
│   • Update Status   │              │ 📦 Pesanan Terbaru     │
│     - Pending       │              │ ⚠️ Stok Menipis        │
│     - Processing    │              │                        │
│     - Shipped       │              │ 📥 Export Excel (.xlsx)│
│     - Completed     │              └────────────────────────┘
│     - Cancelled     │
│   • Update Payment  │
│     - Unpaid        │
│     - Paid          │
│     - Refunded      │
│ • Buat Pesanan Baru │
│   (pilih pelanggan  │
│    & produk manual) │
└─────────────────────┘
```

**Alur Status Pesanan (Admin Side):**
```
PENDING ─▶ PROCESSING ─▶ SHIPPED ─▶ COMPLETED
   │          │            │
   ▼          ▼            ▼
 CANCELLED  CANCELLED    CANCELLED
 (stok       (stok        (stok
  kembali)    kembali)     kembali)
```

---

## 🧭 Struktur Route

### Route Definitions (web.php)

```
🔓 GUEST (Tanpa Login)
─────────────────────────────────────────────────
GET  /login                        → Auth\LoginController@showLoginForm
POST /login                        → Auth\LoginController@login
GET  /register                     → Auth\RegisterController@showRegistrationForm
POST /register                     → Auth\RegisterController@register

🔐 AUTH (Semua User Login)
─────────────────────────────────────────────────
POST /logout                       → Auth\LoginController@logout

GET  /                             → Redirect: /dashboard (admin) /store (customer)

👤 CUSTOMER STORE (Prefix: /store, Middleware: auth)
─────────────────────────────────────────────────
GET  /store                        → StoreController@index
GET  /store/product/{product}     → StoreController@show
POST /store/cart/add/{product}    → StoreController@addToCart
GET  /store/cart                   → StoreController@cart
POST /store/cart/update            → StoreController@updateCart
DEL  /store/cart/{id}             → StoreController@removeFromCart
GET  /store/checkout               → StoreController@checkout
POST /store/order                  → StoreController@placeOrder
GET  /store/orders                 → StoreController@myOrders
GET  /store/orders/{order}        → StoreController@showOrder
GET  /store/profile                → StoreController@profile
PUT  /store/profile                → StoreController@updateProfile
PUT  /store/profile/password       → StoreController@changePassword

👑 ADMIN PANEL (Middleware: auth + admin)
─────────────────────────────────────────────────
GET  /dashboard                    → DashboardController@index

      /categories                  → CategoryController (Resource)
      /products                    → ProductController (Resource)
      /customers                   → CustomerController (Resource)
      /orders                      → OrderController (Resource)
PATCH /orders/{order}/status       → OrderController@updateStatus
PATCH /orders/{order}/payment      → OrderController@updatePayment

GET  /reports                      → ReportController@index
GET  /reports/export-excel         → ReportController@exportExcel
```

---

## 📦 Instalasi & Setup

### Prasyarat
- PHP 8.2+
- Composer
- MySQL / MariaDB
- Node.js & NPM (untuk Vite)
- Laragon / XAMPP / WAMP (opsional)

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/username/toko-online.git
cd toko-online

# 2. Install dependensi PHP
composer install

# 3. Copy & setup environment
copy .env.example .env
# Edit .env: atur DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Generate application key
php artisan key:generate

# 5. Buat database & jalankan migrasi
php artisan migrate

# 6. Jalankan seeder (data demo)
php artisan db:seed

# 7. Buat storage symlink (untuk akses gambar)
php artisan storage:link

# 8. Install & build frontend assets
npm install
npm run build

# 9. Jalankan development server
php artisan serve

# 10. (Opsional) Jalankan Vite untuk hot reload
npm run dev
```

### Storage Link
```bash
# Penting! Buat symlink agar gambar produk/kategori bisa diakses
php artisan storage:link
```

---

## 👥 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@fashionstore.id` | `password` |
| **Customer** | `rina@email.com` | `password` |
| **Customer** | `ahmad@email.com` | `password` |
| **Customer** | `siti@email.com` | `password` |
| **Customer** | `budi@email.com` | `password` |
| **Customer** | `dewi@email.com` | `password` |
| **Customer** | `raka@email.com` | `password` |

---

## 📸 Screenshot Halaman

> *(Tambahkan screenshot di folder `public/screenshots/` dan referensikan di sini)*

| Halaman | Deskripsi |
|---------|-----------|
| `public/screenshots/dashboard.png` | Dashboard admin dengan statistik |
| `public/screenshots/store-index.png` | Halaman utama toko |
| `public/screenshots/product-detail.png` | Detail produk dengan pilihan varian |
| `public/screenshots/cart.png` | Keranjang belanja |
| `public/screenshots/checkout.png` | Halaman checkout |
| `public/screenshots/orders.png` | Daftar pesanan user |
| `public/screenshots/admin-orders.png` | Manajemen pesanan admin |
| `public/screenshots/reports.png` | Laporan penjualan |

---

## 📁 Struktur Direktori

```
toko-online/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ReportController.php
│   │   │   └── StoreController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Customer.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Product.php
│   │   └── User.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_06_10_000001_create_categories_table.php
│   │   ├── 2026_06_10_000002_create_products_table.php
│   │   ├── 2026_06_10_000003_create_customers_table.php
│   │   ├── 2026_06_10_000004_create_orders_table.php
│   │   ├── 2026_06_10_000005_create_order_items_table.php
│   │   ├── 2026_06_13_042730_add_image_to_categories_table.php
│   │   ├── 2026_06_13_100000_add_role_to_users_table.php
│   │   └── 2026_06_18_162102_add_payment_reference_to_orders_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── auth/           (login, register)
│   ├── categories/     (index, create, edit)
│   ├── customers/      (index, create, edit)
│   ├── layouts/        (app.blade.php, store.blade.php)
│   ├── orders/         (index, show, create)
│   ├── products/       (index, show, create, edit)
│   ├── reports/        (index)
│   ├── store/          (index, show, cart, checkout, orders, order-show, profile)
│   ├── dashboard.blade.php
│   └── welcome.blade.php
└── routes/
    └── web.php
```

---

## 🧠 Catatan Development

### Variant Support (Size/Color)
- Cart menggunakan key format: `{product_id}|{size}|{color}`
- Produk dengan varian berbeda dianggap item keranjang yang berbeda
- `StoreController@addToCart` menangani varian ini

### Auto-Confirm Pembayaran Online
- **Transfer Bank:** `VA-YYYYMMDD-XXXXXXXX` (Virtual Account palsu)
- **E-Wallet:** `TRX-YYYYMMDD-XXXXXXXX` (Transaction ID palsu)
- Status langsung `processing` + `paid` untuk simulasi
- Referensi disimpan di kolom `payment_reference`

### Session-Based Cart
- Keranjang disimpan di **session** (bukan database)
- Hilang saat logout — cocok untuk guest browsing
- `updateCart` menangani edge case JSON string dari cache browser lama

### View Composers & Components
- App layout (`layouts/app.blade.php`) untuk admin panel
- Store layout (`layouts/store.blade.php`) untuk toko dengan Tailwind CSS
- Status badge menggunakan accessor `status_color` dan `status_label` di model Order

---

## 📄 Lisensi

Projek ini dibuat untuk tujuan pendidikan. Dibangun dengan **[Laravel](https://laravel.com)** — framework PHP open-source dengan lisensi MIT.
