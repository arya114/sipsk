@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Konfirmasi Persetujuan Pengajuan</h1>
    <p>Apakah Anda yakin ingin menyetujui pengajuan ini?</p>

    <form action="{{ route('pengajuan.action', $spk->id) }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="acc">
        <button type="submit" class="btn btn-success">Setujui Pengajuan</button>
    </form>
</div>
@endsection
