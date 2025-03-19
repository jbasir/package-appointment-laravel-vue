<?php

use Illuminate\Support\Facades\Route;
use Vendor\Quotes\Http\Controllers\QuotesViewController;

Route::get('quotes-ui', [QuotesViewController::class, 'index']); 