<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use ideas\Http\Requests;
use ideas\Http\Requests\IngresoFormRequest;
use Illuminate\Support\Facades\Input;
use ideas\Ingreso;
use ideas\DetalleIngreso;
use ideas\Precio;
use ideas\Articulo;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request)
        {
            $query = trim($request->get('searchText'));
            $ingresos = DB::table('ingreso as i')
                ->join('persona as p', 'i.idproveedor' , '=', 'p.idpersona')
                ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
                ->select('i.idingreso','i.fecha_hora','p.codigo','i.tipo_comprobante','i.serie_comprobante','i.numero_comprobante', 'i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra_costo) as total'))
                ->orderBy('i.idingreso','desc')
                ->groupBy('i.idingreso','i.fecha_hora','p.codigo','i.tipo_comprobante','i.serie_comprobante','i.numero_comprobante', 'i.impuesto','i.estado')
                ->paginate(7);
            return view('compras.ingreso.index', ['ingresos'=>$ingresos,'searchText'=>$query]);
        }
    }

    public function create()
    {
        $personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
        $articulos=DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) AS articulo'), 'art.idarticulo')
            ->where('art.estado', '=', 'Activo')
            ->get();
        return view('compras.ingreso.create', ['personas'=>$personas, 'articulos'=>$articulos]);
    }

    public function store(IngresoFormRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $ingreso = new Ingreso;
//            $pieces = explode("+", $request->get('idproveedor'));
//            $ingreso->idproveedor = $pieces[0];
            $ingreso->idproveedor = $request->get('pidproveedor');
            $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
            $ingreso->serie_comprobante=$request->get('serie_comprobante');
            $ingreso->numero_comprobante=$request->get('numero_comprobante');
            $mytime= Carbon::now('America/Argentina/Buenos_Aires');

            $ingreso->fecha_hora=$mytime->toDateTimeString();
            $ingreso->estado='Activo';
            $ingreso->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_compra_costo = $request->get('precio_compra_costo');
            $porcentaje_venta = $request->get('porcentaje_venta');

            $cont = 0;

            while($cont < count($idarticulo)){
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_compra_costo = $precio_compra_costo[$cont];
                $detalle->porcentaje_venta = $porcentaje_venta[$cont];
                $detalle->save();

                $precio = new Precio();
                $precio->idarticulo = $idarticulo[$cont];
                $precio->porcentaje = $porcentaje_venta[$cont];
                $precio->fecha = $mytime->toDateTimeString();
                $precio->precio_compra = $precio_compra_costo[$cont];
                $precio->precio_venta = (($porcentaje_venta[$cont] / 100) + 1) * $precio_compra_costo[$cont];
                $precio->save();

                $articulo = Articulo::findOrFail($idarticulo[$cont]);
                $articulo->ultimoprecio = (($porcentaje_venta[$cont] / 100) + 1) * $precio_compra_costo[$cont];
                $articulo->stock = $articulo->stock + $cantidad[$cont];
                $articulo->update();

                $cont= $cont+1;
            }
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }

        return Redirect::to('compras/ingreso');
    }

    public function show($id)
    {
        $ingreso =  DB::table('ingreso as i')
                ->join('persona as p', 'i.idproveedor' , '=', 'p.idpersona')
                ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
                ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.numero_comprobante', 'i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra_costo) as total'))
                ->where('i.idingreso', '=', $id)
                ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.numero_comprobante', 'i.impuesto','i.estado')
                ->first();

        $detalles =  DB::table('detalle_ingreso as d')
                    ->join('articulo as a','d.idarticulo', '=', 'a.idarticulo')
                    ->select('a.nombre as articulo','d.cantidad', 'd.precio_compra_costo', 'd.porcentaje_venta')
                    ->where('d.idingreso','=', $id)->get();


        return view('compras.ingreso.show',['ingreso'=>$ingreso, 'detalles'=>$detalles]);
    }

    public function destroy($id)
    {
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->estado = 'Cancelado';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }

    public function buscarArticuloPorProveedorEnIngreso(Request $request){


        //if our chosen id and products table prod_cat_id col match the get first 100 data

       $data= DB::table('articulo as art')->join('persona as p','p.codigo','=','art.proveedor')->select('art.nombre','art.idarticulo')->where('p.codigo','=',$request->codigo)->get();
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($data);//then sent this data to ajax success


    }

    public function edit($id)
    {
        $ingreso=Ingreso::findOrFail($id);
        $detalles= DB::table('detalle_ingreso')->where('idingreso','=',$id)->get();
        $persona=DB::table('persona')->where('idpersona','=',$ingreso->idproveedor)->get();
        $articulos=DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) AS articulo'), 'art.idarticulo')
            ->where('art.estado', '=', 'Activo')
            ->get();

        return view('compras.ingreso.edit',['ingreso'=>$ingreso, 'persona'=>$persona, 'articulos'=>$articulos, 'detalles'=>$detalles]);
    }

    public function update(IngresoFormRequest $request,$id)
    {
//        try
//        {
//            DB::beginTransaction();
            DB::table('detalle_ingreso')->where('idingreso', $id)->delete();

            $ingreso=Ingreso::findOrFail($id);
            $idarticulo = $request->get('idarticulo');
            $proveedor =  DB::table('articulo as a')->join('persona as p' , 'p.codigo' , '=', 'a.proveedor')->where('a.idarticulo','=', $idarticulo[0])->distinct('a.proveedor')->get();
            $ingreso->idproveedor = $proveedor[0]->idpersona;
            $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
            $ingreso->serie_comprobante=$request->get('serie_comprobante');
            $ingreso->numero_comprobante=$request->get('numero_comprobante');
            $mytime= Carbon::now('America/Argentina/Buenos_Aires');

            $ingreso->fecha_hora=$mytime->toDateTimeString();
            $ingreso->estado='Activo';
            $ingreso->save();



            $cantidad = $request->get('cantidad');
            $precio_compra_costo = $request->get('precio_compra_costo');
            $porcentaje_venta = $request->get('porcentaje_venta');

            $cont = 0;

            while($cont < count($idarticulo)){
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_compra_costo = $precio_compra_costo[$cont];
                $detalle->porcentaje_venta = $porcentaje_venta[$cont];
                $detalle->save();

                $precio = new Precio();
                $precio->idarticulo = $idarticulo[$cont];
                $precio->porcentaje = $porcentaje_venta[$cont];
                $precio->fecha = $mytime->toDateTimeString();
                $precio->precio_compra = $precio_compra_costo[$cont];
                $precio->precio_venta = (($porcentaje_venta[$cont] / 100) + 1) * $precio_compra_costo[$cont];
                $precio->save();

                $articulo = Articulo::findOrFail($idarticulo[$cont]);
                $articulo->ultimoprecio = (($porcentaje_venta[$cont] / 100) + 1) * $precio_compra_costo[$cont];
                $articulo->update();

                $cont= $cont+1;
            }
//            DB::commit();
//        }
//        catch(\Exception $e)
//        {
//            DB::rollback();
//        }

        return Redirect::to('compras/ingreso');
    }

    public function buscarPrecioArticuloIngresosPorCodigo(Request $request){


        //if our chosen id and products table prod_cat_id col match the get first 100 data
        //$request->id here is the id of our chosen option id
        $articulo = DB::table('articulo as art')
            ->select('art.nombre','p.idpersona','art.idarticulo')
            ->join('persona as p','p.codigo','=','art.proveedor')
            ->where('art.codigo','=',$request->codigo)
            ->orwhere('art.barcode','=',$request->codigo)
            ->first();

//        $precio= DB::table('precio')
//            ->where('idarticulo','=',$articulo->idarticulo)
//            ->orderBy('idarticulo','desc')
//            ->orderBy('idprecio','desc')
//            ->get();
//        $precio = $precio->unique('idarticulo');
        //$elprecio = DB::table('articulo')join('precio','idarticulo','=',$precio->idarticulo)->get();
        //$data= DB::table('articulo as art')->join('persona as p', 'p.codigo' , '=', 'art.proveedor')->select('art.idarticulo','art.nombre','art.codigo','id.persona')->where('p.codigo','=',$request->codigo)->get();
//        $precio = $precio->merge($articulo);

        // $data= DB::table('articulo as art')->where('idarticulo','=',$request->id);
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($articulo);//then sent this data to ajax success

//        $request->id here is the id of our chosen option id
//        $data=DB::table('articulo as art')->select('idarticulo','nombre')->get();
//        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
//        return response()->json($data);//then sent this data to ajax success
    }

    public function buscarArticuloParaIngreso (Request $request) {

        $articulo = DB::table('articulo as art')
            ->select('art.nombre','p.idpersona','art.idarticulo','art.codigo')
            ->join('persona as p','p.codigo','=','art.proveedor')
            ->where('art.idarticulo','=',$request->id)->first();

        return response()->json($articulo);
    }

}
