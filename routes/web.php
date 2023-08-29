<?php

use App\Http\Controllers\CalculatorController;
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

Route::get('/calculator', [CalculatorController::class, 'index']);

// this snippet will redirect visitors from any route to /calculator
// since it's the only existing one.
Route::any('{any}', function () {
    return redirect('/calculator');
})->where('any', '.*');
