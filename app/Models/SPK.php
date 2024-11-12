<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SPK extends Model
{
     // Nama tabel di database
     protected $table = 'spks'; // Ganti 'spks' dengan nama tabel yang benar jika berbeda

     // Kolom-kolom yang dapat diisi secara massal
     protected $fillable = [
         'nomor_surat',
         'tanggal_spk',
         'nama_pihak_kedua',
         'jabatan_pihak_kedua',
         'nik_pihak_kedua',
         'alamat_pihak_kedua',
         'nomor_telepon_pihak_kedua',
         'email_pihak_kedua',
         'tanggal_mulai',
         'tanggal_selesai',
         'gaji',
         'bank',
         'nomor_rekening',
         'pdf_path',
         'word_path',     
         'status',
         'keterangan',
         'ttd_path',       
        'qr_code_path'      
     ];
}
