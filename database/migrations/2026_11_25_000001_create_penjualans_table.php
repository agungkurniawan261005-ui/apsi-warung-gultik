<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('jumlah_porsi');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('total_harga');
            $table->enum('metode_pembayaran', ['Tunai', 'QRIS'])->index();
            $table->dateTime('waktu_transaksi')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
