<?php

namespace App\Http\Controllers;

use App\Sql\StoredProcedures;
use Illuminate\Http\Request;

class ControladorPosicionesResumen extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return "caca";
        $data["lista_cosechas"] = array_reverse(StoredProcedures::lista_cosechas());
        $data['lista_productos'] = StoredProcedures::lista_productos();
        $data["sql"] = [];
        $data["request"] = $request;
        if ($request->header('HX-Request')) {
            return response()->view('posiciones_resumen.posiciones', $data)->header('HX-push-url', "/posiciones_resumen");
        } else return response()->view('posiciones_resumen.consulta', $data)->header('HX-push-url', "/posiciones_resumen");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cereales = [];
        $data['lista_productos'] = StoredProcedures::lista_productos();
        foreach ($data['lista_productos'] as $producto) {
            $sql = StoredProcedures::posiciones($request->cosecha, $producto->id_producto, $request->cliente);
            if (count($sql)) {
                // return response(dump($producto->nombre, $sql), 200);
                if (!isset($cereales[trim($producto->nombre)])) {
                    $cereales[trim($producto->nombre)]['TotTonsCompra'] = 0;
                    $cereales[trim($producto->nombre)]['TotPondCompra'] = 0;
                    $cereales[trim($producto->nombre)]['TotTonsVenta'] = 0;
                    $cereales[trim($producto->nombre)]['TotPondVenta'] = 0;
                }
                foreach ($sql as $datosPuerto) {
                    $cereales[trim($producto->nombre)]['TotTonsCompra'] += $datosPuerto['TonsCompra'] ?? 0;
                    $cereales[trim($producto->nombre)]['TotPondCompra'] += $datosPuerto['TonsCompraPonderado'] ?? 0;
                    $cereales[trim($producto->nombre)]['TotTonsVenta'] += $datosPuerto['TonsVenta'] ?? 0;
                    $cereales[trim($producto->nombre)]['TotPondVenta'] += $datosPuerto['TonsVentaPonderado'] ?? 0;
                }
            }
        }

        // return response(dump($cereales), 200);

        $data["request"] = $request;
        $data["sql"] = $cereales;

        // $data = array_merge($data, $this->data($data));
        return response()
            ->view('posiciones_resumen.resultado', $data)
            ->header('HX-push-url', "/posiciones_resumen");
    }
}
