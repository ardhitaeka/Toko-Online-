<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration - Membuat tabel pesanan (orders)
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('order_number')->unique(); // Nomor pesanan unik (contoh: INV-20260618-XXXX)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke user pembeli
            $table->decimal('subtotal', 12, 2); // Total harga barang sebelum ongkir & diskon
            $table->decimal('shipping_cost', 12, 2)->default(0); // Ongkos kirim
            $table->decimal('discount', 12, 2)->default(0); // Diskon (jika ada)
            $table->decimal('total', 12, 2); // Total akhir (subtotal + ongkir - diskon)
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending'); // Status pesanan
            $table->enum('payment_method', ['transfer', 'cod', 'e-wallet'])->default('transfer'); // Metode pembayaran
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid'); // Status pembayaran
            $table->text('shipping_address')->nullable(); // Alamat pengiriman
            $table->text('notes')->nullable(); // Catatan pesanan (opsional)
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Balikkan migration - Hapus tabel orders
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
