<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use ideas\Categoria;
use Illuminate\Support\Facades\Redirect;
use ideas\Http\Requests\CategoriaFormRequest;
use DB;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if($request)
        {
            $condiciones = DB::table('categoria')
                ->distinct()->select('condicion')->get();

            $query = trim($request->get('searchText'));
            $query2 = $request->get('select-categoria');
            if($query2== null){
                $query2 = 1;
            }
            $categorias = DB::table('categoria as cat')
                ->where('nombre','LIKE','%'.$query.'%')
                ->where('condicion','=',$query2)
                ->orderBy('idcategoria','desc')
                ->paginate('3');
            return view('almacen.categoria.index', ['categorias'=>$categorias,'searchText'=>$query,'select-categoria'=>$query2,'condiciones'=>$condiciones]);
        }
    }

    public function create()
    {
        return view('almacen.categoria.create');
    }

    public function store(CategoriaFormRequest $request)
    {
        echo $request;
        $categoria = new Categoria;
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->condicion = 1;
        $categoria->save();
        if($request->get('lastPage')){
            return Redirect::to('almacen/articulo/create');
        }
        else{
            return Redirect::to('almacen/categoria');
        }

    }

    public function show($id)
    {
        return view('almacen.categoria.show',['categoria'=>Categoria::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('almacen.categoria.edit',['categoria'=>Categoria::findOrFail($id)]);
    }

    public function update(CategoriaFormRequest $request,$id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = 0;
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }

    public function editarEstado($id)
    {
        $categoria = Categoria::findOrFail($id);
        if($categoria->condicion ==  1)
        $categoria->condicion = 0;
        else $categoria->condicion = 1;
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }

}

