<?php

namespace App\Http\Controllers;

use App\Sql\StoredProcedures;
use Illuminate\Http\Request;

class ControladorPosiciones extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data["lista_productos"] = array_reverse(StoredProcedures::lista_productos());
        $data["lista_cosechas"] = array_reverse(StoredProcedures::lista_cosechas());
        $data["sql"] = [];
        $data["request"] = $request;
        if ($request->header('HX-Request')) {
            return response()->view('posiciones.posiciones', $data)->header('HX-push-url', "/posiciones");
        } else return response()->view('posiciones.consulta', $data)->header('HX-push-url', "/posiciones");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data["request"] = $request;
        $data['sql'] = StoredProcedures::posiciones($request->cosecha, $request->producto, $request->cliente);

        $data = array_merge($data, $this->data($data));
        return response()
            ->view('posiciones.resultado', $data)
            ->header('HX-push-url', "/posiciones");
    }

    public function consulta(Request $request)
    {
        $data["lista_productos"] = array_reverse(StoredProcedures::lista_productos());
        $data["lista_cosechas"] = array_reverse(StoredProcedures::lista_cosechas());
        $data["request"] = $request;
        $data['sql'] = StoredProcedures::posiciones($request->cosecha, $request->producto, $request->cliente);

        $data = array_merge($data, $this->data($data));
        return response()
            ->view('posiciones.consulta', $data);
        //
    }

    private function data(array $data)
    {
        $totTnsC = array_column($data["sql"], 'TonsCompraPonderado');
        $totTnsCxPrecio = array_column($data["sql"], 'CompraxPrecioPonderado');
        $valores["totTnsC"] = array_sum($totTnsC);
        $totTnsCxPrecio = array_sum($totTnsCxPrecio);
        $valores["totPondC"] = $valores["totTnsC"] == 0 ? 0 : $totTnsCxPrecio / $valores["totTnsC"];

        $totTnsV = array_column($data["sql"], 'TonsVentaPonderado');
        $totTnsVxPrecio = array_column($data["sql"], 'VentaxPrecioPonderado');
        $valores["totTnsV"] = array_sum($totTnsV);
        $totTnsVxPrecio = array_sum($totTnsVxPrecio);
        $valores["totPondV"] = $valores["totTnsV"] == 0 ? 0 : $totTnsVxPrecio / $valores["totTnsV"];

        return $valores;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
