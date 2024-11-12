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
        Schema::table('persetujuans', function (Blueprint $table) {
            $table->string('status')->default('Pending'); // Status default 'Pending'
            $table->text('keterangan')->nullable(); // Keterangan
        });
    }

    public function down()
    {
        Schema::table('persetujuans', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};
