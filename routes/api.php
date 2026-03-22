<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;

// Route này sẽ có tiền tố /api mặc định
Route::get('/invoices/{mahd}', [InvoiceController::class, 'show']);