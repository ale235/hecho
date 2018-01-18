<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;

use ideas\Articulo;
use ideas\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use ideas\Http\Requests\PersonaFormRequest;
use Carbon\Carbon;
use ideas\DetalleVenta;

use DB;

class ReportesController extends Controller
{
    public function index(Request $request)
    {
        $articulosSinStock = DB::table('articulo as art')
            ->join('categoria as cat','art.idcategoria','=','cat.idcategoria')
            //->select(DB::raw('COUNT(*) as cantidad'))
            ->where('art.stock','<=','0')
            ->where('art.idcategoria','!=','4')
            ->where('art.estado','=','Activo')
            ->count();
        //dd($articulosSinStock);
        //return $collection;

        $articulos =  DB::table('articulo as a')
            ->join('detalle_venta as dv','a.idarticulo','=','dv.idarticulo')
            ->get();

        $venta = DB::table('venta')
            ->select('fecha_hora', DB::raw('sum(total_venta) as total_venta'))
            ->whereBetween('fecha_hora',['2016-02-02 00:00:00','2017-02-05 00:00:00'])
            ->groupBy('fecha_hora')
            ->get();

        return view('reportes.grafico.index', ['articulosSinStock'=> $articulosSinStock,'articulos'=>$articulos, 'venta'=> $venta]);
        //return view('home');
    }

//    public function articulosSinStock()
//    {
//
//        $collection = DB::table('articulo')
//            ->select(DB::raw('COUNT(*) as cantidad'))
//            ->where('stock','<=','0')
//            ->where('estado','=','Activo')
//            ->get();
//
//        return $collection;
//    }

    public function cajaDelDiaReportes()
    {
        $mytime= Carbon::now('America/Argentina/Buenos_Aires');

        //$date=$mytime->toDateTimeString();

        $collection = DB::table('venta')
            ->select(DB::raw('SUM(total_venta_real) as total'))
            ->whereDay('fecha_hora',$mytime->day)
            ->whereMonth('fecha_hora',$mytime->month)
            ->whereYear('fecha_hora',$mytime->year)
            ->get();

        return $collection;
    }

    public function cajaDeAyer()
    {
        $mytime= Carbon::now('America/Argentina/Buenos_Aires')->yesterday();

        //$date=$mytime->toDateTimeString();

        $collection = DB::table('venta')
            ->select(DB::raw('SUM(total_venta_real) as total'))
            ->whereDay('fecha_hora',$mytime->day)
            ->whereMonth('fecha_hora',$mytime->month)
            ->whereYear('fecha_hora',$mytime->year)
            ->get();

        return $collection;
    }

    public function ventasPorProductos()
    {

        $collection = DB::table('detalle_venta as v')
            ->join('articulo as a','a.idarticulo','=','v.idarticulo')
            ->select('a.nombre',DB::raw('SUM(v.cantidad) as cantidadTotal'))
            ->where('a.estado','=','Activo')
            ->groupBy('a.nombre')
            ->orderBy('cantidadTotal','desc')
//            ->orderBy('desc')
            ->limit(10)
            ->get();

        return $collection;
    }

    public function proveedorQueMasProductosVende()
    {

        $collection = DB::table('detalle_venta as v')
            ->join('articulo as a','a.idarticulo','=','v.idarticulo')
            ->join('persona as p','a.proveedor','=','p.codigo')
            ->select('a.proveedor',DB::raw('SUM(v.cantidad) as cantidadTotal'))
            ->where('p.estado','=','Activo')
            ->groupBy('a.proveedor')
            ->orderBy('cantidadTotal','desc')
//            ->orderBy('desc')
            ->limit(10)
            ->get();

        return $collection;
    }

    public function ganancias(){
        $today = Carbon::now('America/Argentina/Buenos_Aires');
        $firstDay = Carbon::now('America/Argentina/Buenos_Aires');
        $firstDay->day = 1;
        $firstDay->hour = 0;
        $firstDay->minute = 0;
        $firstDay->second = 0;
        $firstDay->toDateTimeString();
        $today->toDateTimeString();
        $collection = DB::table('venta as v')
//            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
//            ->join('precio as p','p.idarticulo','=','dv.idarticulo')
//            ->select('v.idventa', 'p.idprecio', 'p.fecha as fechaprecio', 'v.fecha_hora as fechaventa', 'dv.precio_venta','dv.idarticulo','p.precio_compra','dv.cantidad')
            ->select(DB::raw('SUM(v.ganancia) as ganancia'))
            ->whereBetween('v.fecha_hora', array($firstDay, $today))
//            ->orderBy('v.idventa','desc')
//            ->orderBy('dv.idarticulo','desc')
            ->get();

//        Excel::create('Laravel Excel', function ($excel) use ($columna) {
//
//            $excel->sheet('Excel sheet', function ($sheet) use ($columna) {
//
//                $sheet->row(1, ['Fecha', 'Cliente', 'Total']);
//                $sheet->fromArray($columna, null, 'A1', false, false);
//
//            });
//
//        })->download('xls');



        return response()->json($collection);
        //return $collection
    }

    public function show(Request $request)
    {

/*        if ($request->get('daterange') == null || $request->get('daterange') == '') {
            $venta = DB::table('venta')
                ->orderBy('fecha_hora','desc')
//            ->select('fecha_hora', DB::raw('sum(total_venta) as total_venta'))
//                ->whereBetween('fecha_hora',[$pieces[0],$pieces[1]])
                ->paginate(30);
        }else{
            $date = $request->get('daterange');
            $pieces = explode(" - ", $date);

            $venta = DB::table('venta')
//            ->select('fecha_hora', DB::raw('sum(total_venta) as total_venta'))
                ->whereBetween('fecha_hora',[$pieces[0],$pieces[1]])
                ->orderBy('fecha_hora','desc')
                ->paginate(30);
        }
//        var_dump($request->get('daterange'));


        $query = trim($request->get('searchText'));
        $stock = DB::table('articulo')
            ->where('barcode','LIKE','%'.$query.'%')
            ->where('estado','=','Activo')
            ->paginate(30);


        if($request->is('reportes/grafico/detallestock'))
            return view('reportes.grafico.detallestock', ['stock'=> $stock,'searchText'=>$query]);
        else    return view('reportes.grafico.detalleganancias', ['venta'=> $venta]);
        //return view('home');*/
    }

    public function volveracero($id){
        $stock = Articulo::findOrFail($id);
        $stock->stock = 0;
        $stock->update();
        return Redirect::to('reportes/grafico/detallestock');
    }

    public function getCajaDeAyer() {
        $mytime= Carbon::now('America/Argentina/Buenos_Aires')->yesterday();

        //$date=$mytime->toDateTimeString();

        $collection = DB::table('venta')
            ->whereDay('fecha_hora',$mytime->day)
            ->whereMonth('fecha_hora',$mytime->month)
            ->whereYear('fecha_hora',$mytime->year)
            ->paginate(30);

        return view('reportes.grafico.cajadeayer', compact('collection'));
    }

    public function getCajaDeHoy() {
        $mytime= Carbon::now('America/Argentina/Buenos_Aires');

        $detalle_venta_hoy = DB::table('detalle_venta as dv')
            ->join('venta as v','dv.idventa','=','v.idventa')
            ->join('articulo as art','art.idarticulo','=','dv.idarticulo')
            ->whereDay('v.fecha_hora',$mytime->day)
            ->whereMonth('v.fecha_hora',$mytime->month)
            ->whereYear('v.fecha_hora',$mytime->year)
//            ->paginate(30);
            ->get();

        return view('reportes.grafico.cajadehoy', compact('detalle_venta_hoy'));;
    }

    public function getDetalleStock(Request $request) {
        if($request){
            $query = trim($request->get('searchText'));
            $stock = DB::table('articulo')
                ->where('barcode','LIKE','%'.$query.'%')
                ->orwhere('nombre','LIKE','%'.$query.'%')
                ->where('estado','=','Activo')
                ->paginate(30);
        }


        return view('reportes.grafico.detallestock', ['stock'=> $stock,'searchText'=>$query]);
    }

    public function getDetalleGanancias(Request $request) {
        if($request) {
            if ($request->get('daterange') == null || $request->get('daterange') == '') {
                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                $mytime2 = Carbon::now()->startOfMonth();
                $firstday = $mytime2->toDateTimeString();
                $today = $mytime->toDateTimeString();
                $venta = DB::table('venta')
                    ->orderBy('fecha_hora', 'desc')
////            ->select('fecha_hora', DB::raw('sum(total_venta) as total_venta'))
               ->whereBetween('fecha_hora',[$firstday,$today])
                    ->paginate(30);
            } else {
                //dd($request);
                $date = $request->get('daterange');
                $pieces = explode(" - ", $date);
                $pieces[0]=$pieces[0] . ' 00:00:00';
                $pieces[1]=$pieces[1] . ' 23:59:00';
                $venta = DB::table('venta')
//            ->select('fecha_hora', DB::raw('sum(total_venta) as total_venta'))
                    ->whereBetween('fecha_hora',[$pieces[0],$pieces[1]])
                    ->orderBy('fecha_hora', 'desc')
                    ->paginate(30);
            }

        }

        return view('reportes.grafico.detalleganancias', ['venta'=> $venta]);
    }

    public function ventasDelAno()
    {

        $collection = DB::table('venta')
            ->select(DB::raw('SUM(total_venta_real) as total'))
            ->whereYear('fecha_hora', '=', 2018)
            ->groupBy(DB::raw("month(fecha_hora)"))
            ->get();
        return $collection;
    }

}