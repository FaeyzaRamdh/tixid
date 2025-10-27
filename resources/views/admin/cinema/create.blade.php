@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center my-3">Tambah data bioskkop</h5>
        <form method="POST" action="{{ route('admin.cinemas.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-table">Nama Bioskop :</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-table">Lokasi :</label>
                <textarea id="location" cols="30" rows="3" class="form-control @error('location') is-invalid @enderror"
                    name="location"></textarea>
                @error('location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Tambah data</button>
        </form>
    </div>
@endsection
