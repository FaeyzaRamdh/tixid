<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MovieController::class,'home'])->name('home');
Route::get('/schedules/{movie_id}', [MovieController::class, 'movieSchedule'])->name('schedules.detail');
Route::get('/movies/active', [MovieController::class,'homeMovie'])->name('home.movies.active');
Route::get('/schedules/{scheduleId}/hours/{hourId}/ticket', [TicketController::class, 'showSeats'] )->name 
('schedules.show.seats');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

//httpmethod
// 1.get : menampilkan halaman
// 2.post : mengirim data ke server
// 3.put : mengubah data di server
// 4.delete : menghapus data di server

Route::post('/signup', [UserController::class, 'register'])->name('signup.register');
Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// halaman khusus admin
// middleware() : memanggil middleware yang akan digunakan
// group () : mengelompokan route agar mengikuti sifat sebelumnya (sebelumnya = middlleware)
// prefix awalan path. agar /admin ditulis sekali dan bias digunakan berkali kali
// name : agar di name hanya menulis sekali dan bisa digunakan berkali kali
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function() {
    // admin dashboard disimpan di group middleware afar dapat menggunakan middleware tersebut
    Route::get('/dashboard',function() {
        return view('admin.dashboard');
    })->name('dashboard');

    //data cinema
    Route::prefix('cinemas')->name('cinemas.')->group(function() {
        // ambil banyak data : index
        Route::get('/datatables', [CinemaController::class,'dataTables'])->name('datatables');
        Route::get('/', [CinemaController::class, 'index'])->name('index');
        // resource create (function create controller) untuk memunculkan form tambah
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        // (id) -> paarameter place holder mengirim data ke controller. digunakan ketika akan mengambil data spesifik
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::get('/trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [CinemaController::class,'exportExcel'])->name('export');
    });

    Route::prefix('users')->name('users.')->group(function() {
         Route::get('/datatables', [UserController::class,'dataTables'])->name('datatables');
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
         Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [UserController::class, 'exportExcel'])->name('export');
    });


    //isi data film
    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/datatables', [MovieController::class,'dataTables'])->name('datatables');
        Route::get('/', [MovieController::class,'index'])->name('index');
        Route::get('/create', [MovieController::class,'create'])->name('create');
        Route::post('/store', [MovieController::class,'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class,'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class,'update'])->name('update');
        Route::delete('/delete/{id}', [MovieController::class,'destroy'])->name('delete');
        Route::patch('/nonaktif/{id}', [MovieController::class,'nonaktif'])->name('nonaktif');
        Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [MovieController::class,'exportExcel'])->name('export');
        
    });
});


Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function () {

    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');
    //promo
    Route::prefix('/promos')->name('promos.')->group(function (){
        Route::get('/datatables', [PromoController::class,'dataTables'])->name('datatables');
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('delete');
        Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [PromoController::class, 'exportExcel'])->name('export');
    });
    //jadwal tayagan film 
     Route::prefix('/schedules')->name('schedules.')->group(function() {
            Route::get('/datatables', [ScheduleController::class,'dataTables'])->name('datatables');
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            Route::post('/store', [ScheduleController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
            Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
            Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
            Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');
            Route::get('/export', [ScheduleController::class, 'exportExcel'])->name('export');
    });
});