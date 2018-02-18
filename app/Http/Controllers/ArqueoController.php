<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use ideas\Arqueo;
use Illuminate\Support\Facades\Redirect;

class ArqueoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

            if($request->get('daterange') == null){
                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                $arqueo = DB::table('arqueo')
                    ->whereDay('fecha',$mytime->day)
                    ->whereMonth('fecha',$mytime->month)
                    ->whereYear('fecha',$mytime->year)
                    ->get();
                $total = DB::table('arqueo')
                    ->whereDay('fecha',$mytime->day)
                    ->whereMonth('fecha',$mytime->month)
                    ->whereYear('fecha',$mytime->year)
                    ->sum('monto');
                return view('arqueo.index', ['arqueos' => $arqueo, 'total' => $total]);

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

    public function autocompleteArqueo(Request $request)
    {
        $data = Arqueo::select('descripcion')
            ->where('descripcion','LIKE','%'.$request->get('query').'%')
            ->distinct()
            ->get();
        return response()->json($data);
    }

}
