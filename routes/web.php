<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('payment/verify', function (Request $request) {
    return Http::post('http://localhost:8000/api/v1/payment/verify', [
        'token' => $request->trackId,
        'status' => $request->status
    ]);
});
