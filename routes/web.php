<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

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

Route::get('/', [ContactController::class, 'listAll']);
Route::post('/contact/store', [ContactController::class, 'store']);
Route::get('/contact/update/{id}', [ContactController::class, 'update']);
Route::post('/contact/doupdate', [ContactController::class, 'doupdate']);
Route::post('/contact/delete', [ContactController::class, 'delete']);
Route::post('/contact/listAllJson', [ContactController::class, 'listAllJson']);
Route::post('/contact/getByIdJson', [ContactController::class, 'getByIdJson']);
Route::post('/contact/addPhone', [ContactController::class, 'addPhone']);
Route::post('/contact/deletePhone', [ContactController::class, 'deletePhone']);
Route::post('/contact/defaultPhone', [ContactController::class, 'defaultPhone']);