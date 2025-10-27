<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    public function home()
    {
        //where untuk mencari data format yang digunakan where('kolom', 'operator', 'nilai')
        //get ambil semua dat ahasil filter
        //first mengambil satu data pertama kali dari hasil filter
        //paginate a\membagi data per halaman
        //orderBy mengurutkan data berdasarkan kolom tertentu
        //limit membatasi jumlah data yang diambil
        //type asc mrk untuk ascending (kecil ke besar), desc untuk descending (besar ke kecil)



        $movies = Movie::where('activated', 1)->orderBy('created_at', 'desc')->limit(4)->get();
        return view('home', compact('movies'));
    }

    public function homeMovie(Request $request)
    {
        $nameMovie = $request->search_movie;

        if($nameMovie !=""){

            $movies = Movie::where('title', 'LIKE', '%' . $nameMovie . '%')->where('activated', 1)->orderBy('created_at', 'DESC')->get();
        }else{
            $movies = Movie::where('activated',1)->orderBy('created_at','DESC')->get();
        }
        return view('movies', compact('movies'));
    }
    public function movieSchedule($movie_id, Request $request)
    {
        $sortirHarga = $request->sortirHarga;

        if ($sortirHarga) {
            $movie = Movie::where('id', $movie_id)->with(['schedules' => function($q) use ($sortirHarga){
                $q->orderBy('price', $sortirHarga);
            }, 'schedules.cinema'])->first();
        } else {
            $movie = Movie::where('id', $movie_id)->with('schedules', 'schedules.cinema')->first();
        }
        return view('schedule.detail-film', compact('movie'));
    }

    public function nonaktif($id)
    {
        Movie::where('id', $id)->update([
            'activated' => 0
        ]);
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil menonaktifkan film');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd() : pengecekan data
        // $request->all() : mengambil semua data dari request
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            // mimes : jenis file yang diizinkan
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul wajib diisi',
            'duration.required' => 'Durasi wajib diisi',
            'genre.required' => 'Genre wajib diisi',
            'director.required' => 'Sutradara wajib diisi',
            'age_rating.required' => 'Rating usia wajib diisi',
            'poster.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau svg',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter'
        ]);
        // ambil file dari input
        $poster = $request->file('poster');
        // buat nama file yang akan di simpan di folder public/storage
        // nama yang dibuat baru dan unik untuk menghhidari duplikasi nama file : <acak>-poster, jpg-contoh nama baru nya
        // getClientOriginalExtenxion() : mengambil ekstensi asli dari file yang diupload
        $namafile = rand(1,10) . '-poster.' . $poster->getClientOriginalExtension();
        // simpan file ke folder public/storage
        // storeAs : menyimpan file dengan nama tertentu
        // visibility('public/private') : disesuaikan boleh di tampilkan atau tidak
        $path = $poster->storeAs("poster", $namafile, "public");

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // yang disimpan di db bukan filenya, hanya lokasi file di storeAs() -> $path
            'poster' => $path,
            'description' => $request->description,
            'activated' => 1
        ]);
        if($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil membuat data!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movies = Movie::find($id);
        return view('admin.movie.edit', compact('movies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            // mimes : jenis file yang diizinkan
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul wajib diisi',
            'duration.required' => 'Durasi wajib diisi',
            'genre.required' => 'Genre wajib diisi',
            'director.required' => 'Sutradara wajib diisi',
            'age_rating.required' => 'Rating usia wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 10 karakter'
        ]);
        // ambil data sebelumnya
        $movies = Movie::find($id);
        // jika ada file yang baru
        if($request->file('poster')) {
            // ambil lokasi poster lama : storage_path()
            $posterSebelumnya = storage_path('app/public/' . $movies->poster);
            // cek jika file ada di folder storage : file_exists()
            if(file_exists($posterSebelumnya)) {
                // hapus file lama : unlink()
                unlink($posterSebelumnya);
            }

            $poster = $request->file('poster');
            $namafile = rand(1,10) . '-poster.' . $poster->getClientOriginalExtension();
            $path = $poster->storeAs("poster", $namafile, "public");

        }

        $createData = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // ?? ternary : (if, jika ada ambil) ?? (else, jika ga ada gunakan yang disini)
            'poster' => $path ?? $movies['poster'],
            'description' => $request->description,
            'activated' => 1
        ]);
        if($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil membuat data!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $schedule = Schedule::where('movie_id', $id)->count();
        if ($schedule)
            return redirect()->route('admin.movies.index')->with('error', 'Tidak dapat menghapus data film data tertaut dengan jadwal tayang');
        
           Movie::where('id', $id)->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Data Berhasil di Hapus');
    }
        public function trash()
        {
            $movieTrash = Movie::onlyTrashed()->get();
            return view('admin.movie.trash', compact('movieTrash'));
        }

        public function restore($id)
    {
        $movies = Movie::onlyTrashed()->find($id);
        $movies->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengenbalikan data');
    }

    public function deletePermanent($id)
    {
        $movies = Movie::onlyTrashed()->find($id);
        // jika ada file yang baru
        if($movies) {
            // ambil lokasi poster lama : storage_path()
            $posterSebelumnya = storage_path('app/public/' . $movies->poster);
            // cek jika file ada di folder storage : file_exists()
            if(file_exists($posterSebelumnya)) {
                // hapus file lama : unlink()
                unlink($posterSebelumnya);
            }
        }
        $movies->forceDelete();
        return redirect()->back()->with('success', 'berhasil menghapus data secara permanen');
    }

        public function exportExcel()
        {
        $fileName = 'date-film.xlsx';
        //proses didonod
        return Excel::download(new MovieExport, $fileName);
    }
}
