<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];
    //array json 
    protected function casts(): array
    {
        return [
            'hours' => 'array'
        ];
    }

    public function cinema(){
        //karna schedule ada diposisi kedua gunakan belongsTo untuk menyambungkan
        return $this->belongsTo(Cinema::class);
    }

    public function movie(){
        return $this->belongsTo(Movie::class);
    }
}
