@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center my-3">Buat Promo</h5>
        <form method="POST" action="{{ route('staff.promos.store') }}">
            @csrf
            <div class="mb-3">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" class="form-control @error('promo_code') is-invalid @enderror" id="promo_code"
                    name="promo_code">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Promo</label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                    <option disabled selected>Pilih</option>
                    <option value="percent">Persen</option>
                    <option value="rupiah">Rupiah</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" class="form-control @error('discount') is-invalid @enderror" name="discount">
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Tambah Promo</button>
        </form>
    </div>
@endsection