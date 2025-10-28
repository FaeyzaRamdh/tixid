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
        <table class="table table-bordered" id="tableMovie">
            <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>
            </thead>
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
{{-- mengisi stack  --}}
@push('script')
    <script>
        //$ di js : memanggil jquery
    $(function(){
        $("#tableMovie").DataTable({
            processing: true, //tanda load pas lg proses data
            serverSide: true, // tanda di proses dibelakang (controller)
            ajax: "{{ route('admin.movies.datatables') }}", //memanggil route 
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                    searchable: false },
                { data: 'imgPoster', name: 'imgPoster', orderable: false,
                    searchable: false},
                 { data: 'title', name: 'title', orderable: true,
                    searchable: true },
                { data: 'activeBadge', name: 'activeBadge', orderable: true,
                    searchable: true},
                 { data: 'btnActions', name: 'btnActions', orderable: false,
                    searchable: false},
                
            ]
        })
    })

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
