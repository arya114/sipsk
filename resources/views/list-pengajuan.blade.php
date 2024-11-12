@extends('layouts.layout')

@php
    $title = 'List Pengajuan SPK';
@endphp

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{-- Menampilkan Alert jika ada pesan success, error, atau info --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($spks->isEmpty())
            <p>Tidak ada pengajuan yang tersedia.</p>
        @else
            <div class="table-responsive">
                <table class="table border-primary-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">NO.</th>
                            <th scope="col">Nomor Surat</th>
                            <th scope="col">Tanggal Pengajuan</th>
                            <th scope="col">Gaji</th>
                            <th scope="col">Status</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spks as $spk)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label"
                                            for="flexCheckDefault">{{ $loop->iteration }}</label>
                                    </div>
                                </td>
                                <td>{{ $spk->nomor_surat }}</td>
                                <td>{{ $spk->tanggal_spk }}</td>
                                <td>{{ formatRupiah($spk->gaji) }}</td>
                                <td>
                                    @if ($spk->status == 'Menunggu Persetujuan Direktur')
                                        <span
                                            class="bg-warning-focus text-warning-main px-32 py-4 rounded-pill fw-medium text-sm">Menunggu
                                            Persetujuan Direktur</span>
                                    @elseif($spk->status == 'Pengajuan Disetujui Direktur')
                                        <span
                                            class="bg-success-focus text-success-main px-32 py-4 rounded-pill fw-medium text-sm">Disetukui
                                            Direktur</span>
                                    @elseif($spk->status == 'Rejected')
                                        <span
                                            class="bg-danger-focus text-danger-main px-32 py-4 rounded-pill fw-medium text-sm">Rejected</span>
                                    @else
                                        <span
                                            class="bg-secondary-focus text-secondary-main px-32 py-4 rounded-pill fw-medium text-sm">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ $spk->keterangan }}</td>
                                <td>
                                    @if ($spk->status == 'Pengajuan Disetujui Direktur')
                                        <a href="{{ asset('storage/' . $spk->pdf_path) }}" class="btn btn-primary"
                                            download>Download PDF</a>
                                    @else
                                        <span
                                            class="bg-secondary-focus text-secondary-main px-32 py-4 rounded-pill fw-medium text-sm">Menunggu
                                            Persetujuan</span>
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
        return 'Rp ' . number_format($angka);
    }
@endphp
