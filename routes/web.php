<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});
Route::get('login', function () {
    return view('user.login');
})->name('login');
Route::post('postlogin', 'LoginController@login')->name('postlogin');
Route::get('logout', 'LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});
Route::group(['middleware' => ['auth', 'ceklevel:admin']], function () {
    // Data User
    Route::prefix('/users')->group(function() {
        Route::get('/user', 'UserController@index')->name('user');
        Route::get('/create_user', 'UserController@create')->name('create_user');
        Route::post('/simpan_user', 'UserController@store')->name('simpan_user');
        Route::get('/edit_user/{id}', 'UserController@edit')->name('edit_user');
        Route::post('/update_user/{id}', 'UserController@update')->name('update_user');
        Route::get('/delete_user/{id}', 'UserController@destroy')->name('delete_user');
        Route::get('/{user}',function ($user)
        {
            $admin = User::where('Level',$user)->get();
            return view('user.admin',compact('admin'));
        })->name('admin');
    }); 

    //Relasi Satuan dan Kategori dengan Data Barang
    Route::get('/create_barang', 'BarangController@create')->name('create_barang');
    Route::get('/kategori', 'BarangController@kategori')->name('kategori');
    Route::post('/kategori/create', 'BarangController@kategoristore')->name('kategorisave');
    Route::post('/satuan/create', 'BarangController@satuanstore')->name('satuansave');
    Route::get('/satuan', 'BarangController@satuan')->name('satuan');
    
    //Data Barang
    Route::get('/barang', 'BarangController@index')->name('barang');
    Route::post('/barang/store', 'BarangController@store')->name('barangsave');
    Route::get('/barang/edit/{id}', 'BarangController@edit')->name('barangedit');
    Route::post('/barang/update/{id}', 'BarangController@update')->name('barangupdate');
    Route::get('/hpsbarang/{id}', 'BarangController@destroy')->name('hapusbarang');

    //Data ruangan
    Route::get('/ruangan', 'RoomController@index')->name('ruangan');
    Route::get('/create_ruangan', 'RoomController@create')->name('create_ruangan');
    Route::post('/ruangan/store', 'RoomController@store')->name('ruangansave');

    //relasi ke Ruangan
    Route::get('/klasifikasi', 'RoomController@klasifikasi')->name('klasifikasi');
    Route::get('/rayon', 'RoomController@rayon')->name('rayon');
    Route::post('/klasifikasi/store', 'RoomController@klasifikasistore')->name('klasifikasisave');
    Route::post('/rayon/store', 'RoomController@rayonstore')->name('rayonsave');
});

