@extends('templates.app')

@section('content')
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-primary">Kembali</a>
        </div>

        <h3 class="my-3">Data Bioskop</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Bioskop</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>

            @foreach ($cinemaTrash as $key => $item)
                <tr>
                     <td>{{ $key + 1 }}</td>
                    {{-- name dan location dari fillable --}}
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['location'] }}</td>
                    <td class="d-flex">
                        <form action="{{ route('admin.cinemas.restore', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success mx-2">Kembalikan</button>
                        </form>
                          <form action="{{ route('admin.cinemas.delete_permanent', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger mx-2">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
