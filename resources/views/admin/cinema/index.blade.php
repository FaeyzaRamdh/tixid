@extends('templates.app') c

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger"> {{ Session::get('error') }}</div>
        @endif
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.cinemas.export') }}" class="btn btn-secondary">Export (.xlsx)</a>
            <a href="{{ route('admin.cinemas.trash') }} " class="btn btn-secondary me-2">Data sampah</a>
            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Bioskop</h5>
        <table class="table table-bordered" id="tableCinema">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Bioskop</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            {{-- $cinemas dari impact --}}
            {{-- foreach karena $cinemas pake ::all() data nya lebih dari satu dan berbentuk array --}}
            </thead>
        </table>       
    </div>
@endsection
{{-- mengisi stack  --}}
@push('script')
    <script>
        //$ di js : memanggil jquery
    $(function(){
        $("#tableCinema").DataTable({
            processing: true, //tanda load pas lg proses data
            serverSide: true, // tanda di proses dibelakang (controller)
            ajax: "{{ route('admin.cinemas.datatables') }}", //memanggil route 
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                    searchable: false },
                { data: 'name', name: 'name', orderable: true,
                    searchable: true},
                 { data: 'location', name: 'location', orderable: true,
                    searchable: true },
                 { data: 'btnActions', name: 'btnActions', orderable: false,
                    searchable: false},
                
            ]
        })
    })
    </script>
@endpush

