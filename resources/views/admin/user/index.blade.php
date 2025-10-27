@extends('templates.app')

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
              <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Petugas</h5>


        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            @foreach ($users as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['email'] }}</td>
                    <td class="text-center">
                        @if ($item->role == 'admin')
                            <span class="badge badge-primary">Admin</span>
                        @else
                            <span class="badge badge-success">Staff</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $item['id']) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('admin.users.delete', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
