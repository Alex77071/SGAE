<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');


Route::get('/inicio', function () {
    return view('inicio.index', [
        'usuario' => 'Carlos'
    ]);
})->name('inicio');