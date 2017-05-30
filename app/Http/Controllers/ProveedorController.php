<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use ideas\Persona;
use ideas\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use ideas\Http\Requests\PersonaFormRequest;
use DB;

class ProveedorController extends Controller
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
            $query2 = $request->get('selectText');
            if($query2== null){
                $query2 ='Activo';
            }
            $personas = DB::table('persona')
                ->where('codigo','LIKE','%'.$query.'%')
                ->where('tipo_persona','=','Proveedor')
                ->where('estado','=',$query2)
                ->orderBy('idpersona','desc')
                ->paginate('7');
            return view('compras.proveedor.index', ['personas'=>$personas,'searchText'=>$query, 'selectText'=>$query2]);
        }
    }

    public function create()
    {
        return view('compras.proveedor.create');
    }

    public function store(PersonaFormRequest $request)
    {
        $persona = new Persona;
        $persona->tipo_persona = 'Proveedor';
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request->get('num_documento');
        $persona->codigo = $request->get('codigo');
        $persona->cuitcuil = $request->get('cuitcuil');
        $persona->facebook = $request->get('facebook');
        $persona->instagram = $request->get('instagram');
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->estado = 'Activo';
        $persona->save();
        if($request->get('lastPage')){
            return Redirect::to('almacen/articulo/create');
        }
        else{
            return Redirect::to('compras/proveedor');
        }
    }

    public function show($id)
    {
        return view('compras.proveedor.show',['persona'=>Persona::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('compras.proveedor.edit',['persona'=>Persona::findOrFail($id)]);
    }

    public function update(PersonaFormRequest $request,$id)
    {
        $persona = Persona::findOrFail($id);
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request->get('num_documento');
        $persona->codigo = $request->get('codigo');
        $persona->cuitcuil = $request->get('cuitcuil');
        $persona->facebook = $request->get('facebook');
        $persona->instagram = $request->get('instagram');
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->update();
        return Redirect::to('compras/proveedor');
    }

    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);

        if($persona->estado ==  'Inactivo')
            $persona->estado = 'Activo';
        else $persona->estado = 'Inactivo';
        $persona->update();
        return Redirect::to('compras/proveedor');
    }

    public function cambiarEstado($id){

        $persona = Persona::findOrFail($id);

        if($persona->estado ==  'Inactivo')
            $persona->estado = 'Activo';
        else $persona->estado = 'Inactivo';
        $persona->update();
        return Redirect::to('compras/proveedor');
    }
}
