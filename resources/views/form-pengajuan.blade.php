@extends('layouts.layout')

@php
    $title = 'Form Pengajuan SPK';
    $subTitle = 'Submit Your Data Request';
@endphp

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>
        <h5>{{ $subTitle }}</h5>
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
        <form action="{{ route('persetujuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Data Pihak Kedua -->
            <div class="form-group">
                <label for="nama_pihak_kedua">Nama Pihak Kedua:</label>
                <input type="text" name="nama_pihak_kedua" id="nama_pihak_kedua" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="jabatan_pihak_kedua">Jabatan Pihak Kedua:</label>
                <input type="text" name="jabatan_pihak_kedua" id="jabatan_pihak_kedua" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="nik_pihak_kedua">NIK Pihak Kedua:</label>
                <input type="text" name="nik_pihak_kedua" id="nik_pihak_kedua" class="form-control" required
                    pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka">
            </div>

            <div class="form-group">
                <label for="alamat_pihak_kedua">Alamat Pihak Kedua:</label>
                <input type="text" name="alamat_pihak_kedua" id="alamat_pihak_kedua" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email_pihak_kedua">Email Pihak Kedua:</label>
                <input type="text" name="email_pihak_kedua" id="email_pihak_kedua" class="form-control"
                    placeholder="example@gmail.com" required>
            </div>
            <div class="form-group">
                <label for="nomor_telepon_pihak_kedua">Nomor Telepon Pihak Kedua:</label>
                <input type="text" name="nomor_telepon_pihak_kedua" id="nomor_telepon_pihak_kedua" class="form-control"
                    required>
            </div>
            <!-- Detail Kontrak -->
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai:</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai:</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="gaji">Gaji:</label>
                <input type="number" name="gaji" id="gaji" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="bank">Bank:</label>
                <input type="text" name="bank" id="bank" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="nomor_rekening">Nomor Rekening:</label>
                <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ttd">Upload Gambar Tanda Tangan:</label>
                <input type="file" name="ttd" id="ttd" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Kirim</button>
        </form>
    </div>
@endsection
