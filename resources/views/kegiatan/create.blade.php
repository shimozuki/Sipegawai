@extends('layout.base')

@section('title', 'Tambah Kegiatan')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-calendar2"></i> <span class="text-semibold">Menu Kegiatan</span> - Tambah Kegiatan</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('kegiatan.index') }}"><i class="icon-home2 position-left"></i> Daftar Kegiatan</a></li>
            <li class="active">Tambah Kegiatan</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Gunakan formulir berikut untuk menambahkan kegiatan baru.</h6>
        </em>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> Ada kesalahan dalam input Anda.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('kegiatan.store') }}" method="POST">
    @csrf

    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Rapat Bulanan"
                    value="{{ old('nama_kegiatan') }}">
            </div>

            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
                <input type="date" name="tanggal_kegiatan" class="form-control"
                    value="{{ old('tanggal_kegiatan') }}">
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Ruang Rapat Lt.2"
                    value="{{ old('lokasi') }}">
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan deskripsi singkat...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
            </div>
        </div>
    </div>
</form>
@endsection