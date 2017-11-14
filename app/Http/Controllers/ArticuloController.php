<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use ideas\Http\Requests\ArticuloFormRequest;
use ideas\Articulo;
use ideas\Persona;
use ideas\Precio;
use ideas\Ingreso;
use ideas\DetalleIngreso;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class ArticuloController extends Controller
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
            $query2 = trim($request->get('searchText2'));
            $categorias=DB::table('categoria')
                ->where('condicion','=','1')
                ->get();
            $estados = DB::table('articulo')
                ->select('estado')
                ->distinct('estado')->get();

            if($request->get('selectText')== null ){
                $query3 = 'Activo';
            }
            else $query3 = trim($request->get('selectText'));
            $articulos = DB::table('articulo as art')
                ->join('categoria as cat', 'art.idcategoria', '=', 'cat.idcategoria')
                ->select('art.idarticulo','art.nombre', 'art.codigo', 'art.stock', 'cat.nombre as categoria', 'art.descripcion', 'art.imagen', 'art.estado', 'art.proveedor','art.ultimoprecio', 'art.barcode')
                ->where('art.estado','=',$query3)
                ->where([
                    ['art.nombre','LIKE','%'.$query.'%'],
                    ['art.barcode','LIKE','%'.$query2.'%'],
                    ['art.estado', '=', $query3],
                ])
                ->orderBy('art.idarticulo','desc')
                ->paginate('30');

            return view('almacen.articulo.index', ['articulos'=>$articulos,'searchText'=>$query, 'searchText2'=>$query2, 'estados'=>$estados, 'selectText'=>$query3, 'categorias'=>$categorias]);
        }
    }

    public function create()
    {
        $articulos = DB::table('articulo as art')
            ->select('art.idarticulo','art.codigo','art.proveedor')
            ->get();


        $proveedores = DB::table('persona')
            ->where('tipo_persona','=','Proveedor')
            ->where('estado','=','Activo')
            ->get();
        $categorias=DB::table('categoria')
            ->where('condicion','=','1')
            ->get();
        return view('almacen.articulo.create', ['categorias'=>$categorias, 'proveedores'=>$proveedores, 'articulos'=>$articulos]);
    }

    public function store(ArticuloFormRequest $request)
    {
        try
        {
        DB::beginTransaction();
        $articulo = new Articulo;
            if(count(Articulo::where('barcode',$request->get('barcode'))->first())==1){
                $articulo = Articulo::where('barcode','=',$request->get('barcode'))->firstOrFail();
                $articulo->idcategoria = $request->get('idcategoria');
                //$articulo->codigo =$request->get('proveedor');
                $articulo->proveedor = $request->get('codigo');
                $articulo->nombre = $request->get('nombre');
                $articulo->descripcion = $request->get('descripcion');
                $articulo->estado = 'Activo';

                $articulo->save();
                //$articulo->stock=$articulo->stock + $request->get('pcantidad');
                //dd($articulo);

                $ingreso = new Ingreso;
                $ingreso->idproveedor = $request->get('idproveedorsolo');
                $mytime= Carbon::now('America/Argentina/Buenos_Aires');

                $ingreso->fecha_hora=$mytime->toDateTimeString();
                $ingreso->estado='Activo';
                $ingreso->save();

                $idarticulo = $articulo->idarticulo;
                $cantidad = $request->get('pcantidad');
                $precio_compra_costo = $request->get('pprecio_compra_costo');
                $porcentaje_venta = $request->get('pporcentaje_venta');

                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;
                $detalle->idarticulo = $idarticulo;
                $detalle->cantidad = $cantidad;
                $detalle->precio_compra_costo = $precio_compra_costo;
                $detalle->porcentaje_venta = $porcentaje_venta;
                $detalle->save();

                $precio = new Precio();
                $precio->idarticulo = $idarticulo;
                $precio->porcentaje = $porcentaje_venta;
                $precio->fecha = $mytime->toDateTimeString();
                $precio->precio_compra = $precio_compra_costo;
                $precio->precio_venta = (($porcentaje_venta / 100) + 1) * $precio_compra_costo;
                $precio->save();

                //$articulo = Articulo::findOrFail($idarticulo);
                $articulo->ultimoprecio = (($porcentaje_venta / 100) + 1) * $precio_compra_costo;
                $articulo->stock = $articulo->stock + $cantidad;
                $articulo->update();
            }
            else{
                $ultimo =Articulo::orderBy('idarticulo','desc')->first();
                $articulo->idcategoria = $request->get('idcategoria');
                //dd($request);
                $numero = Articulo::where('proveedor',$request->get('idproveedores'))->orderBy('codigo','desc')->first();
                if($numero){
                    $ultimoNumeroCodigo = substr($numero->codigo, -5);
                    $ultimoNumeroCodigo = (int)$ultimoNumeroCodigo;
                }
                else $ultimoNumeroCodigo = 0;
                //dd($numero);
                $articulo->codigo = $request->get('idproveedores') . str_pad($ultimoNumeroCodigo+1, 5, "0",  STR_PAD_LEFT);
                $articulo->proveedor = $request->get('idproveedores');
                $articulo->nombre = $request->get('nombre');
                $articulo->stock = 0;
                $articulo->descripcion = $request->get('descripcion');
                $articulo->estado = 'Activo';
                if($request->get('barcode')== '')
                    $articulo->barcode = $ultimo->idarticulo + 1;
                else $articulo->barcode = $request->get('barcode');

                if(Input::hasFile('imagen'))
                {
                    $file=Input::file('imagen');
                    $file->move(public_path().'imagenes/articulos/', $file->getClientOriginalName());
                    $articulo->imagen = $file->getClientOriginalName();
                }
                $articulo->save();

                $ingreso = new Ingreso;
//            $pieces = explode("+", $request->get('idproveedor'));
//            $ingreso->idproveedor = $pieces[0];
                $ingreso->idproveedor = $request->get('idproveedorsolo');
                $mytime= Carbon::now('America/Argentina/Buenos_Aires');

                $ingreso->fecha_hora=$mytime->toDateTimeString();
                $ingreso->estado='Activo';
                $ingreso->save();

                $idarticulo = $articulo->idarticulo;
                $cantidad = $request->get('pcantidad');
                $precio_compra_costo = $request->get('pprecio_compra_costo');
                $porcentaje_venta = $request->get('pporcentaje_venta');

                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;
                $detalle->idarticulo = $idarticulo;
                $detalle->cantidad = $cantidad;
                $detalle->precio_compra_costo = $precio_compra_costo;
                $detalle->porcentaje_venta = $porcentaje_venta;
                $detalle->save();

                $precio = new Precio();
                $precio->idarticulo = $idarticulo;
                $precio->porcentaje = $porcentaje_venta;
                $precio->fecha = $mytime->toDateTimeString();
                $precio->precio_compra = $precio_compra_costo;
                $precio->precio_venta = (($porcentaje_venta / 100) + 1) * $precio_compra_costo;
                $precio->save();

                $articulo = Articulo::findOrFail($idarticulo);
                $articulo->ultimoprecio = (($porcentaje_venta / 100) + 1) * $precio_compra_costo;
                $articulo->stock = $articulo->stock + $cantidad;
                $articulo->update();
            }
            DB::commit();
        }
        catch(\Exception $e)
      {
            DB::rollback();
        }
        return Redirect::to('almacen/articulo?selectText=Activo');
    }

    public function show($id)
    {
        return view('almacen.articulo.show',['articulo'=>Articulo::findOrFail($id)]);
    }

    public function edit($id)
    {
        $articulo=Articulo::findOrFail($id);
        $proveedores = DB::table('persona')
            ->where('tipo_persona','=','Proveedor')->get();
        $categorias=DB::table('categoria')
            ->where('condicion', '=', '1')
            ->get();
        return view('almacen.articulo.edit',['articulo'=>$articulo,'categorias'=>$categorias,'proveedores'=>$proveedores]);
    }

    public function update(ArticuloFormRequest $request,$id)
    {
        try
        {
      //      dd($request);
            DB::beginTransaction();
        $articulo = Articulo::findOrFail($id);
        $articulo->idcategoria = $request->get('idcategoria');
        $articulo->codigo = $request->get('codigo');
        $articulo->nombre = $request->get('nombre');
        $articulo->stock = $request->get('pcantidad');;
        $articulo->descripcion = $request->get('descripcion');
        $articulo->barcode = $request->get('barcode');
        $articulo->estado = 'Activo';

        if(Input::hasFile('imagen'))
        {
            $file=Input::file('imagen');
            $file->move(public_path().'imagenes/articulos/', $file->getClientOriginalName());
            $articulo->imagen = $file->getClientOriginalName();
        }
        $articulo->update();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        return Redirect::to('almacen/articulo');
    }

    public function destroy($id)
    {

        $articulo = Articulo::findOrFail($id);
        $detalle_ingreso= DetalleIngreso::where('idarticulo',$id)->get();
        foreach ($detalle_ingreso as $di){
            $precios = Precio::where('idarticulo', $di->idarticulo)->get();
            foreach ($precios as $p){
                $p->delete();
            }

            $di->delete();
        }

        $articulo->delete();

        return Redirect::to('almacen/articulo');
    }

    public static  function test()
    {

        return "hola";
    }

    public function prodfunct(){

        $prod=DB::table('articulo as art')->all();//get data from table
        return view('almacen/articulo',compact('articulosnuevo'));//sent data to view

    }

    public function findProductName(Request $request){


        //if our chosen id and products table prod_cat_id col match the get first 100 data

        //$request->id here is the id of our chosen option id
        $data=DB::table('articulo as art')->select('idarticulo','nombre')->get();
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($data);//then sent this data to ajax success
    }


    public function findPrice(Request $request){

        //it will get price if its id match with product id
        //$p=Product::select('price')->where('id',$request->id)->first();
        $p=DB::table('articulo as art')->select('idarticulo','nombre')->where('idarticulo','=',$request->idarticulo)->get();
        return response()->json($p);
    }

    public function cambiarEstadoArticulo($id){

        $articulo = Articulo::findOrFail($id);
        echo $articulo;
        if($articulo->estado ==  'Inactivo')
            $articulo->estado = 'Activo';
        else $articulo->estado = 'Inactivo';

        $articulo->update();
        return Redirect::to('almacen/articulo');
    }


    public function mostrarPrecio($id){

        $precio= DB::table('precio')
            ->where('idarticulo','=',$id)
            ->orderBy('idarticulo','desc')
            ->orderBy('idprecio','desc')
            ->get();
        $precio = $precio->unique('idarticulo');
        return response()->json($precio);
    }


    public function exportArticulo($selectText)
    {
        //$articulos= Articulo::where('estado',$selectText)->get();
        $articulos= Articulo::join('precio','articulo.idarticulo', '=', 'precio.idarticulo')
            ->join('categoria','categoria.idcategoria','=','articulo.idcategoria')
//            ->select('precio.idprecio','articulo.idarticulo','precio.fecha','precio.precio_venta','articulo.codigo','articulo.nombre','precio.porcentaje','precio.precio_compra')
            ->select('articulo.nombre as articulo','articulo.codigo','articulo.stock','categoria.nombre','articulo.estado','precio.precio_venta','precio.fecha as último_precio','precio.porcentaje','precio.precio_compra')
            ->where('articulo.estado',$selectText)
            //->groupBy('precio.idprecio','articulo.idarticulo','precio.fecha','precio.precio_venta','articulo.codigo','articulo.nombre','precio.porcentaje','precio.precio_compra')
            ->orderBy('articulo.idarticulo','desc')
            ->orderBy('precio.idprecio','desc')

            ->get();

        $articulos = $articulos->unique('codigo');

        Excel::create('Laravel Excel', function($excel) use ($articulos) {

            $excel->sheet('Excel sheet', function($sheet) use ($articulos){

               // $sheet->setOrientation('landscape');
                $sheet->fromArray($articulos);

            });

        })->export('xls');
    }

    public function buscarProveedor(Request $request){

        //it will get price if its id match with product id
        //$p=Product::select('price')->where('id',$request->id)->first();
        $p=DB::table('persona as p')->select('p.idpersona','p.codigo')->where('p.codigo','=', $request->codigo)->get();
        return response()->json($p);
    }

    public function buscarUltimoId(Request $request){

        //it will get price if its id match with product id
        //$p=Product::select('price')->where('id',$request->id)->first();
        $p=DB::table('articulo as art')
            ->select('art.codigo')
            ->where('art.proveedor','=', $request->codigo)
            ->orderBy('art.codigo','desc')
            ->first();
        return response()->json($p);
    }

    public function existeArticulo(Request $request){

        //it will get price if its id match with product id
        //$p=Product::select('price')->where('id',$request->id)->first();
        $p=DB::table('articulo as art')
            ->select('art.idarticulo','art.nombre', 'art.codigo', 'art.stock', 'art.idcategoria', 'art.descripcion', 'art.imagen', 'art.estado', 'art.proveedor','art.ultimoprecio', 'art.barcode','p.precio_venta','p.porcentaje','p.precio_compra','person.idpersona','person.codigo')
            ->join('persona as person','art.proveedor','=','person.codigo')
            ->join('precio as p', 'art.idarticulo','=','p.idarticulo')
            ->where('art.barcode','=', $request->barcode)
            ->orderBy('p.idprecio','desc')
            ->first();
        if(count($p)>0){

            return response()->json($p);
        }
       else{
           return response()->json(['error' => 'Error msg'], 404);
       }
    }

}
