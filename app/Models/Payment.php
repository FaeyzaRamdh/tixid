<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments'; // tabel di database

    protected $fillable = [
        'ticket_id',
        'status',
        'qrcode',
        'booked_date',
        'paid_date'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
