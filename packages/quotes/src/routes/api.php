<?php

use Illuminate\Support\Facades\Route;
use Vendor\Quotes\Http\Controllers\QuotesApiController;

Route::prefix('api/quotes')->group(function () {
    Route::get('/', [QuotesApiController::class, 'getAllQuotes']);
    Route::get('/random', [QuotesApiController::class, 'getRandomQuote']);
    Route::get('/{id}', [QuotesApiController::class, 'getQuote'])->where('id', '[0-9]+');
}); 