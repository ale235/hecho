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
                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                $ahora = $mytime->toDateTimeString();
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
                return view('balance.index', ['balance' => $balance, 'fechasDeBalances' => $fechasDeBalances, 'arqueo' => $arqueo, 'pagos' => $pagos, 'ventas' => $ventas, 'total' => $total, 'date' => $date , 'retirosBalance' => $retirosBalance]);

            }
            else{
                $hola = $request->get('fbalance');
                sort($hola);
                $date = $hola[0] . ' - ' . $hola[1];
                $balance = DB::table('balance')
                    ->where('fecha','=',$hola[0])
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
                return view('balance.index', ['balance' => $balance, 'fechasDeBalances' => $fechasDeBalances, 'arqueo' => $arqueo, 'pagos' => $pagos, 'ventas' => $ventas, 'total' => $total, 'retirosBalance' => $retirosBalance, 'date' => $date]);
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


        $pieces[0]=$pieces[0] . ' 00:00:00';
        $pieces[1]=$pieces[1] . ' 23:59:00';
        dd($pieces);

        if ($date != null && $date != '' && strtotime($date)) {


            $aux = DB::table('articulo as a')
                ->join('detalle_venta as dv', 'dv.idarticulo', '=', 'a.idarticulo')
                ->join('venta as v', 'v.idventa', '=', 'dv.idventa')
                ->select('a.nombre','a.idcategoria', 'dv.precio_venta', 'v.fecha_hora', DB::raw('SUM(dv.cantidad) AS cantidad'), DB::raw('SUM(dv.precio_venta*dv.cantidad) AS precio_total'))
                ->where('v.fecha_hora', '<', $date)
                ->groupBy('a.nombre','a.idcategoria', 'dv.precio_venta', 'v.fecha_hora')
                ->orderBy('v.fecha_hora', 'desc')
                ->get();


        }
        else {
            $pieces = explode(" - ", $date);
            $pieces[0]=$pieces[0] . ' 00:00:00';
            $pieces[1]=$pieces[1] . ' 23:59:00';
            $aux = DB::table('articulo as a')
                ->join('detalle_venta as dv', 'dv.idarticulo', '=', 'a.idarticulo')
                ->join('venta as v', 'v.idventa', '=', 'dv.idventa')
                ->select('a.nombre','a.idcategoria', 'dv.precio_venta', 'v.fecha_hora', DB::raw('SUM(dv.cantidad) AS cantidad'), DB::raw('SUM(dv.precio_venta*dv.cantidad) AS precio_total'))
                ->whereBetween('v.fecha_hora', [$pieces[0],$pieces[1]])
                ->groupBy('a.nombre','a.idcategoria', 'dv.precio_venta', 'v.fecha_hora')
                ->orderBy('v.fecha_hora', 'desc')
                ->get();
        }

        $columna = [];
        $cont2 = 1;
        $total = 0;
        $subtotales = [0,0,0,0,0,0,0,0];

        foreach ($aux as $a) {
            $fila = [];

            $fila[0] = $a->nombre;
            $fila[1] = $a->precio_venta;
            $fila[2] = number_format($a->cantidad,2);
            $fila[3] = number_format($a->precio_total,2);
            $fila[4] = $a->fecha_hora;
            $fila[5] = $a->idcategoria;
            $subtotales[$a->idcategoria] = $subtotales[$a->idcategoria] + $fila[3];
            $total = $total + $fila[3];
            $columna[$cont2] = $fila;
            $cont2 = $cont2 + 1;
        }
        $fila0 = [];
        $fila0[0] = 'Nombre';
        $fila0[1] = 'Precio Venta';
        $fila0[2] = 'Cantidad';
        $fila0[3] = 'Precio total';
        $fila0[4] = 'Fecha';
        $fila0[5] = 'Categoria';

        $filanueva = [];
        $filanueva[0] = ' ';
        $filanueva[1] = ' ';
        $filanueva[2] = ' ';
        $filanueva[3] = $total;
        $filanueva[4] = ' ';
        $filanueva[5] = ' ';
        //$columna[$cont2] = $filanueva;


//        dd($columna);

        usort($columna, function ($item1, $item2) {
            if ($item1[5] == $item2[5]) return 0;
            return $item1[5] < $item2[5] ? -1 : 1;
        });

        array_unshift($columna,$fila0);
        array_push($columna,$filanueva);
        //dd($columna);

        $pieces = explode(" - ", $date);

        //<editor-fold desc="Pagos">


        $pagos = DB::table('pagos')
            ->whereBetween('fecha', array($pieces[0], $pieces[1]))
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
            ->whereBetween('fecha', array($pieces[0], $pieces[1]))
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
        Excel::create('Resultado entre: '.$pieces[0].' a '.$pieces[1], function ($excel) use ($columna, $subtotales,$pagoscol, $totalPago,$arqueocol,$totalArqueo) {

            $excel->sheet('Excel sheet', function ($sheet) use ($columna, $subtotales, $pagoscol, $totalPago,$arqueocol,$totalArqueo) {
                $sheet->setAutoSize(true);
                //$sheet->setBorder('A1:F10', 'thin');
                $sheet->setOrientation('landscape');
                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                if($mytime->hour < 16) {
                    $turno = "Mañana";
                }else {
                    $turno = "Tarde";
                }
                $sheet->mergeCells('A1:F1');
                $today = Carbon::now('America/Argentina/Buenos_Aires')->format("d/m/Y");
                $row = 2;
                $sheet->row($row, ['Fecha', $today, 'Turno', $turno , 'Vendedor', Auth::user()->name]);
                $sheet->mergeCells('A3:F3');

                $sheet->mergeCells('A4:F4');

                //<editor-fold desc="Kiosco">
                $row = 4;
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Kiosco');
                    $cell->setAlignment('center');
                    //$cell->setF('Kiosco');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(16);

                });
                //$sheet->row($row, ['Kiosco']);
                $row = 5;
                $sheet->row($row, $columna[0]);
                $i = 1;
                while($columna[$i][5] == 1) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;

                }
                //</editor-fold>

                //<editor-fold desc="Panificacion">
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Panificacion');
                    $cell->setAlignment('center');

                });
                $row++;
                $sheet->row($row, $columna[0]);
                while($columna[$i][5] == 2) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;

                }
                //</editor-fold>

                //<editor-fold desc="Comida">
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Comida');
                    $cell->setAlignment('center');

                });
                $row++;
                $sheet->row($row, $columna[0]);
                while($columna[$i][5] == 3) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;

                }
                //</editor-fold>

                //<editor-fold desc="Arqueo">
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Arqueo');
                    $cell->setAlignment('center');

                });
                $row++;
                $sheet->row($row, $columna[0]);
                while($columna[$i][5] == 4) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;
                    // dd($columna[$i][5]);
                }
                //</editor-fold>

                //<editor-fold desc="Lacteos">
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Lacteos');
                    $cell->setAlignment('center');

                });
                $row++;
                $sheet->row($row, $columna[0]);

                while($columna[$i][5] == 5) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;
                }
                //</editor-fold>

                //<editor-fold desc="Bebidas">
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Bebidas');
                    $cell->setAlignment('center');

                });
                $row++;
                $sheet->row($row, $columna[0]);
                while($columna[$i][5] == 6) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;
                }
                //</editor-fold>

                //<editor-fold desc="Cigarrillos">
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $row++;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {

                    // manipulate the cell
                    $cell->setValue('Cigarrillos');
                    $cell->setAlignment('center');

                });
                $row++;
                $sheet->row($row, $columna[0]);
                while($columna[$i][5] == 7) {
                    $row++;
                    $sheet->row($row, $columna[$i]);
                    $i++;
                }
                //</editor-fold>


                $sheet->setBorder('A1:F'.$row, 'thin');
                $row = $row + 3;

                $sheet->row($row, [' ','Subtotales']);
                $sheet->row($row+1, ['Kiosco',$subtotales[1]]);
                $sheet->row($row+2, ['Panificacion',number_format($subtotales[2],2)]);
                $sheet->row($row+3, ['Comida',$subtotales[3]]);
                $sheet->row($row+4, ['Arqueo',$subtotales[4]]);
                $sheet->row($row+5, ['Lacteos',$subtotales[5]]);
                $sheet->row($row+6, ['Bebidas',$subtotales[6]]);
                $sheet->row($row+7, ['Cigarrillos',$subtotales[7]]);
                $totalIngresos = number_format($columna[$i][3],2);
                $sheet->row($row+9, ['Total',number_format($columna[$i][3],2)]);

                $row = $row +11;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {
                    $cell->setAlignment('center');

                });
                $sheet->row($row, ['Pagos']);
                $row = $row +1;
                $aux = 0;
                for($i = 0; count($pagoscol)> $i; $i++){
                    $sheet->row($row + $i, $pagoscol[$i]);
                    $aux = $aux + 1;
                }
                $row = $row + $aux;
                $sheet->row($row+1, ['Total Pagos',number_format($totalPago,2)]);

                $row = $row +3;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {
                    $cell->setAlignment('center');

                });
                $sheet->row($row, ['Arqueo']);
                $row = $row +1;
                $aux = 0;
                for($i = 0; count($arqueocol)> $i; $i++){
                    $sheet->row($row + $i, $arqueocol[$i]);
                    $aux = $aux + 1;
                }
                $row = $row + $aux;
                $sheet->row($row+1, ['Total Arqueo',number_format($totalArqueo,2)]);

                $row = $row + 3;
                $sheet->mergeCells('A'.$row.':F'.$row);
                $sheet->cell('A'.$row, function($cell) {
                    $cell->setAlignment('center');

                });
                $sheet->row($row, ['Caja del día']);
                $intV = intval(str_replace(",","",$totalIngresos));
                $sheet->row($row+2, ['Total: ',$intV+$totalArqueo-$totalPago]);

//                dd($subtotales);
            });

        })->download('xls');
    }

}
