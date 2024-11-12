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
            $table->string('status')->default('pending'); // Menambahkan kolom status dengan default 'pending'
            $table->text('keterangan')->nullable(); // Menambahkan kolom keterangan yang bisa bernilai null
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
            $table->dropColumn('status'); // Menghapus kolom status
            $table->dropColumn('keterangan'); // Menghapus kolom keterangan
        });
    }
};
