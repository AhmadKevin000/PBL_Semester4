<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaporPdfController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pre-school', function () {
    return view('pages.pre-school');
});

Route::get('/rapor/{siswa}/{semester}/pdf', [RaporPdfController::class, 'download'])->name('rapor.pdf');
