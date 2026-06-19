<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom payment_reference ke tabel orders
     * Digunakan untuk menyimpan nomor referensi pembayaran:
     * - Transfer Bank -> Virtual Account (contoh: VA-1234567890)
     * - E-Wallet -> Transaction ID (contoh: TRX-GOPAY-XXXXXXXX)
     * - COD -> null (tidak perlu referensi)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom ini akan diisi setelah order dibuat untuk transfer/e-wallet
            $table->string('payment_reference')->nullable()->after('payment_status');
        });
    }

    /**
     * Balikkan migration - Hapus kolom payment_reference
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
        });
    }
};
