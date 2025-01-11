<?php

use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\FasilitasControllerAdmin;
use Illuminate\Support\Facades\Route;

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

Route::resource('fasilitas/data', FasilitasController::class);

Route::get('/fasilitas', [FasilitasControllerAdmin::class, 'index'])->name('fasilitas.index');
Route::post('/fasilitas/store', [FasilitasControllerAdmin::class, 'store'])->name('fasilitas.store');
Route::put('/fasilitas/{id}', [FasilitasControllerAdmin::class, 'update'])->name('fasilitas.update');
Route::delete('/fasilitas/{id}', [FasilitasControllerAdmin::class, 'destroy'])->name('fasilitas.destroy');

Route::get('/', [FasilitasControllerAdmin::class, 'index'])->name('fasilitas.index');
