@extends('templates.app')

@section('content')
    <div class="container my-5">
        @if (Session::get('succes'))
            <div class="alert alert-success">{{ Session::get('succes') }}</div>
        @endif
         @if (Session::get('error'))
            <div class="alert alert-danger"> {{ Session::get('error') }}</div>
        @endif
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary">Export (.xlsx)</a>
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mb-5">Data Film</h5>
        <table class="table table-bordered">
            <tr class="text-center">
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>
            @foreach ($movies as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $item['poster']) }}" width="120" alt="Gambar">
                    </td>
                    <td>{{ $item['title'] }}</td>
                    <td class="text-center">
                        @if ($item['activated'] == 1)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-primary me-1" onclick="showmodal({{ $item }})">Detail</button>
                        <a href="{{ route('admin.movies.edit', $item['id']) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('admin.movies.delete', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                        <form action="{{ route('admin.movies.nonaktif', $item['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @if ($item['activated'] == 1)
                                <button class="btn btn-warning">Non-Aktif Film</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="modal fade" id="modaldetail" tabindex="-1" aria-labelledby="modaldetailLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modaldetailtitle">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modaldetailbody">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showmodal(item) {
            let image = "{{ asset('storage/') }}" + "/" + item.poster;
            let content = `
            <img src="${image}" width="120" class="d-block mx-auto my-3">
            <ul>
                <li>Judul : ${item.title} </li>
                <li>Durasi : ${item.duration} </li>
                <li>Genre : ${item.genre} </li>
                <li>Director : ${item.director} </li>
                <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}</span></li>
                <li>Sinopsis : ${item.description} </li>
            `;
            let modaldetailbody = document.querySelector('#modaldetailbody');
            modaldetailbody.innerHTML = content;
            let modaldetail = document.querySelector('#modaldetail');
            new bootstrap.Modal(modaldetail).show();
        }
    </script>
@endpush
