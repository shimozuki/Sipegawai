@extends('layout.base')

@section('title', 'List Pelayanan')

@section('content_header')
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-clipboard3 position-left"></i> <span class="text-semibold">Menu Pelayanan</span> - Daftar Pelayanan</h4>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="active icon-home2 position-left"></i> Pelayanan</li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<div class="panel bg-info">
    <div class="panel-heading">
        <em>
            <h6>Halaman ini menampilkan daftar semua pelayanan yang telah terdaftar. Anda dapat menambahkan, memperbarui, atau menghapus data pelayanan.</h6>
        </em>
        @if ($user->id_role == 3)
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a href="{{ route('pelayanan.create') }}" class="btn btn-sm bg-teal"><i class="icon-plus2"></i> Tambah Pelayanan</a></li>
            </ul>
        </div>
        @endif
    </div>
</div>

<div class="panel panel-flat">
    <br>
    @if ($user->id_role == 1 || $user->id_role == 4)
    <div class="mb-3">
        <form method="GET" action="{{ route('pelayanan.index') }}" class="form-inline">
            <div class="row">
                <div class="col-md-4">
                    <select name="pegawai_id" class="form-control">
                        <option value="">-- Semua Pegawai --</option>
                        @foreach ($daftarPegawai as $pegawai)
                        <option value="{{ $pegawai->id }}" {{ request('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                            {{ $pegawai->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" type="submit"><i class="icon-search4"></i> Filter</button>
                    <a href="{{ route('pelayanan.index') }}" class="btn btn-default">Reset</a>
                </div>
            </div>
        </form>
    </div>
    @endif

    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover table-xs">
            <thead class="bg-primary">
                <tr>
                    <th>No</th>
                    <th>Nama Pelayanan</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    @if ($user->id_role == 3)
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($pelayanan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_pelayanan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pelayanan)->format('d M Y') }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                    @if ($user->id_role == 3)
                    <td class="text-center">
                        <a href="{{ route('pelayanan.edit', $item->id) }}" class="btn btn-sm bg-primary"><i class="icon-pencil"></i> Edit</a>
                        <form action="{{ route('pelayanan.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pelayanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm bg-danger"><i class="icon-trash"></i> Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data pelayanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="text-right mt-4">
            {{ $pelayanan->links() }}
            <div>{{ 'Total Data: ' . $pelayanan->total() }}</div>
        </div>
    </div>
</div>
@endsection