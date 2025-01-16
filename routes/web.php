<?php

use App\Events\getLiveData;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', function () {
//     event(new (getLiveData));
//     return "done";
// });

Route::get('/testing', [TestController::class,'index']);
