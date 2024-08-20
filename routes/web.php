<?php
namespace App\Http\Middleware;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\DataMahasiswa;
use App\Http\Controllers\DownroleController;
use App\Http\Controllers\UserControlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UproleController;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest'])->group(function () {
    // Rute Anda
    Route::view('/','halaman_depan/index');
    Route::get('/sesi',[AuthController::class, 'index'])->name('auth');
    Route::post('/sesi', [AuthController::class, 'login']);
    Route::get('/reg',[AuthController::class, 'create'])->name('registrasi');
    Route::post('/reg', [AuthController::class, 'register']);
    Route::get('/verify/{verify_key}', [AuthController::class, 'verify']);
});

#userAkses diubah dari Auth
Route::middleware('auth')->group(function () {
    Route::redirect('/home', '/user');
    Route::get('/superadmin', [SuperAdminController::class, 'index'])->name('superadmin')->middleware('checkrole:superadmin');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin')->middleware('checkrole:admin');
    Route::get('/user', [UserController::class, 'index'])->name('user')->middleware('checkrole:user');

    Route::get('/data_pts_aktif', [DataMahasiswa::class, 'index'])->name('data_pts_aktif');
    Route::get('/damatambah', [DataMahasiswa::class, 'tambah']);
    Route::get('/damaedit/{id}', [DataMahasiswa::class,'edit']);
    Route::post('/damahapus/{id}',[DataMahasiswa::class, 'hapus']);

    Route::get('/usercontrol', [UserControlController::class, 'index'])->name('usercontrol');

    Route::post('/logout', [AuthController::class,'logout'])->name('logout');

        // new
        Route::post('/tambahdama', [DataMahasiswa::class, 'create']);
        Route::post('/editdama', [DataMahasiswa::class, 'change']);

        Route::get('/tambahuc', [UserControlController::class, 'tambah']);
        Route::get('/edituc/{id}', [UserControlController::class, 'edit']);
        Route::post('/hapusuc/{id}', [UserControlController::class, 'hapus']);
        Route::post('/tambahuc', [UserControlController::class, 'create']);
        Route::post('/edituc', [UserControlController::class, 'change']);

        Route::post('/uprole/{id}', [UproleController::class, 'index']);
        Route::post('/downrole/{id}', [DownroleController::class, 'index']);
});
