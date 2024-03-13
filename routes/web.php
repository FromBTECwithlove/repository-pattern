<?php

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

Route::get('/', function () {
    return redirect()->route('home.index');
});

Route::resource('home', \App\Http\Controllers\HomeController::class);
Route::delete('delete-multiple', [\App\Http\Controllers\HomeController::class, 'delMultiple'])
    ->name('delete-multiple');