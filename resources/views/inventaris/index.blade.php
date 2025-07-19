@extends('layout.base')

@section('title', 'List Inventaris')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-box position-left"></i> <span class="text-semibold">Menu Inventaris</span> - Daftar Inventaris</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="active icon-home2 position-left"></i> Inventaris</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Halaman ini menampilkan daftar barang inventaris yang tercatat. Anda dapat menambahkan, memperbarui, atau menghapus data inventaris.</h6>
        </em>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="{{ route('inventaris.create') }}" class="btn btn-sm bg-teal"><i class="icon-plus2"></i> Tambah Inventaris</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="panel panel-flat">
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover table-xs">
            <thead class="bg-primary">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventaris as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>
                        <span class="label 
                            @if ($item->kondisi == 'baik') label-success
                            @elseif ($item->kondisi == 'rusak') label-warning
                            @elseif ($item->kondisi == 'hilang') label-danger
                            @endif">
                            {{ ucfirst($item->kondisi) }}
                        </span>
                    </td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td>{{ Str::limit($item->keterangan, 50) }}</td>
                    <td class="text-center">
                        <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-sm bg-primary"><i class="icon-pencil"></i> Edit</a>
                        <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm bg-danger"><i class="icon-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data inventaris</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-right mt-4">
            {{ $inventaris->links() }}
            <div>{{ 'Total Data: ' . $inventaris->total() }}</div>
        </div>
    </div>
</div>
@endsection