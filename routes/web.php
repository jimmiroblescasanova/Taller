<?php

use App\Http\Controllers\PdfEstimate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::get('/admin/pdf/estimate/{estimate}/stream', PdfEstimate::class)
    ->name('pdf.estimate.stream');

Route::get('/mail', function () {
    $estimate = App\Models\Estimate::find(4);

    return new App\Mail\EstimateCreated($estimate);
});