<?php

namespace ideas\Http\Controllers;

use Illuminate\Http\Request;

class PagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

            return view('pagos.index');
    }
}
