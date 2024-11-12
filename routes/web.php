<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/VIEW-PROCESS-DATA', [App\Http\Controllers\HomeController::class, 'ProcessData'])->name('VIEW-PROCESS-DATA'); 

Route::get('/GET-PROCESS-DATA', [App\Http\Controllers\HomeController::class, 'GetProcessData'])->name('PROCESS-DATA'); 