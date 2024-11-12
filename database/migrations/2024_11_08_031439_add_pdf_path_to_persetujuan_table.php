<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('persetujuans', function (Blueprint $table) {
            $table->string('pdf_path')->nullable()->after('keterangan'); // Kolom baru untuk path PDF
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persetujuans', function (Blueprint $table) {
            $table->dropColumn('pdf_path'); // Menghapus kolom saat rollback migrasi
        });
    }
};
