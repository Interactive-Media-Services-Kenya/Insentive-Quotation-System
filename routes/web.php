<?php

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

Route::get('/', function () {
    if (!\Auth::check()) {
       return view('auth.login');
    }else{
        return redirect()->route('home');
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('order', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
Route::post('orders/store', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
