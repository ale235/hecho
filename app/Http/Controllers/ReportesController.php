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
        $proveedores = DB::table('articulo')
            ->select('proveedor',DB::raw('count(*) as total'))
            ->groupBy('proveedor')
            ->get();

        $articulos =  DB::table('articulo as a')
            ->join('detalle_venta as dv','a.idarticulo','=','dv.idarticulo')
            ->get();

        $venta = DB::table('venta')
            ->select('fecha_hora', DB::raw('sum(total_venta) as total_venta'))
            ->whereBetween('fecha_hora',['2016-02-02 00:00:00','2017-02-05 00:00:00'])
            ->groupBy('fecha_hora')
            ->get();

        return view('reportes.grafico.index', ['proveedores'=> $proveedores,'articulos'=>$articulos, 'venta'=> $venta]);
        //return view('home');
    }

    public function projectsChartData()
    {
//        $devlist =  DB::table('articulo as a')
//            ->join('detalle_venta as dv','a.idarticulo','=','dv.idarticulo')
//            ->get();

        $start = Carbon::parse('2017-02-01 00:00:00')->startOfDay();  //2016-09-29 00:00:00.000000
        $end = Carbon::parse('2017-02-03 00:00:00')->endOfDay(); //2016-09-29 23:59:59.000000

        $devlist = DB::table('venta')
            ->select('total_venta','fecha_hora')
//            ->whereBetween('fecha_hora',[new Carbon('2017-02-02 00:00:00'),new Carbon('2017-02-03 00:00:00')])
            ->whereBetween('fecha_hora',[$start, $end])
            ->groupBy('total_venta','fecha_hora')
            ->distinct('fecha_hora')->get();

        //Articulo mçàs vendido

        $collection = DB::table('detalle_venta as dv')
            ->join('articulo as art','art.idarticulo','=','dv.idarticulo')
            ->select('dv.idarticulo',DB::raw('sum(dv.cantidad) as lacantidad'),'art.proveedor')
            ->groupBy('dv.idarticulo','art.proveedor')
            ->orderBy('lacantidad','desc')
            ->get();

        return $collection;
    }

    public function articulosSinStock()
    {
        //Articulo mçàs vendido

        $collection = DB::table('articulo')
            ->select(DB::raw('COUNT(*) as cantidad'))
            ->where('stock','<=','0')
            ->where('estado','=','Activo')
            ->get();

        return $collection;
    }

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

        if ($request->get('daterange') == null || $request->get('daterange') == '') {
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
        //return view('home');
    }

    public function volveracero($id){
        $stock = Articulo::findOrFail($id);
        $stock->stock = 0;
        $stock->update();
        return Redirect::to('reportes/grafico/detallestock');
    }

}

//public function scopeBirthdays($query)
//{
//    return $query-> User::whereMonth('DOB' , Carbon::today()->month);