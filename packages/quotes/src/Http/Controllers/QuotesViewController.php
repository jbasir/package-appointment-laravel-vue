<?php

namespace Vendor\Quotes\Http\Controllers;

use Illuminate\Routing\Controller;

class QuotesViewController extends Controller
{
    /**
     * Show the quotes UI.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('quotes::index');
    }
} 