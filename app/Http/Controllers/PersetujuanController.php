<?php

namespace App\Http\Controllers;

use App\Models\SPK;
use App\Notifications\SPKCreatedNotification;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use App\Models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Ilovepdf\Ilovepdf;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Image; // Pastikan menggunakan Image facade dari Intervention
use Endroid\QrCode\QrCode;

class PersetujuanController extends Controller
{
    // Fungsi untuk menampilkan daftar pengajuan
    public function index()
    {
        $spks = SPK::all(); // Ambil semua data pengajuan dari database
        return view('list-pengajuan', compact('spks'));
    }

    // Fungsi untuk menampilkan form
    public function create()
    {
        return view('form-pengajuan');
    }

    // Fungsi untuk menangani pengiriman form dan membuat dokumen dari template
    public function store(Request $request)
    {
        try {

            // Validasi input untuk memastikan tanda tangan diunggah
            $request->validate([
                'ttd' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi tipe dan ukuran file
            ]);

            // Ambil data SPK terbaru untuk nomor surat
            $lastSPK = SPK::latest()->first();
            $nomorSurat = $lastSPK ? 'SPK-' . str_pad($lastSPK->id + 1, 3, '0', STR_PAD_LEFT) : 'SPK-001';
            $tanggalSPK = date('Y-m-d');
            $superadmin = User::where('role', 'superadmin')->first();

            // Simpan gambar tanda tangan yang diunggah
            $imagePath = null;
            if ($request->hasFile('ttd')) {
                $image = $request->file('ttd');
                $imageName = 'ttd_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'ttd_images/' . $imageName;

                $image->move(storage_path('app/public/ttd_images'), $imageName);
            }

            // Jika ada gambar tanda tangan yang di-upload, buat QR Code
            $qrCodePath = null;
            if ($imagePath) {
                $qrCodePath = $this->generateQrCode($imagePath);
            }

            // Proses pembuatan dokumen SPK (Word)
            $templatePath = storage_path('app/public/SPK.docx');
            $templateProcessor = new TemplateProcessor($templatePath);

            // Mengganti nilai placeholder di template
            $templateProcessor->setValue('nomor_surat', $nomorSurat);
            $templateProcessor->setValue('tanggal_spk', $tanggalSPK);
            $templateProcessor->setValue('nama_pihak_pertama', $superadmin->name);
            $templateProcessor->setValue('jabatan_pihak_pertama', $superadmin->jabatan);
            $templateProcessor->setValue('nik_pihak_pertama', $superadmin->nik);
            $templateProcessor->setValue('alamat_pihak_pertama', $superadmin->address);
            $templateProcessor->setValue('nama_pihak_kedua', $request->input('nama_pihak_kedua'));
            $templateProcessor->setValue('nomor_telepon_pihak_kedua', $request->input('nomor_telepon_pihak_kedua'));
            $templateProcessor->setValue('email_pihak_kedua', $request->input('email_pihak_kedua'));
            $templateProcessor->setValue('jabatan_pihak_kedua', $request->input('jabatan_pihak_kedua'));
            $templateProcessor->setValue('nik_pihak_kedua', $request->input('nik_pihak_kedua'));
            $templateProcessor->setValue('alamat_pihak_kedua', $request->input('alamat_pihak_kedua'));
            $templateProcessor->setValue('tanggal_mulai', $request->input('tanggal_mulai'));
            $templateProcessor->setValue('tanggal_selesai', $request->input('tanggal_selesai'));
            $templateProcessor->setValue('gaji', $request->input('gaji'));
            $templateProcessor->setValue('bank', $request->input('bank'));
            $templateProcessor->setValue('nomor_rekening', $request->input('nomor_rekening'));

            if ($qrCodePath) {
                $templateProcessor->setImageValue('qr_code', $qrCodePath);
            }

            // Simpan dokumen Word
            $wordFileName = 'SPK_' . time() . '.docx';
            $templateProcessor->saveAs($wordFileName);

            // Convert ke PDF menggunakan iLovePDF
            $pdfFilePath = $this->convertToPdfUsingILovePDF($wordFileName);

            // Periksa apakah pdfFilePath berhasil dibuat
            if (!$pdfFilePath) {
                return redirect()->back()->with('error', 'Gagal mengonversi Word ke PDF.');
            }

            // Simpan data SPK ke database (Word dan PDF path)
            $spk = SPK::create([
                'nomor_surat' => $nomorSurat,
                'tanggal_spk' => $tanggalSPK,
                'nama_pihak_kedua' => $request->input('nama_pihak_kedua'),
                'jabatan_pihak_kedua' => $request->input('jabatan_pihak_kedua'),
                'email_pihak_kedua' => $request->input('email_pihak_kedua'),
                'nomor_telepon_pihak_kedua' => $request->input('nomor_telepon_pihak_kedua'),
                'nik_pihak_kedua' => $request->input('nik_pihak_kedua'),
                'alamat_pihak_kedua' => $request->input('alamat_pihak_kedua'),
                'tanggal_mulai' => $request->input('tanggal_mulai'),
                'tanggal_selesai' => $request->input('tanggal_selesai'),
                'gaji' => $request->input('gaji'),
                'bank' => $request->input('bank'),
                'nomor_rekening' => $request->input('nomor_rekening'),
                'pdf_path' => $pdfFilePath,
                'word_path' => $wordFileName, // Menyimpan path file Word ke database
                'status' => $request->input('status', 'Menunggu Persetujuan Direktur'),
                'keterangan' => $request->input('keterangan', 'Menunggu persetujuan dari direktur untuk memulai proses lebih lanjut.'),
                'ttd_path' => $imagePath,
                'qr_code_path' => $qrCodePath
            ]);

            // Kirim notifikasi ke superadmin
            $details = [
                'id' => $spk->id,
                'nomor_surat' => $spk->nomor_surat,
                'tanggal_spk' => $spk->tanggal_spk,
                'nama_pihak_kedua' => $spk->nama_pihak_kedua,
            ];
            $superadmin->notify(new SPKCreatedNotification($details));

            return redirect()->route('list.pengajuan')->with('success', 'Pengajuan SPK berhasil dikirim!');
        } catch (\Exception $e) {
            // Mengatur flash message kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim pengajuan. Silakan coba lagi.');
        }
    }


    // Fungsi untuk mengonversi Word ke PDF menggunakan iLovePDF API
    private function convertToPdfUsingILovePDF($wordFileName)
    {
        try {
            $ilovepdf = new Ilovepdf('project_public_d1c2d3d3f606d8a9e3f3a4d73fc21bd0_yuT4Jc4b8022d84129b013a8f614d6b90b243', 'secret_key_a7b48329a69cae3db7a9f6a0f71ffeb5_fiKORa7e1f0ed7201b75b3c12326de50490fe');
            $task = $ilovepdf->newTask('officepdf');
            $task->addFile($wordFileName);
            $task->execute();

            // Pastikan direktori penyimpanan ada dan dapat diakses
            $downloadPath = storage_path('app/public');
            $task->download($downloadPath);

            // Cek jika file PDF benar-benar dibuat
            $pdfFileName = basename($wordFileName, '.docx') . '.pdf';
            $pdfFullPath = $downloadPath . '/' . $pdfFileName;

            if (file_exists($pdfFullPath)) {
                return $pdfFullPath;
            } else {
                throw new \Exception('File PDF tidak ditemukan setelah konversi.');
            }
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi masalah selama konversi
            return null;
        }
    }

    // Fungsi untuk membuat QR Code dari tanda tangan pengguna
    private function generateQrCode($signaturePath)
    {
        if (empty($signaturePath)) {
            error_log('Jalur tanda tangan kosong, tidak dapat membuat QR code.');
            return null;
        }

        $qrCode = new QrCode(url('storage/' . $signaturePath));
        $filePath = storage_path('app/public/signatures/' . 'qrcode_' . time() . '.png');
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $result->saveToFile($filePath);

        // Log jalur QR code untuk verifikasi
        error_log('QR code saved at: ' . $filePath);

        return $filePath;
    }
}
