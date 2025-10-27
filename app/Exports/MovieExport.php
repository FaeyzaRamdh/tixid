<?php

namespace App\Exports;

use App\Models\Movie;
use Carbon\CarbonTimeZone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
//memanipulasi datetime
use Carbon\Carbon;

class MovieExport implements FromCollection, WithHeadings, WithMapping
{
    //membuat properti / variabel untuk no urutan data
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Movie::all();
    }

    //menentukan header data
    public function headings():array
    {
        return ['No', 'Judul Film', 'Durasi', 'Genre', 'Sutradara', 'Usia Minimal', 'Poster', 'Sinopsis'];
    }

    public function map($movie): array
    {
        return[
            //menambahkan sebanyak 1 data dari $key = 0
            ++$this->key,
            $movie->title,
            //format pengennya 1 jam 30 menit, data asal : 01:00:00
            //parse() mengambil data tanggal/jam
            //format() ,ememtukan format tanggal jam
            Carbon::parse($movie->duration)->format("H") . " Jam " . Carbon::parse
            ($movie->duration)->format('i') . " Menit ",
            $movie->genre,
            $movie->director,
            //fprmat  : usia+ -> 17
            $movie->age_rating . "+",
            //asset link buat liat gambar
            asset('storage/') . "/" . $movie->poster,
            $movie->description
        ];
    }
}
