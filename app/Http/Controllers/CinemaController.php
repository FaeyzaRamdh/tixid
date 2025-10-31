<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;
use Yajra\DataTables\Facades\DataTables;


class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        // cinema:: all()-> mengambil semua data dari model cinema
        // mengirim data dari controller ke bllade -> compact()
        // isi compact() adalah nama variable yang akan dikirim
        return view('admin.cinema.index', compact('cinemas'));
    }
    
    public function datatables()
    {
        //jika data yang diambil tdk ada relasi gnakan query jila ada pake with []
        $cinemas = Cinema::query();
        //of mengambil data dari eloquent model yang akan diproses datanya
        return DataTables::of($cinemas)
        ->addIndexColumn()
        ->addColumn('btnActions', function($cinema){
            $btnEdit = '<a href="'. route('admin.cinemas.edit', $cinema['id']) .'" class="btn btn-secondary">Edit</a>';
            $btnDelete = ' <form action="' . route('admin.cinemas.delete', $cinema['id']) .'" method="POST">'.
                        csrf_field() . 
                        method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>';

            return '<div class="d-flex gap-2">'. $btnEdit . $btnDelete . '</div'; 
        })
        //daftarkan nama dari addColumn untuk di panggil di js datatablesnya
        ->rawColumns(['btnActions'])
        //ubah query jadi json agar bisa dibaca js
        ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinema.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'location' => 'required|min:10'
        ], [
            'name.required' => 'Nama Bioskop harus di isi',
            'location.required' => 'Lokasi Bioskop harus di isi',
            'location.min' => 'Lokasi bioskop harus di isi minimal 10 karakter',
        ]);
        $create = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if($create) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data Berhasil di Simpan');
        } else {
            return redirect()->back()->with('error', 'Data Gagal di Simpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //edit($id) -> $id diambil dari route {id}
        $cinema = Cinema::find($id);
        // find() : mencari berdasarkan id
        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=> 'required',
            'location' => 'required|min:10'
        ], [
            'name.required' => 'Nama Bioskop harus di isi',
            'location.required' => 'Lokasi Bioskop harus di isi',
            'location.min' => 'Lokasi bioskop harus di isi minimal 10 karakter',
        ]);
        // where('column', value) : mencari data, format, where(nama column, value)
        // webelum update() wajib ada where() untuk mencari data yang akan di updatenya
        $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if($updateData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data Berhasil di Update');
        } else {
            return redirect()->back()->with('error', 'Data Gagal di Update');
        }
        return view('admin.cinema.edit', ['cinema' => $cinema]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedules = Schedule::where('cinema_id', $id)->count();
        if ($schedules)
            return redirect()->route('admin.cinemas.index')->with('error', 'Tidak dapat menghapus data bioskop data tertaut dengan jadwal tayang');
        Cinema::where('id', $id)->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Data Berhasil di Hapus');
    }
    public function trash()
    {
        $cinemaTrash = Cinema::onlyTrashed()->get();
        return view('admin.cinema.trash', compact('cinemaTrash'));
    }

    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengenbalikan data');
    }

    public function deletePermanent($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'berhasil menghapus data secara permanen');
    }
    
    public function exportExcel()
    {
        $fileName = 'data-lokasi-bioskop.xlsx';
        return Excel::download(new CinemaExport, $fileName);
    }


    public function cinemaList()
    {
     
        $cinemas = Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

    public function cinemaSchedules($cinema_id)
    {
            //where has ("nama relasi , function ($q) argumen 1
            //nama relasi wjib dua argumen func 8ntk filter pada relasi optional
            //where has ('nama relasi')-> movie where has (' schedule) mengambil dari data film hanya memiliki relasi 
            // (memiliki data) schedules where has ('nama relasi' funct($q-> where)) active 1 mengambil 
            //data sscheduls yang memiliki relsai (memiliki data) movie dan nilai activated pasa movienya 1.

        $schedules = Schedule::where('cinema_id' , $cinema_id)->with('movie')->whereHas('movie', function($q){
            $q->where('activated', 1);
        })->get();
        return view('schedule.cinema-schedules', compact('schedules'));
    }
}