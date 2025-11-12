<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPayment extends Model
{
     use SoftDeletes;
     protected $fillable = ['ticket_id', 'status','qrcode','booked_date', 'paid_date'];

     public function ticket(){
        return $this->belongsTo(Ticket::class);
     }
}

