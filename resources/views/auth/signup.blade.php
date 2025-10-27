<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TIX ID - Login</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>
    <form class="w-50 d-block mx-auto my-5" method="POST" action="{{ route('signup.register') }}">
        {{-- csrf : generate token yg menjadi syarat bagi FE = mengirim data ke server/ BE --}}
        @csrf
        <div class="row mb-4">
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example1"
                        class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                        value="{{ old('first_name') }}" />
                    <label class="form-label" for="form3Example1">First name</label>
                </div>
                @error('first_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example2"
                        class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                        value="{{ old('last_name') }}" />
                    <label class="form-label" for="form3Example2">Last name</label>
                </div>
                @error('last_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <!-- Email input -->
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="form3Example3" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" />
            <label class="form-label" for="form3Example3">Email address</label>
        </div>

        <!-- Password input -->
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" id="form3Example4" class="form-control @error('password') is-invalid @enderror"
                name="password" />
            <label class="form-label" for="form3Example4">Password</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}">Kembali</a>
        </div>
    </form>

    <!-- Bootstrap & MDB Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
</body>

</html>
