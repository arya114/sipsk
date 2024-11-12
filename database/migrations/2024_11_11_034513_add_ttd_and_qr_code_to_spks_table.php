<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('spks', function (Blueprint $table) {
            $table->string('ttd_path')->nullable(); // Kolom untuk path gambar tanda tangan
            $table->string('qr_code_path')->nullable(); // Kolom untuk path QR code
        });
    }

    /**
     * Membalikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spks', function (Blueprint $table) {
            $table->dropColumn('ttd_path'); // Menghapus kolom ttd_path
            $table->dropColumn('qr_code_path'); // Menghapus kolom qr_code_path
        });
    }
};
