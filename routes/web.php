<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadEstimate;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::get('/admin/pdf/estimate/{estimate}', DownloadEstimate::class)->name('pdf.estimate');