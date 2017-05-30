<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use ideas\Articulo;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('home');
    }

    public function barcode(Request $request)
    {
//        $articulo = Articulo::all();

        $query3 = trim($request->get('searchText'));
        $articulo = DB::table('articulo as art')
            ->where('nombre','LIKE','%'.$query3.'%')
            ->orderBy('art.idarticulo','desc')
//                ->orderBy('p.idprecio','desc')
            ->paginate('30');

        return view('barcode',['articulo'=>$articulo, 'searchText'=>$query3]);
    }
}
