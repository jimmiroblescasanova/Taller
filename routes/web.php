<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfEstimateController;
use App\Http\Controllers\PdfOrderController;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');

Route::get('/admin/pdf/estimate/{estimate}/stream', PdfEstimateController::class)
    ->name('pdf.estimate.stream');

Route::get('/admin/pdf/order/{order}/stream', PdfOrderController::class)
    ->name('pdf.order.stream');

// TODO: delete this route, just to test the email
Route::get('/mail', function () {
    $estimate = App\Models\Estimate::find(4);

    return new App\Mail\EstimateCreated($estimate);
});