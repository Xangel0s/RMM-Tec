<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/portal', function () {
    return view('portal.landing');
});

Route::get('/portal/support', function () {
    return view('portal.minimal');
})->name('portal.support');
