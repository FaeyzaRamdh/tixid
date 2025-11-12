@extends('templates.app')

@section('content')
<div class="container card p-4 my-5 shadow">
    <div class="card-body">
        <h5 class="text-center mb-4"><b>Ringkasan Order</b></h5>

        <div class="d-flex gap-4">
            {{-- Poster Film --}}
            <img src="{{ asset('storage/' . $ticket['schedule']['movie']['poster']) }}" 
                 alt="Poster {{ $ticket['schedule']['movie']['title'] }}">

            {{-- Detail Film --}}
            <div class="mt-2">
                <b class="text-warning d-block">{{ $ticket['schedule']['cinema']['name'] }}</b>
                <b class="fs-5 d-block">{{ $ticket['schedule']['movie']['title'] }}</b>

                <table class="mt-3">
                    <tr>
                        <td width="120">Genre</td>
                        <td width="10">:</td>
                        <td>{{ $ticket['schedule']['movie']['genre'] }}</td>
                    </tr>
                    <tr>
                        <td>Durasi</td>
                        <td>:</td>
                        <td>{{ $ticket['schedule']['movie']['duration'] }} menit</td>
                    </tr>
                    <tr>
                        <td>Sutradara</td>
                        <td>:</td>
                        <td>{{ $ticket['schedule']['movie']['director'] }}</td>
                    </tr>
                    <tr>
                        <td>Batas Usia</td>
                        <td>:</td>
                        <td>
                            <span class="badge badge-danger">
                                {{ $ticket['schedule']['movie']['age_rating'] }} +
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <b class="text-secondary">NOMOR PESANAN : {{ $ticket['id'] }}</b>
        <br><b>Detail Pesanan :</b>
        <table>
            <tr>
                <td>{{ $ticket['quantity'] }} Ticket</td>
                <td style="padding: 0 20px"></td>
                <td><b>{{ implode(',', $ticket['rows_of_seats']) }}</b></td>
            </tr>

              <tr>
                <td>Harga Tiket</td>
                <td style="padding: 0 20px"></td>
                <td><b>Rp. {{ number_format($ticket['schedule']['price'],0,',', '.') }} <span class="text-secondary">x{{ $ticket['quantity'] }}</span></b></td>
            </tr>

              <tr>
                <td>Biaya Layanan</td>
                <td style="padding: 0 20px"></td>
                <td><b>Rp. 4.000 <span class="text-secondary">x{{ $ticket['quantity'] }}</span></b></td>
            </tr>
        </table>
        <b>Gunakan Promo : </b>
         <select id="promo_id" class="form-select">
            @foreach ($promos as $promo )
                <option value="{{ $promo['id'] }}">{{ $promo['promo_code'] }} - {{ $promo['type'] == 'percent' ? $promo['discount'] . 
                '%' : 'Rp.' . number_format($promo['discount'],0,',','.') }}</option>
            @endforeach
         </select>
    </div>
</div>
@endsection
