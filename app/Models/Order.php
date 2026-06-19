<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang boleh diisi secara massal (mass assignment)
     * Ini mencegah user mengisi kolom yang tidak seharusnya
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'notes',
        'payment_reference', // Nomor VA atau TRX ID (ditambahkan via migrasi)
    ];

    /**
     * Casting tipe data untuk kolom tertentu
     * Memastikan harga dalam format decimal dengan 2 angka di belakang koma
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * EVENT: BOOTED (dijalankan saat model pertama kali digunakan)
     * creating: otomatis mengisi order_number sebelum data disimpan ke database
     * Format nomor: ORD-{random_string}
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->order_number = $order->order_number ?? 'ORD-' . strtoupper(uniqid());
        });
    }

    /**
     * RELASI: Order milik User
     * Setiap order terhubung ke satu user (pembeli)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI: Order milik Customer (alias dari user)
     * Sama seperti relasi user() tapi dengan nama berbeda untuk kejelasan konteks
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * RELASI: Order memiliki banyak OrderItem
     * Satu order bisa berisi beberapa item produk
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ACCESSOR: status_label
     * Mengubah kode status (pending, processing, dll) menjadi teks yang mudah dibaca
     * Contoh: 'pending' => 'Menunggu'
     * Cara panggil di Blade: $order->status_label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * ACCESSOR: status_color
     * Mengembalikan class CSS Tailwind untuk warna badge status
     * Setiap status punya warna berbeda agar mudah dikenali
     * Cara panggil di Blade: $order->status_color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-gray-100 text-gray-700',
            'processing' => 'bg-yellow-100 text-yellow-700',
            'shipped' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * ACCESSOR: payment_status_label
     * Mengubah kode payment_status menjadi teks yang mudah dibaca
     * Contoh: 'unpaid' => 'Belum Bayar', 'paid' => 'Sudah Bayar'
     * Cara panggil di Blade: $order->payment_status_label
     */
    public function getPaymentStatusLabelAttribute()
    {
        return match ($this->payment_status) {
            'unpaid' => 'Belum Bayar',
            'paid' => 'Sudah Bayar',
            'refunded' => 'Dikembalikan',
            default => $this->payment_status,
        };
    }

    /**
     * ACCESSOR: payment_method_label
     * Mengubah kode payment_method menjadi teks yang mudah dibaca
     * Contoh: 'transfer' => 'Transfer Bank', 'cod' => 'COD'
     * Cara panggil di Blade: $order->payment_method_label
     */
    public function getPaymentMethodLabelAttribute()
    {
        return match ($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'cod' => 'COD',
            'e-wallet' => 'E-Wallet',
            default => $this->payment_method,
        };
    }
}
