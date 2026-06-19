<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration - Membuat tabel produk
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Relasi ke kategori
            $table->string('name'); // Nama produk
            $table->string('slug')->unique(); // Slug untuk URL (unik)
            $table->text('description')->nullable(); // Deskripsi produk (opsional)
            $table->string('sku')->unique(); // Kode SKU (unik)
            $table->decimal('price', 12, 2); // Harga normal
            $table->decimal('sale_price', 12, 2)->nullable(); // Harga diskon (opsional)
            $table->integer('stock')->default(0); // Jumlah stok
            $table->string('size')->nullable(); // Ukuran (contoh: S,M,L,XL) (opsional)
            $table->string('color')->nullable(); // Warna (contoh: Merah,Biru,Hijau) (opsional)
            $table->string('material')->nullable(); // Bahan (contoh: Katun,Polyester) (opsional)
            $table->string('image')->nullable(); // URL gambar produk (opsional)
            $table->boolean('is_active')->default(true); // Status aktif (bisa dijual/tidak)
            $table->boolean('is_featured')->default(false); // Produk unggulan
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Balikkan migration - Hapus tabel produk
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
