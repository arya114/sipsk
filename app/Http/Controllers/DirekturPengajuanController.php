<?php

namespace App\Http\Controllers;

use App\Models\SPK;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Ilovepdf\Ilovepdf;
use Illuminate\Support\Facades\Auth;

class DirekturPengajuanController extends Controller
{
    public function index()
    {
        $spks = SPK::all(); // Ambil semua data pengajuan dari database
        return view('list-pengajuan-direktur', compact('spks'));
    }

    public function action(Request $request, $id)
    {
        $spk = SPK::findOrFail($id);

        if ($request->input('action') === 'acc') {
            $spk->status = 'Pengajuan Disetujui Direktur';
            $spk->keterangan = 'Pengajuan disetujui oleh direktur, silahkan unduh dokumen.';
            $this->generateAndUpdateDocuments($spk);
            $spk->save();

            return redirect()->route('list.pengajuan.direktur')->with('status', 'Pengajuan berhasil disetujui.');
        }

        return redirect()->route('list.pengajuan.direktur')->with('error', 'Aksi tidak valid.');
    }


    public function confirmAction($id)
    {
        $spk = SPK::findOrFail($id);
        return view('confirm-action', compact('spk'));
    }

    public function approveDirect($id)
    {
        $spk = SPK::findOrFail($id);

        // Ubah status menjadi disetujui
        $spk->status = 'Pengajuan Disetujui Direktur';
        $spk->keterangan = 'Pengajuan disetujui oleh direktur, silahkan unduh dokumen.';

        // Generate dan update dokumen jika diperlukan
        $this->generateAndUpdateDocuments($spk);

        $spk->save();

        return redirect()->route('list.pengajuan.direktur')->with('status', 'Pengajuan berhasil disetujui.');
    }


    public function view($id)
    {
        $spk = SPK::findOrFail($id);

        // Periksa apakah path PDF ada
        if (!$spk->pdf_path || !file_exists(storage_path('app/public/' . $spk->pdf_path))) {
            return redirect()->back()->with('error', 'File PDF tidak ditemukan.');
        }

        $pdfPath = asset('storage/' . $spk->pdf_path);
        return view('view-pdf', compact('pdfPath'));
    }

    private function generateAndUpdateDocuments($spk)
    {
        // Ambil pengguna superadmin yang sedang login
        $superadmin = Auth::user();

        // Pastikan hanya superadmin yang bisa mengakses
        if ($superadmin->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Ambil path tanda tangan dari superadmin
        $signaturePath = $superadmin->signature_path ? storage_path('app/public/' . $superadmin->signature_path) : null;

        // Jika tanda tangan ditemukan, kita akan membuat QR code dengan URL gambar tersebut
        if ($signaturePath && file_exists($signaturePath)) {
            // Buat URL gambar tanda tangan yang dapat diakses
            $signatureUrl = url('storage/' . $superadmin->signature_path);

            // Generate QR code dengan URL gambar tanda tangan
            $qrCodePath = $this->generateQrCode($signatureUrl);
        } else {
            // Jika tanda tangan tidak ditemukan, gunakan QR code sebagai alternatif
            $qrCodePath = $this->generateQrCode('Pengajuan telah disetujui oleh Direktur');
        }

        // Ambil path file Word dari database dan pastikan tidak ada duplikasi path
        $wordPath = $spk->word_path;
        $templateProcessor = new TemplateProcessor($wordPath);

        // Sematkan QR code ke dalam dokumen Word
        $templateProcessor->setImageValue('qr_code_pihak_pertama', [
            'path' => $qrCodePath,
            'width' => 70,
            'height' => 70,
            'ratio' => true
        ]);

        // Pastikan folder "final_word" ada
        $this->ensureDirectoryExists('app/public/final_word');
        $newWordFileName = 'SPK_Approved_' . time() . '.docx';
        $newWordPath = storage_path('app/public/final_word/' . $newWordFileName);
        $templateProcessor->saveAs($newWordPath);

        // Convert ke PDF
        $pdfFilePath = $this->convertToPdfUsingILovePDF($newWordPath);

        // Simpan path baru ke database
        $spk->word_path = 'final_word/' . $newWordFileName; // Simpan path relatif ke DB
        $spk->pdf_path = 'final_pdfs/' . basename($pdfFilePath); // Simpan path relatif ke DB
        $spk->qr_code_path = $qrCodePath;
        $spk->save();

        // Hapus file Word setelah konversi
        if (file_exists($newWordPath)) {
            unlink($newWordPath);  // Menghapus file Word
        }
    }

    private function generateQrCode($data)
    {
        $qrCode = new QrCode($data);
        $filePath = storage_path('app/public/qrcodes/' . 'qrcode_' . time() . '.png');
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $result->saveToFile($filePath);
        return $filePath;
    }

    private function convertToPdfUsingILovePDF($wordPath)
    {
        $ilovepdf = new Ilovepdf('project_public_d1c2d3d3f606d8a9e3f3a4d73fc21bd0_yuT4Jc4b8022d84129b013a8f614d6b90b243', 'secret_key_a7b48329a69cae3db7a9f6a0f71ffeb5_fiKORa7e1f0ed7201b75b3c12326de50490fe');
        $task = $ilovepdf->newTask('officepdf');
        $task->addFile($wordPath);
        $task->execute();

        // Pastikan direktori penyimpanan ada
        $downloadPath = storage_path('app/public/final_pdfs');
        $task->download($downloadPath);

        // Nama file PDF
        $pdfFileName = basename($wordPath, '.docx') . '.pdf';
        $pdfFilePath = $downloadPath . '/' . $pdfFileName;

        // Cek apakah file PDF berhasil dibuat
        if (file_exists($pdfFilePath)) {
            return $pdfFilePath;
        } else {
            return null; // Jika file PDF tidak ditemukan
        }
    }

    private function ensureDirectoryExists($path)
    {
        $fullPath = storage_path($path);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
    }
}
