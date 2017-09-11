<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;

class StockMinimoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request)
        {
            return view('compras.stockminimo.index');
        }
    }

}
