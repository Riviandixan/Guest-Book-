<?php

use App\Http\Controllers\GuestController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest
Route::get('/', [GuestController::class, 'index'])->name('guest.index');
Route::post('store', [GuestController::class, 'store'])->name('store');

Route::get('/room', [RoomController::class, 'index'])->name('room.index');
Route::post('/room-ajax', [RoomController::class, 'ajax'])->name('room.ajax');
Route::post('/save', [RoomController::class, 'save'])->name('room.save');

