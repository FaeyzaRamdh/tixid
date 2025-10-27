@extends('templates.app')
@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center my-3">Edit Promo</h5>
        <form method="POST" action="{{route('staff.promos.update', $promos['id'])}}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" class="form-control @error('promo_code') is-invalid @enderror" id="promo_code"
                    name="promo_code" value="{{$promos['promo_code']}}">
            </div>
           <div class="mb-3">
            <label class="form-label" for="type">Tipe Promo</label>
            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                <option value="">Pilih Tipe</option>
                <option value="persen" {{ $promos->type == 'persen' ? 'selected' : '' }}>%</option>
                <option value="rupiah" {{ $promos->type == 'rupiah' ? 'selected' : '' }}>Rp</option>
            </select>
            @error('type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Jumlah Potongan</label>
                <input type="number" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{$promos['discount']}}">
            </div>
            <button class="btn btn-primary" type="submit">Tambah Promo</button>
        </form>
    </div>
@endsection