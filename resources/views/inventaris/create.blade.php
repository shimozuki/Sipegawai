@extends('layout.base')

@section('title', 'Tambah Inventaris')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-box"></i> <span class="text-semibold">Menu Inventaris</span> - Tambah Inventaris</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('inventaris.index') }}"><i class="icon-home2 position-left"></i> Daftar Inventaris</a></li>
            <li class="active">Tambah Inventaris</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Gunakan formulir berikut untuk menambahkan data inventaris baru.</h6>
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

<form action="{{ route('inventaris.store') }}" method="POST">
    @csrf

    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Laptop Acer Predator Neo 6"
                    value="{{ old('nama_barang') }}">
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" class="form-control" placeholder="Contoh: Elektronik"
                    value="{{ old('kategori') }}">
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" placeholder="Contoh: 5"
                    value="{{ old('jumlah') }}">
            </div>

            <div class="form-group">
                <label for="kondisi">Kondisi</label>
                <select name="kondisi" class="form-control">
                    <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Ruang Lab 1"
                    value="{{ old('lokasi') }}">
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="4" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan Inventaris</button>
            </div>
        </div>
    </div>
</form>
@endsection