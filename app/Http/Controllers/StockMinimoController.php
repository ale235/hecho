<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use ideas\Articulo;
use DB;

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
            $articulos = DB::table('articulo as art')->get();
            $cantidad = DB::table('articulo as art')->count();
            return view('compras.stockminimo.index',compact('articulos','cantidad'));
        }
    }

}
