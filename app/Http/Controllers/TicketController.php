<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Promo;
use App\Models\Schedule;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showSeats($scheduleId, $hourId)
    {
        $schedule = Schedule::find($scheduleId);
        $hour = $schedule['hours'][$hourId] ?? '';


        $soldSeats = Ticket::where('schedule_id', $scheduleId)->where('activated', 1)
        ->where('date', now()->format('Y-m-d'))->pluck('rows_of_seats');

        $soldSeatsFormat = [];
        foreach($soldSeats as $seat){
            foreach($seat as $item){
                array_push($soldSeatsFormat, $item);
            }
        }
        //pluck ambil datanya hanya 1 field/column kemudian di satukan di array
        // dd($soldSeatsFormat);
        return view('schedule.row-seats', compact('schedule', 'hour', 'soldSeatsFormat'));
    }

    public function index()
    {
        //
        $userId = Auth::user()->id;
        //ambil data tiket berdasarkan siapa yanglogin
        $ticketActive = Ticket::where('user_id', $userId)->where('activated', 1)->where('date', now()->format('Y-m-d'))->get();
        //ambil data tiket berdasarkan data siapa yang login dann non login dan sudah kadaluarsa
        $ticketNonActive =  Ticket::where('user_id', $userId)->where('date', '<>',now()->format('Y-m-d'))->get();
        return view('ticket.index', compact('ticketActive', 'ticketNonActive'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'date' => 'required',
            'hour' => 'required',
            'rows_of_seats' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'service_fee' => 'required',
        ]);

        $createData = Ticket::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'hour' => $request->hour,
            'rows_of_seats' => $request->rows_of_seats,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'service_fee' => $request->service_fee,
            'activated' => 0,
        ]);
        return response()->json([
            'message'  => 'Berhasil Membuat data Tiket',
            'data' => $createData
        ]);
    }

    public function ticketOrderPage($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie'])->first();
        $promos = Promo::where('activated', 1)->get();
          return view('schedule.order', compact('ticket', 'promos'));
    }

    public function createBarcode(Request $request)
    {
        $kodeBarcode = 'TICKET' . $request->ticket_id;
        $qrImage = QrCode::format('svg')
        ->size(300)
        ->margin(2)
        ->errorCorrection('H')
        ->generate($kodeBarcode);

        $filename = $kodeBarcode . '.svg';
        $path = 'barcodes/' . $filename;

        Storage::disk('public')->put($path, $qrImage);

        $createData = Payment::create([
            'ticket_id' => $request->ticket_id,
            'qrcode' => $path,
            'status' => 'process',
            'booked_date'=> now()
        ]);

        $ticket = Ticket::find($request->ticket_id);
        $totalPrice = $ticket->total_price;

        if($request->promo_id != NULL){
            $promo = Promo::find($request->promo_id);
            if($promo['type'] == 'percent'){
                $discount = $ticket['total_price'] * ($promo['discount'] / 100);
            } else{
                $discount = $promo['discount'];
            }
            $totalPrice = $ticket['total_price'] - $discount;
        }
        $updateTicket = Ticket::where('id', $request->ticket_id)->update([
            'promo_id' => $request->promo_id,
            'total_price' => $totalPrice
        ]);

        return response()->json([
            'message' => 'berhasil membuat barcode',
            'data'=> $createData
        ]);
    }

        public function ticketPaymentPage($ticketId)
        {
            $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'promo', 'ticketPayment'])->first();
            return view('schedule.payment', compact('ticket'));
        }

        public function updateStatusTicket($ticketId)
        {
            $updatePayment = Payment::where('ticket_id', $ticketId)->update([
                'paid_date' => now()]);
            $updateTicket = Ticket::where('id', $ticketId)->update([
                'activated' => 1
            ]);

            //diarahkan ke halaman route web php
            return redirect()->route('tickets.show', $ticketId);
        }

    /**
     * Display the specified resource.
     */
    public function show($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with('schedule', 'schedule.movie', 'schedule.cinema', 'ticketPayment')->first();
        return view('schedule.ticket', compact('ticket'));
    }

    public function exportPdf($ticketId)
            //siapkan data yang aka di tampilkan di pdf hasinya harusnbetuk array toArray()  
    {
        $ticket = Ticket::where('id', $ticketId)->with('schedule', 'schedule.movie', 'schedule.cinema', 'ticketPayment')->first()->toArray();
        //load view yang akan di jadikan pdf
        view()->share('ticket', $ticket);
        $pdf = Pdf::loadView('schedule.export-pdf', $ticket);
        $fileName = 'TICKET' . $ticketId . '.pdf';
        //download pdf dengan nama file tertentu
        return $pdf->download('ticket-'.$ticketId.'.pdf');
    }


    public function dataChart()
    {
        // ambiil data bulan saat ini
        $month = now()->format('m');
        $tickets = Ticket::where('activated', 1)->whereHas('ticketPayment', function($q) use ($month){
            $q->whereMonth('booked_date', $month);
            
        })->get()->groupBy(function($ticket) {
            return Carbon::parse($ticket->ticketPayment->booked_date)->format('Y-m-d');
        })->toArray();
        // dd($tickets);
        $labels = array_keys($tickets);
        //siapkan wadah untuk array yang akan berisi angka angka jumlah data di tanggal tersebut
        $data = [];
        foreach ($tickets as $ticketGroup){
            array_push($data, count($ticketGroup));
        }
        //
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
