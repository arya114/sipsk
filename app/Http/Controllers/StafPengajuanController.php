<?php

namespace App\Http\Controllers;

use App\Models\Persetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\TcpdfFpdi;

class StafPengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Persetujuan::all();
        return view('list-pengajuan-staf', compact('pengajuans'));
    }
    public function action(Request $request, $id)
    {
        $pengajuan = Persetujuan::findOrFail($id);
        $user = Auth::user(); // Ambil pengguna yang sedang login

        if ($request->input('action') === 'acc') {
            $pengajuan->status = 'Menunggu Persetujuan Direktur';
            $pengajuan->keterangan = 'Pengajuan di-acc oleh staf dan menunggu persetujuan direktur.';

            // Ambil path tanda tangan dari pengguna
            $signatureImagePath = $user->signature_path; // Pastikan ini sudah ada
            $imageUrl = url('storage/' . $signatureImagePath); // Membuat URL dari path

            // Generate QR Code dengan URL gambar tanda tangan
            $qrImagePath = $this->generateQrCode($imageUrl);

            // Tempelkan QR Code ke PDF
            $pdfPath = $pengajuan->pdf_path;
            $pdfFileName = basename($pdfPath); // Ambil hanya nama file
            $fullPdfPath = storage_path('app/public/' . $pdfFileName);

            if (file_exists($fullPdfPath)) {
                $newPdfPath = $this->attachQrCodeToPdf($fullPdfPath, $qrImagePath);

                // Simpan dan respons
                $pengajuan->pdf_path = 'pdf/' . basename($newPdfPath); // Simpan path baru ke database
                $pengajuan->save();

                return redirect()->route('list.pengajuan.staf')->with('status', 'Pengajuan berhasil diperbarui dengan QR Code.');
            } else {
                return redirect()->route('list.pengajuan.staf')->with('error', 'File PDF asli tidak ditemukan.');
            }
        }

        // ... logika lain untuk menolak pengajuan ...
    }

    private function generateQrCode($imageUrl)
    {
        $qrCode = new QrCode($imageUrl);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $fileName = 'qrcode_' . time() . '.png';
        $filePath = storage_path('app/public/' . $fileName);
        $result->saveToFile($filePath);

        return $filePath;
    }

    private function attachQrCodeToPdf($pdfPath, $qrImagePath)
    {
        $pdf = new TcpdfFpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pageCount = $pdf->setSourceFile($pdfPath);

        if ($pageCount > 0) {
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $templateId = $pdf->importPage($pageNo);

                if ($templateId !== false) {
                    $pdf->useTemplate($templateId, 0, 0, 210, 297);
                    $pdf->Image($qrImagePath, 160, 250, 30, 30, 'PNG'); // Sesuaikan posisi QR Code
                }
            }
        }

        $newPdfPath = storage_path('app/public/' . 'updated_' . basename($pdfPath));
        $pdf->Output($newPdfPath, 'F');

        unlink($qrImagePath); // Hapus QR Code setelah digunakan

        return $newPdfPath;
    }
}
