<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use ideas\Balance;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class BalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

            if($request->get('daterange') == null){
                $balance = DB::table('balance')
                    ->orderBy('fecha','desc')
                    ->first();

                $fechasDeBalances = DB::table('balance')
                    ->orderBy('fecha','desc')->get();

//                $arqueo = DB::table('arqueo')
//                    ->whereBetween('fecha', [$pieces[0],$pieces[1]])
//                    ->get();
//
//                $total = DB::table('arqueo')
//                    ->whereBetween('fecha', [$pieces[0],$pieces[1]])
//                    ->sum('monto');
                //dd($balance->fecha);
//                $ventas = DB::table('venta')
//                    ->where('fecha_hora','>=',$balance->fecha)
//                    ->sum('total_venta');
//
//                dd($ventas);

                return view('balance.index', ['balance' => $balance, 'fechasDeBalances' => $fechasDeBalances]);

            }
            else{
                $pieces = explode(" - ", $request->get('daterange'));
                if(isset($pieces[1])){
                    $pieces[0]=$pieces[0] . ' 00:00:00';
                    $pieces[1]=$pieces[1] . ' 23:59:00';
                }
                else {
                    $pieces = array($pieces[0].' 00:00:00', $pieces[0]. ' 23:59:00');
                }
                $arqueo = DB::table('arqueo')
                    ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                    ->get();

                $total = DB::table('arqueo')
                    ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                    ->sum('monto');

                return view('arqueo.index', ['arqueos' => $arqueo, 'total' => $total]);
        }
    }

    public function create(Request $request)
    {
        if($request){
            return view('arqueo.create');
        }
    }

    public function store(Request $request)
    {
        if($request){
            $mytime = Carbon::now('America/Argentina/Buenos_Aires');
            //dd($request);
            $arqueo = new Arqueo;
            $arqueo->fecha = $request->get('daterange');
            $arqueo->descripcion = $request->get('descripcion');
            $arqueo->monto = $request->get('monto');
            $arqueo->save();
//            $total = DB::table('arqueo')->sum('monto');
            return Redirect::to('arqueo');
        }
    }

    public function edit($id)
    {
        $arqueo = Arqueo::findOrFail($id);
        return view('arqueo.edit',['arqueo'=> $arqueo]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $arqueo = Arqueo::findOrFail($id);
            $arqueo->fecha = $request->get('daterange');
            $arqueo->descripcion = $request->get('descripcion');
            $arqueo->monto = $request->get('monto');
            $arqueo->update();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return Redirect::to('arqueo');
    }

    public function show($id)
    {
        return view('ventas.venta.show');
    }

    public function destroy($id)
    {
        $arqueo = Arqueo::findOrFail($id);
        $arqueo->delete();
        return Redirect::to('arqueo');
    }

    public function balanceHastaElDiaDeHoy(Request $request)
    {


        $mytime = Carbon::now('America/Argentina/Buenos_Aires');
        $mytime2 = Carbon::now('America/Argentina/Buenos_Aires');
        $mytime2->hour = 0;
        $mytime2->minute = 0;
        $mytime2->second = 0;
        $yesterday = $mytime2->toDateTimeString();
        $today = $mytime->toDateTimeString();

        $balance = DB::table('balance as b')
            ->orderBy('b.fecha', 'desc')
            ->first();

        //<editor-fold desc="Pagos">


        $pagos = DB::table('pagos')
            ->whereBetween('fecha', array($yesterday, $today))
            ->get();

        $pagoscol = [];
        $cont3 = 1;
        $totalPago = 0;
        foreach ($pagos as $p) {
            $fila = [];

            $fila[0] = $p->descripcion;
            $fila[1] = $p->monto;
            $pagoscol[$cont3] = $fila;
            $cont3 = $cont3 + 1;
            $totalPago = $totalPago + $fila[1];
        }
        $filapago0 = [];
        $filapago0[0] = 'Descripcion';
        $filapago0[1] = 'Monto';

        array_unshift($pagoscol,$filapago0);

        //</editor-fold>

        //<editor-fold desc="Arqueo">


        $arqueo = DB::table('arqueo')
            ->whereBetween('fecha', array($yesterday, $today))
            ->get();

        $arqueocol = [];
        $cont3 = 1;
        $totalArqueo = 0;
        foreach ($arqueo as $a) {
            $fila = [];

            $fila[0] = $a->descripcion;
            $fila[1] = $a->monto;
            $arqueocol[$cont3] = $fila;
            $cont3 = $cont3 + 1;
            $totalArqueo = $totalArqueo + $fila[1];
        }
        $filaarqueo0 = [];
        $filaarqueo0[0] = 'Descripcion';
        $filaarqueo0[1] = 'Monto';

        array_unshift($arqueocol,$filaarqueo0);

        //</editor-fold>

        //dd($balance);
        $aux = DB::table('venta as v')
            ->select('v.fecha_hora','v.total_venta')
            ->whereBetween('v.fecha_hora', [$balance->fecha,$today])
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->fecha_hora)->format('Y-m-d'); // grouping by years
                //return Carbon::parse($date->created_at)->format('m'); // grouping by months
            });

        $ventas = [];
        $count = 0;
        foreach ($aux as $a) {
            $venta = array();
            $subtotal= 0;
            $fechasplit = explode(' ', $a[0]->fecha_hora);
            $venta[0] = $fechasplit[0];
            foreach ($a as $b) {
                $subtotal = $subtotal +  number_format($b->total_venta,2);
            }
            $venta[1] = $subtotal;
            array_push($ventas,$venta);
        }

        Excel::create('Caja ' . $mytime->format('Y-m-d'), function ($excel) use ($pagoscol, $totalPago,$arqueocol,$totalArqueo, $ventas) {

            $excel->sheet('Excel sheet', function ($sheet) use ($pagoscol, $totalPago,$arqueocol,$totalArqueo,$ventas) {

                $sheet->row(1, ['Fecha', 'Cliente', 'Total']);
                $sheet->fromArray($ventas, null, 'A1', false, false);

            });

        })->download('xls');
    }

}
