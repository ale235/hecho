<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use ideas\Http\Requests;
use ideas\Http\Requests\VentaFormRequest;
use Illuminate\Support\Facades\Input;
use ideas\Venta;
use ideas\Articulo;
use ideas\DetalleVenta;
use ideas\Precio;
use ideas\Persona;
use DB;
use Illuminate\Support\Facades\Auth;
use PHPExcel_Worksheet_Drawing;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            if ($request->get('daterange') == null || $request->get('daterange') == '') {
                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                $date = $mytime->toDateTimeString();
                $ventas = DB::table('venta as v')
                    ->join('persona as p', 'v.idcliente', '=', 'p.idpersona')
                    ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
                    ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta','v.total_venta_real')
                   // ->whereBetween('v.fecha_hora', array(new Carbon($pieces[1]), new Carbon($pieces[0])))
//                ->where('v.num_comprobante', 'LIKE', '%'.$query.'%')
                    ->whereDay('fecha_hora',$mytime->day)
                    ->whereMonth('fecha_hora',$mytime->month)
                    ->whereYear('fecha_hora',$mytime->year)
                    ->orderBy('v.idventa', 'desc')
                    ->groupBy('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta','v.total_venta_real')
                    ->paginate(20);
            } else {

                $date = $request->get('daterange');
                $pieces = explode(" - ", $date);
                $pieces[0]=$pieces[0] . ' 00:00:00';
                $pieces[1]=$pieces[1] . ' 23:59:00';
                $query = trim($request->get('searchText'));
                $ventas = DB::table('venta as v')
                    ->join('persona as p', 'v.idcliente', '=', 'p.idpersona')
                    ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
                    ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta','v.total_venta_real')
//                ->where('v.num_comprobante', 'LIKE', '%'.$query.'%')
                    ->whereBetween('v.fecha_hora', array(new Carbon($pieces[0]), new Carbon($pieces[1])))
                    ->orderBy('v.idventa', 'desc')
                    ->groupBy('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta','v.total_venta_real')
                    ->paginate(20);
            }
//            echo $pieces[0];

            return view('ventas.venta.index', ['ventas' => $ventas, 'date' => $date]);
        }
    }

    public function create()
    {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente')->get();
        $proveedores = DB::table('persona')->where('tipo_persona', '=', 'Proveedor')->where('estado', '=', 'Activo')->get();
        $articulos = Articulo::where('estado', '=', 'Activo')->get();
        $articulosPorPeso = Articulo::where('estado', '=', 'Activo')->where('idcategoria','=','2')->get();
        return view('ventas.venta.create', ['personas' => $personas, 'articulos' => $articulos, 'proveedores' => $proveedores,'articulosporpeso' => $articulosPorPeso]);
    }

    public function edit($id)
    {
        $venta = Venta::findOrFail($id);

        $detalles = DB::table('detalle_venta')->where('idventa', '=', $id)->get();

        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente')->get();
        $proveedores = DB::table('persona')->where('tipo_persona', '=', 'Proveedor')->get();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) AS articulo'), 'art.idarticulo')
//            ->where('art.estado', '=', 'Activo')
            ->get();
        return view('ventas.venta.edit', ['venta' => $venta, 'personas' => $personas, 'articulos' => $articulos, 'proveedores' => $proveedores, 'detalles' => $detalles]);
    }

    public function store(VentaFormRequest $request)
    {
        try {
            DB::beginTransaction();
            $venta = new Venta;
            if($request->get('checkCliente')=='true'){
                $persona = new Persona;
                $persona->tipo_persona = 'Cliente';
                $persona->nombre = $request->get('nombre');
                $persona->num_documento = $request->get('num_documento');
                $persona->direccion = $request->get('direccion');
                $persona->telefono = $request->get('telefono');
                $persona->email = $request->get('email');
                $persona->instagram = $request->get('instagram');
                $persona->facebook = $request->get('facebook');
                $persona->save();
                $venta->idcliente = $persona->idpersona;
            }
            else{
                $venta->idcliente = $request->get('idcliente');
            }
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->serie_comprobante = $request->get('serie_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->total_venta = $request->get('total_venta');
            $venta->idvendedor = auth()->user()->id;
            $mytime = Carbon::now('America/Argentina/Buenos_Aires');

            $venta->fecha_hora = $mytime->toDateTimeString();
            $venta->impuesto = '0';
            $venta->estado = 'Activo';
            $venta->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
//            $descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');

            $cont = 0;
            $totalcompra = 0;
            while ($cont < count($idarticulo)) {
                $detalle = new DetalleVenta();
                $precio_compra = DB::table('precio')->where('idarticulo', '=', $idarticulo[$cont])->orderBy('idprecio','desc')->first();
                $totalcompra = $totalcompra + $precio_compra->precio_compra * $cantidad[$cont];
                $detalle->idventa = $venta->idventa;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
//                $detalle->descuento = $descuento[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $articulo = Articulo::findOrFail($idarticulo[$cont]);
                $articulo->stock = $articulo->stock - $cantidad[$cont];
                $articulo->update();

                $cont = $cont + 1;
            }
            if($request->get('pventa_real') == ''){
                $venta->total_venta_real = $venta->total_venta;
                $ganancia = $venta->total_venta_real - $totalcompra;
            } else{
                $venta->total_venta_real = $request->get('pventa_real');
                $ganancia = $venta->total_venta_real - $totalcompra;
            }
            //$venta = Venta::findOrFail($venta->idventa);
            $venta->total_compra = $totalcompra;
            $venta->ganancia = $ganancia;
            $venta->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }


        return Redirect::to('ventas/venta');
    }

    public function update(VentaFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            DB::table('detalle_venta')->where('idventa', $id)->delete();

            $venta = Venta::findOrFail($id);
            $venta->idcliente = $request->get('idcliente');
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->serie_comprobante = $request->get('serie_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->total_venta = $request->get('total_venta');
            $venta->idvendedor = auth()->user()->id;
            $mytime = Carbon::now('America/Argentina/Buenos_Aires');

            $venta->fecha_hora = $mytime->toDateTimeString();
            $venta->impuesto = '0';
            $venta->estado = 'Activo';
            $venta->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
//            $descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');

            $cont = 0;

            while ($cont < count($idarticulo)) {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->idventa;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
//                $detalle->descuento = $descuento[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();
                $cont = $cont + 1;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return Redirect::to('ventas/venta');
    }

    public function show($id)
    {
        $venta = DB::table('venta as v')
            ->join('persona as p', 'v.idcliente', '=', 'p.idpersona')
            ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
            ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.impuesto', 'v.estado', 'v.total_venta')
            ->where('v.idventa', '=', $id)
            ->first();

        $detalles = DB::table('detalle_venta as d')
            ->join('articulo as a', 'd.idarticulo', '=', 'a.idarticulo')
            ->select('a.nombre as articulo', 'd.cantidad', 'd.descuento', 'd.precio_venta')
            ->where('d.idventa', '=', $id)->get();


        return view('ventas.venta.show', ['venta' => $venta, 'detalles' => $detalles]);
    }

    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);
        $venta->estado = 'Cancelado';
        $venta->update();
        return Redirect::to('ventas/venta');
    }

    public function buscarArticuloPorProveedor(Request $request)
    {


        //if our chosen id and products table prod_cat_id col match the get first 100 data

        //$request->id here is the id of our chosen option id
        $data = DB::table('articulo as art')->select('art.idarticulo', 'art.nombre', 'art.codigo')->where('art.proveedor', '=', $request->codigo)->get();
        //$data= DB::table('articulo as art')->join('persona as p', 'p.codigo' , '=', 'art.proveedor')->select('art.idarticulo','art.nombre','art.codigo','id.persona')->where('p.codigo','=',$request->codigo)->get();

        // $data= DB::table('articulo as art')->where('idarticulo','=',$request->id);
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($data);//then sent this data to ajax success

//        $request->id here is the id of our chosen option id
//        $data=DB::table('articulo as art')->select('idarticulo','nombre')->get();
//        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
//        return response()->json($data);//then sent this data to ajax success
    }

    public function buscarPrecioArticuloVentas(Request $request)
    {


        //if our chosen id and products table prod_cat_id col match the get first 100 data
        //$request->id here is the id of our chosen option id
        $precio = DB::table('precio')
            ->where('idarticulo', '=', $request->id)
            ->orderBy('idarticulo', 'desc')
            ->orderBy('idprecio', 'desc')
            ->get();
        $precio = $precio->unique('idarticulo');
        $articulo = Articulo::findOrFail($request->id);
        //$elprecio = DB::table('articulo')join('precio','idarticulo','=',$precio->idarticulo)->get();
        //$data= DB::table('articulo as art')->join('persona as p', 'p.codigo' , '=', 'art.proveedor')->select('art.idarticulo','art.nombre','art.codigo','id.persona')->where('p.codigo','=',$request->codigo)->get();
        $precio = $precio->merge($articulo);

        // $data= DB::table('articulo as art')->where('idarticulo','=',$request->id);
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($precio);//then sent this data to ajax success

//        $request->id here is the id of our chosen option id
//        $data=DB::table('articulo as art')->select('idarticulo','nombre')->get();
//        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
//        return response()->json($data);//then sent this data to ajax success
    }

    public function buscarPrecioArticuloVentasPorCodigo(Request $request)
    {


        //if our chosen id and products table prod_cat_id col match the get first 100 data
        //$request->id here is the id of our chosen option id
        $articulo = DB::table('articulo')
            ->where('codigo', '=', $request->codigo)
            ->orwhere('barcode','=',$request->codigo)->first();

        $precio = DB::table('precio')
            ->where('idarticulo', '=', $articulo->idarticulo)
            ->orderBy('idarticulo', 'desc')
            ->orderBy('idprecio', 'desc')
            ->get();
        $precio = $precio->unique('idarticulo');
        //$elprecio = DB::table('articulo')join('precio','idarticulo','=',$precio->idarticulo)->get();
        //$data= DB::table('articulo as art')->join('persona as p', 'p.codigo' , '=', 'art.proveedor')->select('art.idarticulo','art.nombre','art.codigo','id.persona')->where('p.codigo','=',$request->codigo)->get();
        $precio = $precio->merge($articulo);

        // $data= DB::table('articulo as art')->where('idarticulo','=',$request->id);
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($precio);//then sent this data to ajax success

//        $request->id here is the id of our chosen option id
//        $data=DB::table('articulo as art')->select('idarticulo','nombre')->get();
//        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
//        return response()->json($data);//then sent this data to ajax success
    }

    public function exportDetalle(Request $request, $date)
    {

        if ($date != null && $date != '' && strtotime($date)) {


            $aux = DB::table('articulo as a')
                ->join('detalle_venta as dv', 'dv.idarticulo', '=', 'a.idarticulo')
                ->join('venta as v', 'v.idventa', '=', 'dv.idventa')
                ->select('a.nombre', 'dv.precio_venta', 'v.fecha_hora', DB::raw('SUM(dv.cantidad) AS cantidad'), DB::raw('SUM(dv.precio_venta*dv.cantidad) AS precio_total'))
                ->where('v.fecha_hora', '<', $date)
                ->groupBy('a.nombre', 'dv.precio_venta', 'v.fecha_hora')
                ->orderBy('v.fecha_hora', 'desc')
                ->get();


        } else {
            $pieces = explode(" - ", $date);
            $aux = DB::table('articulo as a')
                ->join('detalle_venta as dv', 'dv.idarticulo', '=', 'a.idarticulo')
                ->join('venta as v', 'v.idventa', '=', 'dv.idventa')
                ->select('a.nombre', 'dv.precio_venta', 'v.fecha_hora', DB::raw('SUM(dv.cantidad) AS cantidad'), DB::raw('SUM(dv.precio_venta*dv.cantidad) AS precio_total'))
                ->whereBetween('v.fecha_hora', array(new Carbon($pieces[0]), new Carbon($pieces[1])))
                ->groupBy('a.nombre', 'dv.precio_venta', 'v.fecha_hora')
                ->orderBy('v.fecha_hora', 'desc')
                ->get();
        }

        $columna = [];
        $cont2 = 1;
        $total = 0;
        $fila0 = [];
        $fila0[0] = 'Nombre';
        $fila0[1] = 'Precio Venta';
        $fila0[2] = 'Cantidad';
        $fila0[3] = 'Precio total';
        $fila0[4] = 'Fecha';
        $columna[0] = $fila0;

        foreach ($aux as $a) {
            $fila = [];

            $fila[0] = $a->nombre;
            $fila[1] = $a->precio_venta;
            $fila[2] = $a->cantidad;
            $fila[3] = $a->precio_total;
            $fila[4] = $a->fecha_hora;
            $total = $total + $fila[3];
            $columna[$cont2] = $fila;
            $cont2 = $cont2 + 1;
        }
        $filanueva = [];
        $filanueva[0] = ' ';
        $filanueva[1] = ' ';
        $filanueva[2] = ' ';
        $filanueva[3] = $total;
        $filanueva[4] = ' ';
        $columna[$cont2] = $filanueva;

        Excel::create('Laravel Excel', function ($excel) use ($columna) {

            $excel->sheet('Excel sheet', function ($sheet) use ($columna) {

                $sheet->row(1, ['Nombre', 'Precio venta', 'Cantidad', 'Precio total']);
                $sheet->fromArray($columna, null, 'A1', false, false);

            });

        })->download('xls');
    }

    public function exportResultado(Request $request, $date)
    {

        if ($date != null && $date != '' && strtotime($date)) {
            $aux = DB::table('venta as v')
                ->join('persona as p', 'p.idpersona', '=', 'v.idcliente')
                ->select('v.fecha_hora', 'p.nombre', 'v.total_venta')
                ->where('v.fecha_hora', '<', $date)
                ->get();
        } else {
            $pieces = explode(" - ", $date);
            $aux = DB::table('venta as v')
                ->join('persona as p', 'p.idpersona', '=', 'v.idcliente')
                ->select('v.fecha_hora', 'p.nombre', 'v.total_venta')
                ->whereBetween('v.fecha_hora', array(new Carbon($pieces[0]), new Carbon($pieces[1])))
                ->get();
        }

        $columna = [];
        $cont2 = 1;
        $total = 0;
        $fila0 = [];
        $fila0[0] = 'Fecha';
        $fila0[1] = 'Cliente';
        $fila0[2] = 'Total venta';
        $columna[0] = $fila0;

        foreach ($aux as $a) {
            $fila = [];

            $fila[0] = $a->fecha_hora;
            $fila[1] = $a->nombre;
            $fila[2] = $a->total_venta;
            $total = $total + $fila[2];
            $columna[$cont2] = $fila;
            $cont2 = $cont2 + 1;
        }
        $filanueva = [];
        $filanueva[0] = ' ';
        $filanueva[1] = ' ';
        $filanueva[2] = $total;
        $columna[$cont2] = $filanueva;

        Excel::create('Laravel Excel', function ($excel) use ($columna) {

            $excel->sheet('Excel sheet', function ($sheet) use ($columna) {

                $sheet->row(1, ['Fecha', 'Cliente', 'Total']);
                $sheet->fromArray($columna, null, 'A1', false, false);

            });

        })->download('xls');
    }

    public function cajaDelDia(Request $request)
    {


        $mytime = Carbon::now('America/Argentina/Buenos_Aires');
        $mytime2 = Carbon::now('America/Argentina/Buenos_Aires');
        $mytime2->hour = 0;
        $mytime2->minute = 0;
        $mytime2->second = 0;
        $yesterday = $mytime2->toDateTimeString();
        $today = $mytime->toDateTimeString();


        $aux = DB::table('articulo as a')
            ->join('detalle_venta as dv', 'dv.idarticulo', '=', 'a.idarticulo')
            ->join('venta as v', 'v.idventa', '=', 'dv.idventa')
            ->select('a.nombre','a.idcategoria', 'dv.precio_venta', 'v.fecha_hora', DB::raw('SUM(dv.cantidad) AS cantidad'), DB::raw('SUM(dv.precio_venta*dv.cantidad) AS precio_total'))
            ->whereBetween('v.fecha_hora', array($yesterday, $today))
            ->groupBy('a.nombre','a.idcategoria', 'dv.precio_venta', 'v.fecha_hora')
            ->orderBy('a.idcategoria', 'asc')
            ->orderBy('v.fecha_hora', 'desc')
            ->get();

        $columna = [];
        $cont2 = 1;
        $total = 0;
        $subtotales = [0,0,0,0,0,0,0];
        $fila0 = [];
        $fila0[0] = 'Nombre';
        $fila0[1] = 'Precio Venta';
        $fila0[2] = 'Cantidad';
        $fila0[3] = 'Precio total';
        $fila0[4] = 'Fecha';
        $fila0[5] = 'Categoria';
        $columna[0] = $fila0;

        foreach ($aux as $a) {
            $fila = [];

            $fila[0] = $a->nombre;
            $fila[1] = $a->precio_venta;
            $fila[2] = $a->cantidad;
            $fila[3] = $a->precio_total;
            $fila[4] = $a->fecha_hora;
            $fila[5] = $a->idcategoria;
            $subtotales[$a->idcategoria] = $subtotales[$a->idcategoria] + $fila[3];
            $total = $total + $fila[3];
            $columna[$cont2] = $fila;
            $cont2 = $cont2 + 1;
        }
        $filanueva = [];
        $filanueva[0] = ' ';
        $filanueva[1] = ' ';
        $filanueva[2] = ' ';
        $filanueva[3] = $total;
        $filanueva[4] = ' ';
        $filanueva[5] = ' ';
        $columna[$cont2] = $filanueva;

        Excel::create('Laravel Excel', function ($excel) use ($columna, $subtotales) {

            $excel->sheet('Excel sheet', function ($sheet) use ($columna, $subtotales) {
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

                $sheet->setBorder('A1:F'.$row, 'thin');
                $row = $row + 3;

                $sheet->row($row, [' ','Subtotales']);
                $sheet->row($row+1, ['Kiosco',$subtotales[1]]);
                $sheet->row($row+2, ['Panificacion',$subtotales[2]]);
                $sheet->row($row+3, ['Comida',$subtotales[3]]);
                $sheet->row($row+4, ['Arqueo',$subtotales[4]]);
                $sheet->row($row+5, ['Lacteos',$subtotales[5]]);
                $sheet->row($row+6, ['Bebidas',$subtotales[6]]);
                $sheet->row($row+8, ['Total',$columna[$i][3]]);


//                dd($subtotales);
            });

        })->download('xls');
    }
}
