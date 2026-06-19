<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration - Membuat tabel item pesanan
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Relasi ke pesanan (induk)
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Relasi ke produk
            $table->string('product_name'); // Nama produk (disimpan untuk histori, produk dihapus)
            $table->decimal('price', 12, 2); // Harga per unit saat checkout
            $table->integer('quantity'); // Jumlah dibeli
            $table->decimal('subtotal', 12, 2); // Subtotal (price * quantity)
            $table->string('size')->nullable(); // Ukuran yang dipilih (opsional)
            $table->string('color')->nullable(); // Warna yang dipilih (opsional)
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Balikkan migration - Hapus tabel order_items
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
