@extends('layouts.layout')

@php
    $title = 'List Pengajuan Staf';
@endphp

@section('content')
    <div class="container">
        <!-- Pesan status (jika ada) -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <h1>{{ $title }}</h1>

        @if ($pengajuans->isEmpty())
            <p>Tidak ada pengajuan yang tersedia.</p>
        @else
            <div class="table-responsive">
                <table class="table basic-border-table mb-0">
                    <thead>
                        <tr>
                            <th>Nomor Surat</th>
                            <th>Nama Item</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuans as $pengajuan)
                            <tr>
                                <td>
                                    <a href="#0" class="text-primary-600">{{ $pengajuan->no_surat }}</a>
                                </td>
                                <td>{{ $pengajuan->nama_item }}</td>
                                <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                                <td>{{ formatRupiah($pengajuan->total_biaya) }}</td>
                                <td>{{ $pengajuan->status }}</td>
                                <td>
                                    <a href="{{ route('pengajuan.view', $pengajuan->id) }}" class="text-primary-600">View
                                        Document</a>
                                    <form action="{{ route('pengajuan.action', $pengajuan->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" name="action" value="acc"
                                            class="btn btn-success btn-sm">Acc</button>
                                        <button type="submit" name="action" value="tolak"
                                            class="btn btn-danger btn-sm">Tolak</button>
                                    </form>
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
