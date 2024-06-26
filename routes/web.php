<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

Route::get('/', [ProductController::class, 'index']);
Route::post('/products/createorupdate', [ProductController::class, 'createOrUpdate']);
Route::get('/getdata', [ProductController::class, 'getData']);
Route::post('/getdataformodal', [ProductController::class, 'getDataForModal']);
Route::post('/delete', [ProductController::class, 'destroy']);

Route::get('get-all-session', function () {
    $session = session()->all();
    return $session;
});

Route::get('set-session', function (Request $request) {
    $request->session()->put('user_name', 'Aditya');
    $request->session()->put('user_id', '111');
    return redirect('get-all-session');
});

Route::get('destroy-session', function (Request $request) {
    // $request->session()->forget('user_name');
    // $request->session()->forget('user_id');
    $request->session()->flush();
    return redirect('get-all-session');
});