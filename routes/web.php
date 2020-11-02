<?php

    use App\Http\Controllers\Availability;
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
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [Availability::class, 'index'])->name('dashboard');

Route::middleware(['auth:sanctum'])->get('/availability', [Availability::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/availability/store', [Availability::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/availability/destroy', [Availability::class, 'destroy']);
