@extends('templates.app')

@section('content')
    <div class="container card my-5 p-4">
        <div class="card-body">
            <h5 class="text-center">Selesaikan Pembayaran</h5>
            <img src="{{ asset('storage/' . $ticket['ticketPayment']['qrcode']) }}" alt=""
                class="d-block mx-auto mb-4">
            <div class="w-25 d-block mb-4">
                <table class="w-100">
                    <tr>
                        <td>2 Tiket</td>
                        <td><b>{{ implode(', ', $ticket['rows_of_seats']) }}</b></td>
                    </tr>

                    <tr>
                        <td>Kursi Reguler</td>
                        <td>
                            <b>
                                Rp. {{ number_format($ticket['schedule']['price']) }}
                                <span class="text-secondary">{{ $ticket['quantity'] }}</span>
                            </b>
                        </td>
                    </tr>

                    <tr>
                        <td>Biaya Layanan</td>
                        <td>
                            <b>
                                Rp. 4.000
                                <span class="text-secondary">{{ $ticket['quantity'] }}</span>
                            </b>
                        </td>
                    </tr>

                    <tr>
                        <td>Promo</td>
                        @php
                            if ($ticket['promo']) {
                                $promo =
                                    $ticket['promo']['type'] == 'percent'
                                        ? $ticket['promo']['discount'] . '%'
                                        : 'Rp. ' . number_format($ticket['promo']['discount']);
                            } else {
                                $promo = 'Rp. 0';
                            }
                        @endphp
                        <td><b>{{ $promo }}</b></td>
                    </tr>
                </table>
            </div>

            <hr>

            @php
                $price = $ticket['total_price'] + $ticket['service_fee'];
            @endphp

            <div class="d-flex justify-content-between">
                <div>Total Pembayaran</div>
                <div><b>Rp. {{ number_format($price) }}</b></div>
            </div>
        </div>

        <form action="{{ route('tickets.payment.update', ['ticketId' => $ticket['id']]) }}" method="POST">
            @csrf
            @method('PATCH')
            <button class="btn btn-primary btn-block btn-lg" type="submit">Sudah dibayar</button>
        </form>
    </div>
@endsection
