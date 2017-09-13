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
            //dd($request);
            $articulos = DB::table('articulo as art')->get();
            $cantidad = DB::table('articulo as art')->count();
            return view('compras.stockminimo.index',compact('articulos','cantidad'));
        }
    }

    public function create()
    {
        $articulos = DB::table('articulo as art')->get();
        $cantidad = DB::table('articulo as art')->count();
        return view('compras.stockminimo.create',compact('articulos','cantidad'));
    }

    public function store()
    {

    }

    public function show()
    {
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }



}
