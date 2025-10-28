<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PromoExport;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promo.index', compact('promos'));
    }

    public function datatables()
    {
        //jika data yang diambil tdk ada relasi gnakan query jila ada pake with []
        $promos = Promo::query();
        //of mengambil data dari eloquent model yang akan diproses datanya
        return DataTables::of($promos)
        ->addIndexColumn() 
        ->addColumn('type', function($promo){
            if($promo['type'] == 'rupiah'){
                return '<small class="badge badge-primary">Rp' . number_format($promo['discount'], 0, ',','.') . '</small>';
            } else{
                return '<small class="badge badge-primary">' . $promo['discount'] . ' %</small>';
            }
        
        })
        ->addColumn('btnActions', function($promo){
            $btnEdit = '<a href="'. route('staff.promos.edit', $promo['id']) .'" class="btn btn-secondary">Edit</a>';
            $btnDelete = ' <form action="' . route('staff.promos.delete', $promo['id']) .'" method="POST">'.
                        csrf_field() . 
                        method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>';

            return '<div class="d-flex gap-2">'. $btnEdit . $btnDelete . '</div'; 
        })
        //daftarkan nama dari addColumn untuk di panggil di js datatablesnya
        ->rawColumns(['btnActions','type'])
        //ubah query jadi json agar bisa dibaca js
        ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $rules = [
        'promo_code' => 'required',
        'type'       => 'required|in:percent,rupiah',
    ];

    if ($request->input('type') === 'percent') {
        $rules['discount'] = 'required|numeric|min:0|max:100';
    } elseif ($request->input('type') === 'rupiah') {
        $rules['discount'] = 'required|numeric|min:1000';
    }

    $messages = [
        'promo_code.required' => 'Kode Promo Harus Diisi',
        'type.required'       => 'Tipe Promo Harus Diisi',
        'discount.required'   => 'Diskon Harus Diisi',
        'discount.max'        => 'Diskon persen tidak boleh lebih dari 100',
        'discount.min'        => 'Diskon rupiah minimal Rp1.000',
    ];

    $validated = $request->validate($rules, $messages);

    Promo::create([
        'promo_code' => $validated['promo_code'],
        'type'       => $validated['type'],
        'discount'   => $validated['discount'],
        'activated'  => 1,
    ]);

    return redirect()
        ->route('staff.promos.index')
        ->with('success', 'Berhasil menambah promo');


        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'type' => $request->input('type'),
            'discount' => $request->discount,
            'activated' => 1
        ]);
        if ($createData) {
            return redirect()->route('staff.promos.index')->with('succes', 'Berhasil menambah promo');
        } else {
            return redirect()->back()->with('error', 'Login gagal silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promos = Promo::find($id);
        return view('staff.promo.edit', compact('promos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required',
            'discount' => 'required'
        ], [
            'promo_code.required' => 'Kode Promo Harus Diisi',
            'type.required' => 'Tipe Promo Harus Diisi',
            'discount.required' => 'Diskon Harus Diisi'
        ]);
        $updateData = Promo::where('id', $id)->update([
            'promo_code' => $request->promo_code,
            'type' => $request->input('type'),
            'discount' => $request->discount,
            'activated' => 1
        ]);
        if ($updateData) {
            return redirect()->route('staff.promos.index')->with('succes', 'Berhasil Mengubah romo');
        } else {
            return redirect()->back()->with('error', 'Login gagal silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Promo::where('id', $id)->delete();
        return redirect()->route('staff.promos.index')->with('succes', 'Berhasil Menghapus Data Promo');
    }

      public function trash()
        {
            $promoTrash = Promo::onlyTrashed()->get();
            return view('staff.promo.trash', compact('promoTrash'));
        }

        public function restore($id)
    {
        $promos = Promo::onlyTrashed()->find($id);
        $promos->restore();
        return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengenbalikan data');
    }

    public function deletePermanent($id)
    {
        $promos = Promo::onlyTrashed()->find($id);
        $promos->forceDelete();
        return redirect()->route('staff.promos.trash')->with('success', 'Berhasil menghapus data secara permanen');
    }

     /**
     * Export data to Excel.
     */


    public function exportExcel()
    {
        $fileName = 'data-promo.xlsx';
        return Excel::download(new PromoExport, $fileName);
    }
}