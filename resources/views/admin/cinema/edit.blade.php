@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center mt-3">Edit Data Bioskop</h5>
        <form action="{{ route('admin.cinemas.update', $cinema['id']) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop :</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ $cinema['name'] }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi :</label>
                <textarea id="location" cols="30" rows="5" class="form-control @error('location') is-invalid @enderror"
                    name="location">{{ $cinema['location'] }}</textarea>
                @error('location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Tambah Data</button>
        </form>
    </div>
@endsection
