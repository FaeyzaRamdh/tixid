@extends('templates.app')
@section('content')
    <div class="container my-5">
        <h5 class="mb-5">Seluruh Film Sedang Tayang</h5>
        <form action="" method="GET">
            @csrf
            <div class="row">
                <div class="col-10">
                    <input type="text" name="search_movie" placeholder="Cari Judul..."
                    class="form-control">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-center flex-wrap gap-2 my-3">
            @foreach ($movies as $movie)
                <div class="card" style="width: 15rem; margin: 5px">
                    <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top"
                        alt="{{ $movie->title }} "style="height: 300px; object-fit: cover;" />
                    <div class="card-body" style="padding: 0 !important">
                        <p class="card-text text-center bg-primary py-2">
                            <a href="{{ route('schedules.detail', $movie['id']) }}" class="text-warning"><b>Beli
                                    Tiket</b></a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endsection
