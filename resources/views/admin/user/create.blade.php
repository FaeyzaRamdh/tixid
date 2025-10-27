@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center my-3">Tambah data Petugas & Staff</h5>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-table">Nama Petugas :</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-table">Email :</label>
                <input id="email" cols="30" rows="3"
                    class="form-control @error('email') is-invalid @enderror" name="email">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-table">Password :</label>
                <input id="password" cols="30" rows="3"
                    class="form-control @error('password') is-invalid @enderror" name="password">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Tambah data</button>
        </form>

    </div>
@endsection
