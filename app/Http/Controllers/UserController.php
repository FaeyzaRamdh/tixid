<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //Request $request : mengambil value request/input
        //dd() debuging function
        // dd($request->all());

        // validasi
        $request->validate([
            // name_input ->"validasi"
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            // email:dns memastikan email valid
            'email' => 'required|email|min:3',
            'password' => 'required'
        ], [
            // custom pesan
            // format : 'name_input.validasi' +> 'pesan error'
            'first_name.required' => 'Nama depan wajib di isi',
            'first_name.min' => 'Nama depan diisi minimal 3 karakter',
            'last_name.required' => 'Nama belakang wajib di isi',
            'last_name.min' => 'Nama belakang wajib di isi minimal 3 karakter',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email diisi dengan data valid',
            'password.required' => 'Password wajib di isi'
        ]);

        // elquent (fungsi model) tambah data baru : create ([])
        $createData = User::create([
        // 'column' => $request-> name_input
        'name' => $request->first_name . " " . $request->last_name,
        'email' => $request->email,
        // enkripsi data : merubah menjadi karakter acak, tidak ada yang bisa tau isi datanya : hash::make()
        'password' => hash::make($request->password),
        // role diisi langsung sebagai user agar tidak bisa menajdi admin atau staff
        'role' => 'user'
    ]);

        if ($createData) {
            // redirect : mengirim ke halaman lain
            // route('name_route', [data])
            // with('key', 'value') : mengirim session
            return redirect()->route('login')->with('success', 'Register berhasil, silahkan login');
        } else {
            return redirect()->back()->with('error', 'Register gagal, silahkan coba lagi');
        }
    }

    public function loginAuth(Request $request)
    {
        // validasi
        $request->validate([
            // name_input ->"validasi"
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib di isi',
            'password.required' => 'Password wajib di isi'
        ]);

        // menyimpan data yang akan di verivikasi
        $data = $request->only(['email', 'password']);
        // Auth::attempt($data) : mengecek data di tabel users
        if (Auth::attempt($data)) {
            // setelah berhasil login, dicek lagi terkait role nya untuk menentukan perpindahan halaman
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login!');
            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Berhasil Login!');
            } else {
                return redirect()->route('home')->with ('success', 'Berhasil Login!');
            }
        } else {
            return redirect()->back()->with('error', 'Login gagal, Pastikan email dan password benar');
        }
    }
    public function logout()
    {
        // auth::logout() : menghapus session login
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Logout berhasil, silahkan login kembali untuk mengakses lebih lengkap');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin','staff'])->get();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
        
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'email' => 'required',
            'password' => 'required'
        ], [
            'name.required' => 'Nama Petugas/Staff harus di isi',
            'email.required' => 'Email Petugas harus di isi',
            'password.required' => 'Password harus di isi',
        ]);
        $create = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff'
        ]);
        if($create) {
            return redirect()->route('admin.users.index')->with('success', 'Data Berhasil di Simpan');
        } else {
            return redirect()->back()->with('error', 'Data Gagal di Simpan');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=> 'required',
            'email' => 'required'
        ], [
            'name.required' => 'Nama Petugas/Staff harus di isi',
            'email.required' => 'Email Petugas harus di isi',
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $update = $user->save();
        if($update) {
            return redirect()->route('admin.users.index')->with('success', 'Data Berhasil di Update');
        } else {
            return redirect()->back()->with('error', 'Data Gagal di Update');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'Data Berhasil di Hapus');
    }

    public function trash()
    {
        $userTrash = User::whereIn('role', ['admin','staff'])->onlyTrashed()->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
            $user->forceDelete();
            return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen');
        }

    public function exportExcel()
    {
        $fileName = 'data-user.xlsx';

        return Excel::download(new UserExport, $fileName);
    }
}