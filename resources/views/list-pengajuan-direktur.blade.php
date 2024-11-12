@extends('layouts.layout')

@php
    $title = 'List Pengajuan Direktur';
@endphp

@section('content')
    <div class="container">
        <!-- Menampilkan alert jika ada pesan status atau error -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h1>{{ $title }}</h1>

        @if ($spks->isEmpty())
            <p>Tidak ada pengajuan yang tersedia.</p>
        @else
            <div class="table-responsive">
                <table class="table basic-border-table mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Surat</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spks as $spk)
                            <tr>
                                <td>
                                    <a href="#0" class="text-primary-600">{{ $spk->nomor_surat }}</a>
                                </td>
                                <td>{{ $spk->tanggal_spk }}</td>
                                <td>{{ formatRupiah($spk->gaji) }}</td>
                                <td>{{ $spk->status }}</td>
                                <td>
                                    @if ($spk->status === 'Pengajuan Disetujui Direktur')
                                        <!-- Tombol Download jika status disetujui -->
                                        <a href="{{ asset('storage/' . $spk->pdf_path) }}" class="btn btn-primary btn-sm" download>Download PDF</a>
                                    @else
                                        <!-- Tombol Acc dan Tolak jika status belum disetujui -->
                                        <form action="{{ route('pengajuan.action', $spk->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" name="action" value="acc" class="btn btn-success btn-sm">Acc</button>
                                            <button type="submit" name="action" value="tolak" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@php
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 2, ',', '.');
    }
@endphp
