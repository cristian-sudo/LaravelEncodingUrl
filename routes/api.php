<?php

use App\Http\Controllers\UrlShortenerController;
use Illuminate\Support\Facades\Route;

Route::post('/encode', [UrlShortenerController::class, 'encode'])->name('encode');
Route::post('/decode', [UrlShortenerController::class, 'decode'])->name('decode');
