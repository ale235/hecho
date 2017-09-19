<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use ideas\Articulo;
use DB;

use Illuminate\Support\Facades\Redirect;

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
            $articulos = DB::table('articulo as art')
                ->whereNotNull('art.stock_minimo')
                ->get();
            return view('compras.stockminimo.index',compact('articulos'));
        }
    }

    public function create()
    {
        $articulos = DB::table('articulo as art')->get();
        $cantidad = DB::table('articulo as art')->count();
        return view('compras.stockminimo.create',compact('articulos','cantidad'));
    }

    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $articulo = new Articulo;
            $idarticulo = $request->get('idarticulo');
            $stockMinimo =  $request->get('stockminimo');
            $cont = 0;
            //dd($request);
            while ($cont < count($idarticulo)) {
                //dd($request);
                $articulo = Articulo::findOrFail($idarticulo[$cont]);
                if($stockMinimo[$cont]!= null) {
                    $articulo->stock_minimo = $stockMinimo[$cont];

                }
                else {
                    $articulo->stock_minimo = null;
                }
                $articulo->update();
                $cont = $cont + 1;
            }
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        return Redirect::to('compras/stockminimo');
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
