<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use ideas\Pagos;
use Illuminate\Support\Facades\Redirect;

class PagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

            if($request->get('daterange') == null){
                $mytime = Carbon::now('America/Argentina/Buenos_Aires');
                $pagos = DB::table('pagos')
                    ->whereDay('fecha',$mytime->day)
                    ->whereMonth('fecha',$mytime->month)
                    ->whereYear('fecha',$mytime->year)
                    ->get();
                $total = DB::table('pagos')
                    ->whereDay('fecha',$mytime->day)
                    ->whereMonth('fecha',$mytime->month)
                    ->whereYear('fecha',$mytime->year)
                    ->sum('monto');
                return view('pagos.index', ['pagos' => $pagos, 'total' => $total]);

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
                $pagos = DB::table('pagos')
                    ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                    ->get();

                $total = DB::table('pagos')
                    ->whereBetween('fecha', [$pieces[0],$pieces[1]])
                    ->sum('monto');

                return view('pagos.index', ['pagos' => $pagos, 'total' => $total]);
        }
    }

    public function create(Request $request)
    {
        if($request){
            return view('pagos.create');
        }
    }

    public function store(Request $request)
    {
        if($request){
            $mytime = Carbon::now('America/Argentina/Buenos_Aires');
            //dd($request);
            $pago = new Pagos;
            $pago->fecha = $request->get('daterange');
            $pago->descripcion = $request->get('descripcion');
            $pago->monto = $request->get('monto');
            $pago->save();
//            $total = DB::table('pagos')->sum('monto');
            return Redirect::to('pagos');
        }
    }

    public function edit($id)
    {
        $pago = Pagos::findOrFail($id);
        return view('pagos.edit',['pago'=> $pago]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $pago = Pagos::findOrFail($id);
            $pago->fecha = $request->get('daterange');
            $pago->descripcion = $request->get('descripcion');
            $pago->monto = $request->get('monto');
            $pago->update();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return Redirect::to('pagos');
    }

    public function show($id)
    {
        return view('ventas.venta.show');
    }

    public function destroy($id)
    {
        $pagos = Pagos::findOrFail($id);
        $pagos->delete();
        return Redirect::to('pagos');
    }

    public function autocomplete(Request $request)
    {
        $data = Pagos::select('descripcion')
            ->where('descripcion','LIKE','%'.$request->get('query').'%')
            ->get();
        return response()->json($data);
    }
}
