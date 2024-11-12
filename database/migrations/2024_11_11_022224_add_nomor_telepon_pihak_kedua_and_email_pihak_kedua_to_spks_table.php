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
            $table->string('nomor_telepon_pihak_kedua')->nullable(); // Menambahkan kolom nomor_telepon_pihak_kedua
            $table->string('email_pihak_kedua')->nullable();        // Menambahkan kolom email_pihak_kedua
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spks', function (Blueprint $table) {
            $table->dropColumn('nomor_telepon_pihak_kedua'); // Menghapus kolom phone_number
            $table->dropColumn('email_pihak_kedua');        // Menghapus kolom email
        });
    }
};
