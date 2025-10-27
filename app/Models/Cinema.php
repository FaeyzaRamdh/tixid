<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //mendaftarkan soft deletes
    use SoftDeletes;

    //mendaftarkan detail data (column) agar data2 tsb bisa di isi
    protected $fillable = ['name', 'location',];

    //mendefinisikan relasi ke tabel lain
    public function schedules(){
        //hasMany = one to many
        //hasOne = `one to one
        return $this->hasMany(Schedule::class);
    }


}
