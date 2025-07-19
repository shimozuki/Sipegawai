@extends('layout.base')

@section('title', 'List Kegiatan')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-calendar2 position-left"></i> <span class="text-semibold">Menu Kegiatan</span> - Daftar Kegiatan</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="active icon-home2 position-left"></i> Kegiatan</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Halaman ini menampilkan daftar semua kegiatan yang telah terdaftar. Anda dapat menambahkan, melihat detail, atau menghapus kegiatan.</h6>
        </em>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="{{ route('kegiatan.create') }}" class="btn btn-sm bg-teal"><i class="icon-plus2"></i> Tambah Kegiatan</a></li>
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
                    <th>Nama Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kegiatan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d M Y') }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                    <td class="text-center">
                        <a href="{{ route('kegiatan.edit', $item->id) }}" class="btn btn-sm bg-primary"><i class="icon-pencil"></i> Edit</a>
                        <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm bg-danger"><i class="icon-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data kegiatan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-right mt-4">
            {{ $kegiatan->links() }}
            <div>{{ 'Total Data: ' . $kegiatan->total() }}</div>
        </div>
    </div>
</div>
@endsection