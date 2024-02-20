<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControladorCliente extends Controller
{
    public function selecciona_cliente(Request $request)
    {
        session(['cliente' => $request->id]);
        return view('partials.botones_cliente');
    }
}
