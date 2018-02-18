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
            if($request->get('fbalance') == null){
                $ahora = Carbon::now('America/Argentina/Buenos_Aires')->format("Y-m-d");
//                $ahora = $mytime->toDateTimeString();
                $balance = DB::table('balance')
                    ->orderBy('fecha','desc')
                    ->first();
                $retirosBalance = [];
                $fechasDeBalances = DB::table('balance')
                    ->orderBy('fecha','desc')->get();
                $arqueo = DB::table('arqueo')
                    ->whereBetween('fecha', [$balance->fecha,$ahora])
                    ->get();
                $pagos = DB::table('pagos')
                    ->whereBetween('fecha', [$balance->fecha,$ahora])
                    ->get();
                $ventas = DB::table('venta')
                    ->where('fecha_hora','>=',$balance->fecha)
                    ->sum('total_venta');
                $arqueoSuma = DB::table('arqueo')
                    ->whereBetween('fecha', [$balance->fecha,$ahora])
                    ->sum('monto');
                $pagosSuma = DB::table('pagos')
                    ->whereBetween('fecha', [$balance->fecha,$ahora])
                    ->sum('monto');
                $total = $ventas + $balance->capitalinicial + $arqueoSuma - $pagosSuma;

                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                $date = $mytime->toDateTimeString();
                $balanceFin = null;
                return view('balance.index', ['balance' => $balance, 'fechasDeBalances' => $fechasDeBalances, 'arqueo' => $arqueo, 'pagos' => $pagos, 'ventas' => $ventas, 'total' => $total, 'date' => $date , 'retirosBalance' => $retirosBalance, 'ahora' => $ahora, 'balanceFin' => $balanceFin]);

            }
            else{
                $ahora = Carbon::now('America/Argentina/Buenos_Aires')->format("Y-m-d");
//                $ahora = $mytime->toDateTimeString();
                $hola = $request->get('fbalance');
                sort($hola);
                $date = $hola[0] . ' - ' . $hola[1];
                $balance = DB::table('balance')
                    ->where('fecha','=',$hola[0])
                    ->orderBy('fecha','desc')
                    ->first();
                $balanceFin = DB::table('balance')
                    ->where('fecha','=',$hola[1])
                    ->orderBy('fecha','desc')
                    ->first();
                $retirosBalance = DB::table('balance')
                    ->where([
                        ['fecha', '>', $hola[0]],
                        ['fecha', '<', $hola[1]],
                    ])
                    ->orderBy('fecha','desc')
                    ->get();
                $fechasDeBalances = DB::table('balance')
                    ->orderBy('fecha','desc')->get();
                $arqueo = DB::table('arqueo')
                    ->whereBetween('fecha', [$hola[0],$hola[1]])
                    ->get();
                $pagos = DB::table('pagos')
                    ->whereBetween('fecha', [$hola[0],$hola[1]])
                    ->get();
                $ventas = DB::table('venta')
                    ->whereBetween('fecha_hora', [$hola[0],$hola[1]])
                    ->sum('total_venta');
                $arqueoSuma = DB::table('arqueo')
                    ->whereBetween('fecha', [$hola[0],$hola[1]])
                    ->sum('monto');
                $pagosSuma = DB::table('pagos')
                    ->whereBetween('fecha', [$hola[0],$hola[1]])
                    ->sum('monto');

                $totalRetiros = 0;
                foreach ($retirosBalance as $r){
                    $totalRetiros = $totalRetiros + $r->retirodecapital;
                }
                $total = $ventas + $balance->capitalinicial + $arqueoSuma - $pagosSuma - $totalRetiros;
                //dd($date);
                return view('balance.index', ['balance' => $balance, 'fechasDeBalances' => $fechasDeBalances, 'arqueo' => $arqueo, 'pagos' => $pagos, 'ventas' => $ventas, 'total' => $total, 'retirosBalance' => $retirosBalance, 'date' => $date, 'ahora' => $ahora, 'balanceFin' => $balanceFin]);
        }
    }

    public function create(Request $request)
    {
        if($request){
            return view('balance.create');
        }
    }

    public function store(Request $request)
    {
        if($request){
            $mytime = Carbon::now('America/Argentina/Buenos_Aires');
            //dd($request);
            $balance = new Balance;
            $balance->fecha = $request->get('daterange');
            $balance->retirodecapital = $request->get('retirodecapital');
            $balance->capitalinicial = $request->get('capitalinicial');
            $balance->save();
//            $total = DB::table('arqueo')->sum('monto');
            return Redirect::to('balance');
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
            $arqueo = Balance::findOrFail($id);
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
        $arqueo = Balance::findOrFail($id);
        $arqueo->delete();
        return Redirect::to('balance');
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
        $totalVentas = 0;
        foreach ($aux as $a) {
            $venta = array();
            $subtotal= 0;
            $fechasplit = explode(' ', $a[0]->fecha_hora);
            $venta[0] = $fechasplit[0];
            foreach ($a as $b) {
                $subtotal = $subtotal +  number_format($b->total_venta,2);
            }
            $venta[1] = $subtotal;
            $totalVentas = $totalVentas + $venta[1];
            array_push($ventas,$venta);
        }

        Excel::create('Caja ' . $mytime->format('Y-m-d'), function ($excel) use ($balance,$pagoscol, $totalPago,$arqueocol,$totalArqueo, $ventas,$totalVentas) {

            $excel->sheet('Excel sheet', function ($sheet) use ($balance,$pagoscol, $totalPago,$arqueocol,$totalArqueo,$ventas,$totalVentas) {

//                $sheet->row(1, ['Fecha', 'Cliente', 'Total']);
//                $sheet->fromArray($ventas, null, 'A1', false, false);
                $row = 1;

                $sheet->setAutoSize(true);
                $sheet->mergeCells('A'.$row . ':F'.$row);
                $sheet->setSize('A' . $row, 25, 18);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Ingresos');
                    $cell->setAlignment('center');
                    //$cell->setF('Kiosco');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(16);

                });


                $row = 2;
                $sheet->mergeCells('B'.$row . ':C'.$row);
                $sheet->mergeCells('D'.$row . ':E'.$row);
                $sheet->setSize('A' . $row, 23, 18);
                $sheet->row($row,[' ','Mañana',' ','Tarde',' ','Total']);

                //<editor-fold desc="Ventas">
                $row = 3;
                $sheet->mergeCells('B'.$row . ':C'.$row);
                $sheet->mergeCells('D'.$row . ':E'.$row);
                $sheet->row($row,['Ventas','','','','',$totalVentas]);
                //</editor-fold>

                //<editor-fold desc="Arqueo">
                $row = 4;
                $sheet->mergeCells('B'.$row . ':C'.$row);
                $sheet->mergeCells('D'.$row . ':E'.$row);
                $sheet->row($row,['Arqueos','','','','',$totalArqueo]);
                //</editor-fold>
                //<editor-fold desc="Subtotal">
                $row = 5;
                $sheet->mergeCells('A'.$row . ':D'.$row);

                $sheet->setSize('E' . $row, 25, 18);
                $sheet->row($row,[' ','','','','Total Ingresos',$totalArqueo+$totalVentas]);
                //</editor-fold>

                $row = 6;
                $sheet->mergeCells('A'.$row . ':F'.$row);
                $sheet->setSize('A' . $row, 25, 18);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Egresos');
                    $cell->setAlignment('center');
                    //$cell->setF('Kiosco');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(16);

                });
                $row = 7;
                $sheet->mergeCells('B'.$row . ':C'.$row);
                $sheet->mergeCells('D'.$row . ':E'.$row);
                $sheet->setSize('A' . $row, 23, 18);
                $sheet->row($row,[' ','Mañana',' ','Tarde',' ','Total']);
                //<editor-fold desc="Pagos">
                $row = 8;
                $sheet->mergeCells('B'.$row . ':C'.$row);
                $sheet->mergeCells('D'.$row . ':E'.$row);
                $sheet->row($row,['Pagos','','','','',$totalPago]);
                //</editor-fold>
                //<editor-fold desc="Subtotal">
                $row = 9;
                $sheet->mergeCells('A'.$row . ':D'.$row);
                $sheet->setSize('E' . $row, 25, 18);
                $sheet->row($row,[' ','','','','Total Egresos',$totalPago]);
                //</editor-fold>

                $sheet->setBorder('A1:F'.$row, 'thin');

                $row = 13;

                $sheet->row($row, [' ','Subtotales']);
                $sheet->row($row+1, ['Capital Inicial Anterior:',$balance->capitalinicial]);
                $sheet->row($row+2, ['Ingresos: ',$totalArqueo+$totalVentas]);
                $sheet->row($row+3, ['Egresos: ',$totalPago]);
                $sheet->row($row+4, ['Balance Final hasta este día: ',$balance->capitalinicial + $totalArqueo+$totalVentas - $totalPago]);



            });

        })->download('xls');
    }

    public function balanceDesdeHastaDetalle(Request $request,$date)
    {

        $pieces = explode(" - ", $date);



        if(1 == count($pieces)){
            $this->balanceHastaElDiaDeHoy($request);
        }
        else{
            $pieces[0]=$pieces[0] . ' 00:00:00';
            $pieces[1]=$pieces[1] . ' 23:59:00';



            //<editor-fold desc="Ventas">
            $aux = DB::table('venta as v')
                ->join('detalle_venta as dv', 'dv.idventa', '=', 'v.idventa')
                ->join('articulo as a', 'a.idarticulo', '=', 'dv.idarticulo')
                ->join('categoria as c', 'c.idcategoria', '=', 'a.idcategoria')

                ->select('v.fecha_hora','dv.cantidad','dv.precio_venta','c.nombre')
                ->whereBetween('v.fecha_hora', [$pieces[0],$pieces[1]])
                ->orderby('v.fecha_hora','desc')
                ->get()
                ->groupBy(function($date) {
//                return Carbon::parse($date->fecha_hora)->format('Y-m-d');
                    return $date->nombre;// grouping by years
                    //return Carbon::parse($date->created_at)->format('m'); // grouping by months
                });

            $ventas = [];
            $count = 0;
            $totalVentas = 0;
            foreach ($aux as $a) {
                $venta = array();
                $subtotal= 0;
                //$fechasplit = explode(' ', $a[0]->fecha_hora);
                $venta[0] = $a[0]->nombre;
                foreach ($a as $b) {
                    $intV = intval(str_replace(",",".",$b->precio_venta));
                    $subtotal = $subtotal + ($b->cantidad * number_format($intV,2) ) ;
                }
                $venta[1] = $subtotal;
                $totalVentas = $totalVentas + $venta[1];
                array_push($ventas,$venta);
            }
            //</editor-fold>

            //<editor-fold desc="Balance">
            $balance = DB::table('balance as b')
                ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                ->orderBy('fecha', 'asc')
                ->get();

//        dd($balance);
            //</editor-fold>
            //<editor-fold desc="Pagos">


            $pagos = DB::table('pagos')
                ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                ->get();

            $pagoscol = [];
            $cont3 = 0;
            $totalPago = 0;
            foreach ($pagos as $p) {
                $fila = [];

                $fila[0] = $p->descripcion;
                $fila[1] = $p->monto;
                $pagoscol[$cont3] = $fila;
                $cont3 = $cont3 + 1;
                $totalPago = $totalPago + $fila[1];
            }

            //</editor-fold>

            //<editor-fold desc="Arqueo">


            $arqueo = DB::table('arqueo')
                ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                ->get();

            $arqueocol = [];
            $cont3 = 0;
            $totalArqueo = 0;
            foreach ($arqueo as $a) {
                $fila = [];

                $fila[0] = $a->descripcion;
                $fila[1] = $a->monto;
                $arqueocol[$cont3] = $fila;
                $cont3 = $cont3 + 1;
                $totalArqueo = $totalArqueo + $fila[1];
            }
//        $filaarqueo0 = [];
//        $filaarqueo0[0] = 'Descripcion';
//        $filaarqueo0[1] = 'Monto';
//
//        array_unshift($arqueocol,$filaarqueo0);

            //</editor-fold>



            Excel::create('Caja ', function ($excel) use ($balance,$pagoscol, $totalPago,$arqueocol,$totalArqueo, $ventas,$totalVentas) {

                $excel->sheet('Excel sheet', function ($sheet) use ($balance,$pagoscol, $totalPago,$arqueocol,$totalArqueo,$ventas,$totalVentas) {

//                $sheet->row(1, ['Fecha', 'Cliente', 'Total']);
//                $sheet->fromArray($ventas, null, 'A1', false, false);
                    $row = 1;

                    $sheet->setAutoSize(true);
                    $sheet->mergeCells('A'.$row . ':F'.$row);
                    $sheet->setSize('A' . $row, 25, 18);
                    $sheet->cell('A'.$row, function($cell) {

                        // manipulate the cell
                        $cell->setValue('Ingresos');
                        $cell->setAlignment('center');
                        //$cell->setF('Kiosco');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);

                    });


                    $row = 2;
                    $sheet->mergeCells('B'.$row . ':C'.$row);
                    $sheet->mergeCells('D'.$row . ':E'.$row);
                    $sheet->setSize('A' . $row, 23, 18);
                    $sheet->row($row,[' ','Mañana',' ','Tarde',' ','Total']);

                    //<editor-fold desc="Balance">
                    $row = 3;
                    $sheet->mergeCells('B'.$row . ':C'.$row);
                    $sheet->mergeCells('D'.$row . ':E'.$row);
//                $sheet->setSize('A' . $row, 21, 18);
                    $sheet->cell('A'.$row, function($cell) {

                        // manipulate the cell
                        $cell->setValue('Capital Inicial');
//                    $cell->setAlignment('center');
//                    $cell->setFontWeight('bold');
//                    $cell->setFontSize(15);

                    });
                    $sheet->row($row,['Capital Inicial','','','','',$balance[0]->capitalinicial]);
                    //</editor-fold>

                    //<editor-fold desc="Ventas">
//                $row = 4;
//                $sheet->mergeCells('B'.$row . ':C'.$row);
//                $sheet->mergeCells('D'.$row . ':E'.$row);
//
//                $sheet->setSize('A' . $row, 21, 18);
//                $sheet->cell('A'.$row, function($cell) {
//
//                    // manipulate the cell
//                    $cell->setValue('Ventas');
////                    $cell->setAlignment('center');
////                    $cell->setFontWeight('bold');
//                    $cell->setFontSize(15);
//
//                });
//                $sheet->row($row,['Ventas','','','','','']);

//                $sheet->row($row,['Ventas','','','','',$totalVentas]);

                    $i = 0;
                    $row = 4;
                    while($i < count($ventas)) {
                        //$row++;
                        $sheet->row($row,[$ventas[$i][0],'','','','',$ventas[$i][1]]);
                        $i++;
                        $row++;

                    }

                    //</editor-fold>

                    //<editor-fold desc="Arqueo">
                    $row++;
                    $i = 0;
                    while($i < count($arqueocol)) {
                        //$row++;
                        $sheet->row($row,[$arqueocol[$i][0],'','','','',$arqueocol[$i][1]]);
                        $i++;
                        $row++;

                    }
//                $sheet->row($row,['Arqueos','','','','',$totalArqueo]);
                    //</editor-fold>


                    //<editor-fold desc="Subtotal">
                    $row++;
                    $sheet->mergeCells('A'.$row . ':D'.$row);

                    $sheet->setSize('E' . $row, 25, 18);
                    $sheet->row($row,[' ','','','','Total Ingresos',$totalArqueo+$totalVentas + $balance[0]->capitalinicial]);
                    //</editor-fold>

                    $row = $row + 2;
                    $sheet->mergeCells('A'.$row . ':F'.$row);
                    $sheet->setSize('A' . $row, 25, 18);
                    $sheet->cell('A'.$row, function($cell) {

                        // manipulate the cell
                        $cell->setValue('Egresos');
                        $cell->setAlignment('center');
                        //$cell->setF('Kiosco');
                        $cell->setFontWeight('bold');
                        $cell->setFontSize(16);

                    });

                    $row++;
                    $sheet->mergeCells('B'.$row . ':C'.$row);
                    $sheet->mergeCells('D'.$row . ':E'.$row);
                    $sheet->setSize('A' . $row, 23, 18);
                    $sheet->row($row,[' ','Mañana',' ','Tarde',' ','Total']);


                    //<editor-fold desc="Pagos">
                    $row++;

                    $i = 0;
                    while($i < count($pagoscol)) {
                        //$row++;
                        $sheet->row($row,[$pagoscol[$i][0],'','','','',$pagoscol[$i][1]]);
                        $i++;
                        $row++;

                    }
//                $sheet->mergeCells('B'.$row . ':C'.$row);
//                $sheet->mergeCells('D'.$row . ':E'.$row);
//                $sheet->row($row,['Pagos','','','','',$totalPago]);
                    //</editor-fold>

                    //<editor-fold desc="Retiros">
                    $row++;

                    $i = 1;
//                dd($balance);
                    $sumretiro = 0;
                    while($i < count($balance)-1) {
                        //$row++;
                        $sheet->row($row,['Retiro de Capital: ' . $balance[$i]->fecha,'','','','',$balance[$i]->retirodecapital]);
                        $sumretiro = $sumretiro + $balance[$i]->retirodecapital;
                        $i++;
                        $row++;

                    }
//                $sheet->mergeCells('B'.$row . ':C'.$row);
//                $sheet->mergeCells('D'.$row . ':E'.$row);
//                $sheet->row($row,['Pagos','','','','',$totalPago]);
                    //</editor-fold>

                    //<editor-fold desc="Subtotal">
                    $row = $row + 2;
                    $sheet->mergeCells('A'.$row . ':D'.$row);
                    $sheet->setSize('E' . $row, 25, 18);
                    $sheet->row($row,[' ','','','','Total Egresos',$totalPago + $sumretiro]);
                    //</editor-fold>

                    $sheet->setBorder('A1:F'.$row, 'thin');
//
                    $row = $row + 2;
//
                    $sheet->row($row, [' ','Subtotales']);
                    $sheet->row($row+1, ['Capital Inicial Anterior:',$balance[0]->capitalinicial]);
                    $sheet->row($row+2, ['Ingresos: ',$totalArqueo+$totalVentas]);
                    $sheet->row($row+3, ['Egresos: ',$totalPago+$sumretiro]);
                    $sheet->row($row+4, ['Balance Final hasta este día: ',$balance[0]->capitalinicial + $totalArqueo+$totalVentas - $totalPago- $sumretiro]);



                });

            })->download('xls');
        }
//        $pieces = explode(" - ", $date);



    }

}
