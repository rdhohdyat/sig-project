@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Daftar Fasilitas</h1>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
        Tambah Fasilitas
    </button>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($fasilitas->isEmpty())
        <p class="alert alert-warning">Belum ada data fasilitas.</p>
    @else
        <div class="table-responsive">
            <table id="fasilitasTable" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Kecamatan</th>
                        <th>Alamat</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Foto</th>
                        <th>Jam Operasional</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fasilitas as $item)
                        <tr>
                            <td>{{ $item->no }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->kecamatan }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                            <td>
                                @if($item->foto)
                                    <img src="{{ $item->foto }}" alt="Foto" style="width: 100px; height: auto;">
                                @else
                                    Tidak ada foto
                                @endif
                            </td>
                            <td>{{ $item->jam_buka }} - {{ $item->jam_tutup }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editFacilityModal{{ $item->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <div class="modal fade" id="editFacilityModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="editFacilityModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editFacilityModalLabel{{ $item->id }}">Edit
                                                    Fasilitas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('fasilitas.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" id="nama" name="nama"
                                                            value="{{ $item->nama }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kategori" class="form-label">Kategori</label>
                                                        <input type="text" class="form-control" id="kategori" name="kategori"
                                                            value="{{ $item->kategori }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kecamatan" class="form-label">Kecamatan</label>
                                                        <input type="text" class="form-control" id="kecamatan" name="kecamatan"
                                                            value="{{ $item->kecamatan }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="alamat" class="form-label">Alamat</label>
                                                        <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                                            required>{{ $item->alamat }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="latitude" class="form-label">Latitude</label>
                                                        <input type="text" class="form-control" id="latitude" name="latitude"
                                                            value="{{ $item->latitude }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="longitude" class="form-label">Longitude</label>
                                                        <input type="text" class="form-control" id="longitude" name="longitude"
                                                            value="{{ $item->longitude }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="foto" class="form-label">Foto (URL)</label>
                                                        <input type="text" class="form-control" id="foto" name="foto"
                                                            value="{{ $item->foto }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jam_buka" class="form-label">Jam Buka</label>
                                                        <input type="time" class="form-control" id="jam_buka" name="jam_buka"
                                                            value="{{ $item->jam_buka }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jam_tutup" class="form-label">Jam Tutup</label>
                                                        <input type="time" class="form-control" id="jam_tutup" name="jam_tutup"
                                                            value="{{ $item->jam_tutup }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="keterangan" class="form-label">Keterangan</label>
                                                        <textarea class="form-control" id="keterangan" name="keterangan"
                                                            rows="3">{{ $item->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('fasilitas.destroy', $item->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif
</div>

<div class="modal fade" id="addFacilityModal" tabindex="-1" aria-labelledby="addFacilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFacilityModalLabel">Tambah Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('fasilitas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="jam_buka" class="form-label">Jam Buka</label>
                        <input type="time" class="form-control" id="jam_buka" name="jam_buka" required>
                    </div>
                    <div class="mb-3">
                        <label for="jam_tutup" class="form-label">Jam Tutup</label>
                        <input type="time" class="form-control" id="jam_tutup" name="jam_tutup" required>
                    </div>
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude"
                            placeholder="Contoh: -6.200000" required>
                    </div>
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude"
                            placeholder="Contoh: 106.816666" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto (URL)</label>
                        <input type="text" class="form-control" id="foto" name="foto" placeholder="Masukkan URL foto">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                            placeholder="Tambahkan keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection