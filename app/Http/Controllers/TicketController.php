<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Promo;
use App\Models\Schedule;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\TicketPayment;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showSeats($scheduleId, $hourId)
    {
        $schedule = Schedule::find($scheduleId);
        $hour = $schedule['hours'][$hourId] ?? '';
        return view('schedule.row-seats', compact('schedule', 'hour'));
    }

    public function index()
    {
        //
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

       $createData = TicketPayment::create([
    'ticket_id' => $request->ticket_id,
    'barcode_path' => $path,
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
        'data'=> [
        'ticket_id' => $request->ticket_id,
        ]
    ]);
    }

        public function ticketPaymentPage($ticketId)
        {
            $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'promo', 'ticketPayment'])->first();
            return view('schedule.payment', compact('ticket'));
        }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
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
