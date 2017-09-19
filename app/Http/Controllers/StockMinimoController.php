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
                ->select('art.idarticulo','art.stock_minimo','art.stock','art.nombre','art.barcode','art.ultimoprecio',DB::raw('SUM(art.stock - art.stock_minimo) AS diferencia'))
                ->whereNotNull('art.stock_minimo')
                ->groupBy('art.idarticulo','art.stock_minimo','art.stock','art.nombre','art.barcode','art.ultimoprecio')
                ->get();
            $articulosMinimosTotal = DB::table('articulo as art')
                ->whereNotNull('art.stock_minimo')
                ->count();
            $diferencia = DB::table('articulo as art')
                ->whereNotNull('art.stock_minimo')
                ->get();
            $cantidad = 0;
            foreach ($diferencia as $a) {
               if($a->stock_minimo <= $a->stock){
                   $cantidad = $cantidad + 1;
               }
            }
            if($cantidad!=0){
                $porcentaje = ($cantidad / $articulosMinimosTotal) * 100;
            }
            else {
                $porcentaje = 0;
            }

     //       dd($articulos);
            return view('compras.stockminimo.index',compact('articulos','porcentaje'));
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
