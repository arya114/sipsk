<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_item',
        'jumlah',
        'harga_satuan',
        'total_biaya',
        'no_surat',
        'tanggal_pengajuan',
        'total_anggaran',
        'nama_direktur',
        'status',       // Tambahkan status
        'keterangan',   // Tambahkan keterangan
        'pdf_path', // Tambahkan ini
    ];
}
