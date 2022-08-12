<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimplifyController;
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

Route::get('/',[SimplifyController::class,'orderinfo']); 
Route::get('/new-order',[SimplifyController::class,'orderinfo']); 
Route::post('/new-order',[SimplifyController::class,'orderinfo']); 
Route:: get('/get-jusisdiction', [SimplifyController::class, 'get_jusisdiction']);
Route::post("/case-participants", [SimplifyController::class, 'case_participants']);
Route::get("/retrieve-package/{id}", [SimplifyController::class, 'case_participants']);
Route::post("/add-document", [SimplifyController::class, 'add_document']);
Route::get("/add-document", [SimplifyController::class, 'add_document']);