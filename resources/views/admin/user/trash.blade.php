@extends('templates.app')

@section('content')
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Kembali</a>
        </div>

        <h3 class="my-3">Data Anggota</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
        @php
            $roleColors = [
                'admin' => 'danger',
                'staff' => 'primary',
                'user' => 'success',
            ];
        @endphp


        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            @foreach ($userTrash as $key => $item)
                <tr>
                     <td>{{ $key + 1 }}</td>
                    {{-- name dan location dari fillable --}}
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['email'] }}</td>
                    <td> 
                        <span class="badge badge-{{ $roleColors[$item->role] ?? 'secondary' }}">
                            {{ ucfirst($item->role) }}
                        </span>
                    </td>
                    <td class="d-flex">
                 
                        <form action="{{ route('admin.users.restore', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success mx-2">Kembalikan</button>
                        </form>
                          <form action="{{ route('admin.users.delete_permanent', $item->id) }}" method="POST">
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
