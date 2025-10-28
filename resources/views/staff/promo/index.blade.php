@extends('templates.app')
@section('content')
    <div class="container mt-3">
        @if (Session::get('succes'))
            <div class="alert alert-success">{{ Session::get('succes') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
               <a href="{{ route('staff.promos.trash') }} " class="btn btn-secondary me-2">Data sampah</a>
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Promo</h5>
        <table class="table table-bordered" id="tablePromo">
            <thead>
            <tr>
                <th>#</th>
                <th>Kode Promo</th>
                <th>Total Potongan</th>
                <th>Aksi</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection
@push('script')
    <script>
        //$ di js : memanggil jquery
    $(function(){
        $("#tablePromo").DataTable({
            processing: true, //tanda load pas lg proses data
            serverSide: true, // tanda di proses dibelakang (controller)
            ajax: "{{ route('staff.promos.datatables') }}", //memanggil route 
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                    searchable: false },
                { data: 'promo_code', name: 'promo_code', orderable: true,
                    searchable: true},
                 { data: 'type', name: 'discount', orderable: true,
                    searchable: true },
                 { data: 'btnActions', name: 'btnActions', orderable: false,
                    searchable: false},
                
            ]
        })
    })
    </script>
@endpush
