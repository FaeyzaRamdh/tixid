{{-- panggil file template --}}
@extends('templates.app')
{{-- ngisi yield --}}
@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success w-100">
            {{ Session::get('success') }}
            <b>Selamat datang, {{ Auth::user()->name }}</b>
        </div>
    @endif


    @if (Session::get('logout'))
        {{-- Auth::user()->field : mengambil data orang yang login, field dari fillable model --}}
        <div class="alert alert-warning w-100">{{ Session::get('logout') }}</div>
    @endif




    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center w-100" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-location-dot me-2"></i>Bogor
        </button>
        <ul class="dropdown-menu w-100">
            <li><a class="dropdown-item" href="#">Bogor</a></li>
            <li><a class="dropdown-item" href="#">Jakarta</a></li>
            <li><a class="dropdown-item" href="#">Bandung</a></li>
        </ul>
    </div>

    <!-- Carousel wrapper -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <img src="https://asset.tix.id/microsite_v2/c0ca475a-7eeb-44c4-b556-8adf89af790c.jpeg" class="d-block w-100"
                    alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img src="https://asset.tix.id/microsite_v2/fc597165-a299-47dc-8acc-6769e61b088c.webp" class="d-block w-100"
                    alt="Canyon at Nigh" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img src="https://asset.tix.id/microsite_v2/2f887f81-fa68-4d74-8585-f7388d29a2a5.webp" class="d-block w-100"
                    alt="Cliff Above a Stormy Sea" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                </div>
            </div>
        </div>
        <!-- Inner -->

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel wrapper -->

    <div class="container my-3">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="mt-3">
                <h5>
                    <i class="fa-solid fa-clapperboard"></i> Sedang Tayang
            </div>
            <div>
                <a href="{{ route('home.movies.active') }}" class="btn btn-warning rounded-pill"> Semua </a>
            </div>
        </div>
        <div class="d-flex my-3 gap-2">
            <a href="{{ route('home.movies.active') }}" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>Semua
                    Film</small></a>
            <a href="" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>XXI</small></a>
            <a href="" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>CGV</small></a>
            <a href="" class="btn btn-outline-primary rounded-pill"
                style="padding: 5px 10px !important"><small>Cinepolis</small></a>
        </div>
        <div class="d-flex justify-content-center gap-4 my-3">

            <div class="d-flex justify-content-center gap-4 my-3">
                @foreach ($movies as $movie)
                    <div class="card" style="width: 13rem;">
                        <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top"
                            alt="{{ $movie->title }} "style="height: 300px; object-fit: cover;" />
                        <div class="card-body" style="padding: 0 !important">
                            <p class="card-text text-center bg-primary py-2">
                                <a href="{{ route('schedules.detail', $movie->id) }}" class="text-warning"><b>Beli
                                        Tiket</b></a>
                            </p>    
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <footer class="bg-body-tertiary text-center text-lg-start">
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2020 Copyright:
                <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
            </div>
            <!-- Copyright -->
        </footer>
    </div>
    </footer>
@endsection
