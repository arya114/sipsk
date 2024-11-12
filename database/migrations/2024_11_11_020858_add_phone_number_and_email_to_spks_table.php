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
            $table->string('phone_number')->nullable(); // Menambahkan kolom phone_number
            $table->string('email')->nullable();        // Menambahkan kolom email
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
            $table->dropColumn('phone_number'); // Menghapus kolom phone_number
            $table->dropColumn('email');        // Menghapus kolom email
        });
    }
};
