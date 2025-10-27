@extends('templates.app')

@section('content')
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-primary">Kembali</a>
        </div>

        <h3 class="my-3">Data Film</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>
                      @foreach ($movieTrash as $key => $item)
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
                            <form action="{{ route('admin.movies.restore', $item->id) }}" method="POST">
                                @csrf
                                  @method('PATCH')
                            <button class="btn btn-success mx-2">Kembalikan</button>
                        </form>
                          <form action="{{ route('admin.movies.delete_permanent', $item->id) }}" method="POST">
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

