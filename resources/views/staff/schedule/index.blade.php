@extends('templates.app')

@section('content')
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            {{-- karena modal tidak berubah isinya munculin pake bootstrap togle ddan target --}}
            <a href="{{ route('staff.schedules.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('staff.schedules.trash') }}" class="btn btn-secondary me-2">Data sampah</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                Tambah Data
            </button>
        </div>
        <h3 class="my-3">Data Jadwal Tayangan</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif


        <table class="table table-bordered" id="tableSchedule">
            <thead>
            <tr>
                <th>#</th>
                <th>Nama Bioskop</th>
                <th>Judul Film</th>
                <th>Jam Tayang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        </table>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('staff.schedules.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cinema_id" class="col-form-label">Bioskop :</label>
                            <select name="cinema_id" id="cinema_id"
                                class="form-select @error('cinema_id') is-invalid @enderror">

                                <option disabled hidden selected>Pilih Bioskop</option>
                                @foreach ($cinemas as $cinema)
                                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                                @endforeach
                            </select>
                            @error('cinema_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="movie_id" class="col-form-label">Film :</label>
                            <select name="movie_id" id="movie_id"
                                class="form-select @error('movie_id') is-invalid @enderror">
                                <option disabled hidden selected>Pilih Film</option>
                                @foreach ($movies as $movie)
                                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                                @endforeach
                            </select>
                            @error('movie_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga :</label>
                            <input type="number" name="price" id="price"
                                class="form-control @error('price') is-invalid @enderror">

                            </input>
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            {{-- jika ada error validasi array hours --}}
                            @if ($errors->has('hours.*'))
                                <small class="text-danger">{{ $errors->first('hours.*') }}</small><br>
                            @endif
                            <label for="hours" class="form-label">Jam Tayang :</label>
                            <input type="time" name="hours[]" id="hours" class="form-control"
                                @if ($errors->has('hours.*')) is-invalid @endif>
                            {{-- wadah untuk perubahan input dari js --}}
                            <div id="additionalInput"></div><br>
                            <span class="text-primary mt-3" style="cursor: pointer" onclick="addInput()">+ Tambah
                                Input</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        //$ di js : memanggil jquery
    $(function(){
        $("#tableSchedule").DataTable({
            processing: true, //tanda load pas lg proses data
            serverSide: true, // tanda di proses dibelakang (controller)
            ajax: "{{ route('staff.schedules.datatables') }}", //memanggil route 
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,
                    searchable: false },
                { data: 'cinemaName', name: 'cinemaName', orderable: true,
                    searchable: true},
                 { data: 'movieTitle', name: 'movieTitle', orderable: true,
                    searchable: true },
                 { data: 'priceFormatted', name: 'priceFormatted', orderable: true,
                    searchable: true},
                 { data: 'hoursList', name: 'hoursList', orderable: false,
                    searchable: false },
                  { data: 'btnActions', name: 'btnActions', orderable: false,
                    searchable: false},
                
            ]
        })
    })
    </script>
    <script>
        function addInput() {
            let content = '<input type="time" name="hours[]" class="form-control mt-3">';
            // panggil bagian yang akan di isi
            let wadah = document.querySelector('#additionalInput');
            // tambahkan konten, karena akan terus bertambah gunakan +=
            wadah.innerHTML += content;
        }
    </script>
    {{-- cek apakah ada eror di php atau ngga --}}
    @if ($errors->any())
        <script>
            //jika ada error munculkan modal
            let modalAdd = document.querySelector('#modalAdd');
            new bootstrap.Modal(modalAdd).show();
        </script>
    @endif
@endpush
