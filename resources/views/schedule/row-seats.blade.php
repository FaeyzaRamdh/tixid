@extends('templates.app')
@section('content')
    <div class="container card my-5 p-4" style="margin-bottom: 20% !important;">
        <div class="card-body">
            <b>{{ $schedule['cinema']['name'] }}</b>
            {{-- now() untuk mengambil tanggal sekarang, Format F nama bulan --}}
            <br><b>{{ now()->format('d F Y') }} - {{ $hour }}</b>
            <div class="alert alert-secondary">
                <i class="fa-solid fa-info text-danger"></i> Anak berusia 2 tahun harus membeli tiket.
            </div>
            <div class="w-50 d-block mx-auto my-4">
                <div class="row">
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; background: blue; margin-right: 5px;">
                        </div>Kursi Dipilih.
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; background: #112646; margin-right: 5px;">
                        </div>Kursi Tersedia.
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; background: #eaeaea; margin-right: 5px;">
                        </div>Kursi Terjual.
                    </div>
                </div>
            </div>
            @php
                $rows = range('A', 'H');
                $cols = range(1, 18);
            @endphp
            @foreach ($rows as $row)
                <div class="d-flex justify-content-center align-content-center">
                    @foreach ($cols as $col)
                        @if ($col == 7)
                            <div style="width: 50px;"></div>
                    
                        @endif
                        <div
                            style="width: 50px; height: 50px; text-align: center; font-weight: bold; color: white; padding-top: 10px; cursor: pointer; background: #112646; margin: 5px; border-radius: 8px;"onclick="selectSeat('{{ $schedule->price }}','{{ $row }} ',' {{ $col }}', this)">
                            {{ $row }} - {{ $col }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="fixed-bottom">
        <div class="p-4 bg-light text-center w-100"><b>LAYAR BIOSKOP</b></div>
        <div class="row w-100 bg-light">
            <div class="col-6 py-3 text-center" style="border: 1px solid gray">
                <h5>Total Harga</h5>
                <h5 id="totalPrice">Rp. </h5>
            </div>
            <div class="col-6 py-3 text-center" style="border: 1px solid gray">
                <h5>Pilih Kursi</h5>
                <h5 id="seats"></h5>
            </div>
        </div>
        {{-- input hidden nyimpen yang diperlukan js untuk membuat data namun di tampilan di sembunyikan --}}
        <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" id="date" value="{{ now()}}">
        <input type="hidden" id="hour" value="{{ $hour }}">

        <div class="w-100 bg-light p-2 text-center" id="btnOrder"><b>RINGKASAN ORDER
        </b></div>
    </div>
@endsection

@push('script')
 <script>
    let seats = [];
    let totalPrice = 0;

    function selectSeat(price, row, col , element){
        //buat format kursi
        let seat = row + "-" + col;
        //cek apakah seats kursi udah ada di array kursi atau belu,
        let indexSeat = seats.indexOf(seat);
        //jika ada item akan berisi 0 / 1 dan selanjutnya
        if (indexSeat === -1){
        //kalo kursi blm ada di array
        seats.push(seat); //push nambahin array
        element.style.background = "blue"; //ubah warna kursi jadi biru
    } else{
        //alo lursi udh ada
        seats.splice(indexSeat, 1);
        element.style.background = "#112646"; //kembalikan warna kursi semula
    }
    //hitung total harga
    totalPrice = price * seats.length;
    let totalPriceElement = document.querySelector("#totalPrice");
    totalPriceElement.innerText = totalPrice;

    let seatsElement = document.querySelector("#seats");
    //join (','): mengubah array jadi string 
    seatsElement.innerText = seats.join(',');

    let btnOrder = document.querySelector('#btnOrder');
    if (seats.length> 0 ) {
        btnOrder.classList.remove('bg-light');
        btnOrder.style.background = '#112646';
        btnOrder.style.color = 'white';
        btnOrder.style.cursor = 'pointer';
        //kalo di klik lakukan proses pembuatan tiket
        btnOrder.onclick = createTicket;

    }else{
        btnOrder.classList.add('bg-light');
        btnOrder.style.background = '';
        btnOrder.style.color = '';
        btnOrder.style.cursor = '';
        btnOrder.onclick = null;
    }
 }

  function createTicket(){
    //menampilkan ajax mengakses data di data base (be) lewat js digunakan ($)

    $.ajax({
        url: "{{ route('tickets.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            user_id: $("#user_id").val(),
            schedule_id: $("#schedule_id").val(),
            date: $("#date").val(),
            hour: $("#hour").val(),
            rows_of_seats: seats,
            quantity: seats.length,
            total_price: totalPrice,
            service_fee: 4000 * seats.length
        },
        success: function(response){
            let ticketId = response.data.id;
            //redirect ke halaman pembayaran
            window.location.href = '/tickets/' + ticketId + '/order';
        },
        error: function(massage){
            alert('gagal membuat tiket');
        }
    });
  }
</script>
@endpush