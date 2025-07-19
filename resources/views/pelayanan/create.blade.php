@extends('layout.base')

@section('title', 'Tambah Pelayanan')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-clipboard3"></i> <span class="text-semibold">Menu Pelayanan</span> - Tambah Pelayanan</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('pelayanan.index') }}"><i class="icon-home2 position-left"></i> Daftar Pelayanan</a></li>
            <li class="active">Tambah Pelayanan</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Gunakan formulir berikut untuk menambahkan data pelayanan baru.</h6>
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

<form action="{{ route('pelayanan.store') }}" method="POST">
    @csrf

    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label for="nama_pelayanan">Nama Pelayanan</label>
                <input type="text" name="nama_pelayanan" class="form-control" placeholder="Contoh: Layanan Konsultasi"
                    value="{{ old('nama_pelayanan') }}">
            </div>

            <div class="form-group">
                <label for="tanggal_pelayanan">Tanggal Pelayanan</label>
                <input type="date" name="tanggal_pelayanan" class="form-control"
                    value="{{ old('tanggal_pelayanan') }}">
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Klinik Utama"
                    value="{{ old('lokasi') }}">
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Pelayanan</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan deskripsi singkat...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan Pelayanan</button>
            </div>
        </div>
    </div>
</form>
@endsection