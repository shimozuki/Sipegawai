@extends('layout.base')

@section('title', 'Edit Inventaris')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-box"></i> <span class="text-semibold">Menu Inventaris</span> - Edit Inventaris</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ route('inventaris.index') }}"><i class="icon-home2 position-left"></i> Daftar Inventaris</a></li>
            <li class="active">Edit Inventaris</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Form ini digunakan untuk mengubah informasi inventaris.</h6>
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

<form method="POST" action="{{ route('inventaris.update', $inventaris->id) }}">
    @csrf
    @method('PUT')

    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control"
                    value="{{ old('nama_barang', $inventaris->nama_barang) }}" placeholder="Nama Barang">
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" class="form-control"
                    value="{{ old('kategori', $inventaris->kategori) }}" placeholder="Kategori Barang">
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control"
                    value="{{ old('jumlah', $inventaris->jumlah) }}" placeholder="Jumlah">
            </div>

            <div class="form-group">
                <label for="kondisi">Kondisi</label>
                <select name="kondisi" class="form-control">
                    <option value="baik" {{ old('kondisi', $inventaris->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ old('kondisi', $inventaris->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="hilang" {{ old('kondisi', $inventaris->kondisi) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control"
                    value="{{ old('lokasi', $inventaris->lokasi) }}" placeholder="Lokasi Barang">
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="4" placeholder="Keterangan tambahan">{{ old('keterangan', $inventaris->keterangan) }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Perbarui Inventaris</button>
            </div>
        </div>
    </div>
</form>
@endsection