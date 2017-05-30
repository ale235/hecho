<?php

namespace ideas\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use ideas\Http\Requests;
use ideas\Http\Requests\VentaFormRequest;
use Illuminate\Support\Facades\Input;
use ideas\Precio;
use Carbon\Carbon;
use ideas\Articulo;
use DB;

use Illuminate\Http\Request;

class PrecioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $proveedores=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();

        return view('precios.actualizar.index',['proveedores'=>$proveedores]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            if($request->get('pidarticulo') != null && $request->get('pidarticulo') != ''){
                $idarticulo = $request->get('pidarticulo');
                $porcentaje = $request->get('pporcentaje_venta');
                $mytime= Carbon::now('America/Argentina/Buenos_Aires');

                $ultimoprecio = DB::table('precio')
                    ->where('idarticulo','=', $idarticulo)
                    ->orderBy('idarticulo','desc')
                    ->orderBy('idprecio','desc')
                    ->first()
                    ->precio_compra;
                $precio = new Precio();
                $precio->idarticulo = $idarticulo;
                $precio->porcentaje = $porcentaje;
                $precio->fecha = $mytime->toDateTimeString();
                $precio->precio_compra = $ultimoprecio;
                $precio->precio_venta = (($porcentaje / 100) + 1) * $ultimoprecio;
                $precio->save();
                $articulo = Articulo::findOrFail($idarticulo);
                $articulo->ultimoprecio = $precio->precio_venta;
                $articulo->update();
            }
            else{
                $cont = 0;
                $idarticulo = $request->get('idarticulo');
                $porcentaje = $request->get('nuevo_porcentaje');
                $mytime= Carbon::now('America/Argentina/Buenos_Aires');
                while($cont < count($idarticulo)){
                    $ultimoprecio = DB::table('precio')
                        ->where('idarticulo','=', $idarticulo[$cont])
                        ->orderBy('idarticulo','desc')
                        ->orderBy('idprecio','desc')
                        ->first();
                if($porcentaje[$cont] != $ultimoprecio->porcentaje){
                    $precio = new Precio();
                    $precio->idarticulo = $idarticulo[$cont];
                    $precio->porcentaje = $porcentaje[$cont];
                    $precio->fecha = $mytime->toDateTimeString();
                    $precio->precio_compra = $ultimoprecio->precio_compra;
                    $precio->precio_venta = (($porcentaje[$cont] / 100) + 1) * $ultimoprecio->precio_compra;
                    $precio->save();
                    $articulo = Articulo::findOrFail($idarticulo[$cont]);
                    $articulo->ultimoprecio = $precio->precio_venta;
                    $articulo->update();
                }
                $cont= $cont+1;
                }
            }


//            $precio = new Precio;



            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }

        return Redirect::to('precios/actualizar');
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(PrecioFormRequest $request,$id)
    {

    }

    public function destroy($id)
    {

    }

    public function editarEstado(PrecioFormRequest $request,$id)
    {


    }

    public function buscarArticuloPorProveedor(Request $request){


        //if our chosen id and products table prod_cat_id col match the get first 100 data

        //$request->id here is the id of our chosen option id
        $data= DB::table('articulo as art')->select('art.idarticulo','art.nombre','art.codigo')->where('art.proveedor','=',$request->codigo)->get();
        //$data= DB::table('articulo as art')->join('persona as p', 'p.codigo' , '=', 'art.proveedor')->select('art.idarticulo','art.nombre','art.codigo','id.persona')->where('p.codigo','=',$request->codigo)->get();

        // $data= DB::table('articulo as art')->where('idarticulo','=',$request->id);
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($data);//then sent this data to ajax success
    }

    public function buscarPrecioArticulo(Request $request){


        //if our chosen id and products table prod_cat_id col match the get first 100 data
        //$request->id here is the id of our chosen option id
        $data= DB::table('precio as p')
            ->select('p.porcentaje')
            ->where('p.idarticulo','=',$request->id)
            ->orderBy('idarticulo','desc')
            ->orderBy('idprecio','desc')
            ->get();
        //$data= DB::table('articulo as art')->join('persona as p', 'p.codigo' , '=', 'art.proveedor')->select('art.idarticulo','art.nombre','art.codigo','id.persona')->where('p.codigo','=',$request->codigo)->get();
        $data = $data->unique('idarticulo');
        // $data= DB::table('articulo as art')->where('idarticulo','=',$request->id);
        //$data=Product::select('productname','id')->where('prod_cat_id',$request->id)->take(100)->get();
        return response()->json($data);//then sent this data to ajax success
    }

    public function buscarArticuloPorPrecioYPorProveedor(Request $request){


        //if our chosen id and products table prod_cat_id col match the get first 100 data

        $data = DB::table('precio as p')->join('articulo as art', 'p.idarticulo', '=', 'art.idarticulo')
            ->select(DB::raw('max(p.idprecio) as elprecio'),'p.idprecio','art.idarticulo','p.fecha','p.precio_venta','art.codigo','art.nombre','p.porcentaje','p.precio_compra')
            ->where('art.proveedor','=',$request->codigo)
            ->groupBy('p.idprecio','art.idarticulo','p.fecha','p.precio_venta','art.codigo','art.nombre','p.porcentaje','p.precio_compra')
            ->orderBy('p.idprecio','desc')
            ->get();

        return response()->json($data);//then sent this data to ajax success
    }


}
