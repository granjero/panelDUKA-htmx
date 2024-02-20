<?php

namespace App\Sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

use function PHPUnit\Framework\returnSelf;

class StoredProcedures
{

    public static function lista_productos()
    {
        $sql = DB::connection('sqlmercados')->select("stp_lista_productos");
        return $sql;
    }

    public static function lista_cosechas()
    {
        $array = DB::connection('sqlmercados')->select("internet_cosecha_list");
        return $array;
    }

    // el array para posiciones
    public static function posiciones(array $cosecha, string $producto, string $cliente)
    {
        $cliente = $cliente == 8730 ? '1' : '2';
        $sql = array();

        foreach ($cosecha as $cosechaAnio)
        {
            $db =  DB::connection('dbgestion')->select("sp_GetContratosByCosecha '$cosechaAnio', '$producto', '', '$cliente'");
            $sql = array_merge($sql, $db);
        }

        $array = [];
        $rowID = 0;
        foreach ($sql as $objeto) // COMPRA
        {
            if ($objeto->cv == "C") {
                $array[trim($objeto->ZonaPuerto)]['Compra'][] = [
                    'id' => $rowID++,
                    'contrato' => $objeto->contrato,
                    'tns' => floatval($objeto->Tons),
                    'precio' => floatval($objeto->precioC),
                    'mes' => $objeto->plazo,
                    'txp' => (floatval($objeto->Tons) * floatval($objeto->precioC)),
                ];
                $array[trim($objeto->ZonaPuerto)]['TonsCompra'] = ($array[trim($objeto->ZonaPuerto)]['TonsCompra'] ?? 0) + floatval($objeto->Tons);
                $array[trim($objeto->ZonaPuerto)]['CompraxPrecio'] = ($array[trim($objeto->ZonaPuerto)]['CompraxPrecio'] ?? 0) + (floatval($objeto->Tons) * floatval($objeto->precioC));

                if ($objeto->precioC > 0) {
                    $array[trim($objeto->ZonaPuerto)]['TonsCompraPonderado'] = ($array[trim($objeto->ZonaPuerto)]['TonsCompraPonderado'] ?? 0) + floatval($objeto->Tons);
                    $array[trim($objeto->ZonaPuerto)]['CompraxPrecioPonderado'] = ($array[trim($objeto->ZonaPuerto)]['CompraxPrecioPonderado'] ?? 0) + (floatval($objeto->Tons) * floatval($objeto->precioC));
                }
            }
        }

        $rowID = 0;
        foreach ($sql as $objeto) // VENTA
        {
            if ($objeto->cv == "V") {
                $array[trim($objeto->ZonaPuerto)]['Venta'][] = [
                    'id' => $rowID++,
                    'contrato' => $objeto->contrato,
                    'tns' => floatval($objeto->Tons),
                    'precio' => floatval($objeto->precioV),
                    'mes' => $objeto->plazo,
                    'txp' => (floatval($objeto->Tons) * floatval($objeto->precioV)),
                ];
                $array[trim($objeto->ZonaPuerto)]['TonsVenta'] = ($array[trim($objeto->ZonaPuerto)]['TonsVenta'] ?? 0) + floatval($objeto->Tons);
                $array[trim($objeto->ZonaPuerto)]['VentaxPrecio'] = ($array[trim($objeto->ZonaPuerto)]['VentaxPrecio'] ?? 0) + (floatval($objeto->Tons) * floatval($objeto->precioV));

                if ($objeto->precioV > 0) {
                    $array[trim($objeto->ZonaPuerto)]['TonsVentaPonderado'] = ($array[trim($objeto->ZonaPuerto)]['TonsVentaPonderado'] ?? 0) + floatval($objeto->Tons);
                    $array[trim($objeto->ZonaPuerto)]['VentaxPrecioPonderado'] = ($array[trim($objeto->ZonaPuerto)]['VentaxPrecioPonderado'] ?? 0) + (floatval($objeto->Tons) * floatval($objeto->precioV));
                }
            }
        }
        // Totales

        // dd($array);
        return $array;
    }

    public static function dolar()
    {
        $sql = DB::connection('dbgestion')->select("sp_GetListaTipoCambio");
        $dolares = array_reverse($sql);

        $array = [];

        foreach ($dolares as $dolar) {
            $llaveFecha = date('y') - date('y', strtotime($dolar->Fecha));
            $array[$llaveFecha][] = (object) [
                'fecha' =>  date('d-M', strtotime($dolar->Fecha)),
                'venta' => $dolar->Venta,
                'fechaCalendario' => date('Y-m-d', strtotime($dolar->Fecha)),
            ];
            //dd($dolar);
        }

        return $array;
    }
    //
    // public static function contratosSucursales()
    // {
    //     Carbon::setLocale('es_ES');
    //     $sql = [];
    //     $lunesPasado = new Carbon(date('Y-m-d', strtotime("monday last week")));
    //     $viernesPasado = new Carbon(date('Y-m-d', strtotime("friday last week")));
    //     $primerDiaMesPasado = new Carbon(date('Y-m-d', strtotime("first day of last month")));
    //     $ultimoDiaMesPasado = new Carbon(date('Y-m-d', strtotime("last day of last month")));
    //
    //     $contratos['semana'] = DB::connection('dbgestion')->select("sp_GetContratosBySemenaBySucursal '$lunesPasado', '$viernesPasado'");
    //     $contratos['mes'] = DB::connection('dbgestion')->select("sp_GetContratosBySemenaBySucursal '$primerDiaMesPasado', '$ultimoDiaMesPasado'");
    //
    //     // calculo totales
    //     $totales = [];
    //     foreach ($contratos as $periodo => $datos) {
    //         foreach ($datos as $dato) {
    //             $totales[trim($dato->nom_suc)][$periodo]['21'] = ($totales[trim($dato->nom_suc)][$periodo]['21'] ?? 0) + $dato->kilos21;
    //             $totales[trim($dato->nom_suc)][$periodo]['22'] = ($totales[trim($dato->nom_suc)][$periodo]['22'] ?? 0) + $dato->kilos22;
    //             $totales[trim($dato->nom_suc)][$periodo]['23'] = ($totales[trim($dato->nom_suc)][$periodo]['23'] ?? 0) + $dato->kilos23;
    //         }
    //     }
    //
    //     $rowID = 0;
    //     foreach ($contratos as $periodo => $datos) {
    //         foreach ($datos as $dato) {
    //             $sql[trim($dato->nom_suc)][$periodo][] = [
    //                 'id' => $rowID++,
    //                 'periodo' => $periodo == 'mes' ? "Contratos del Mes de " . $primerDiaMesPasado->isoFormat('MMMM') : "Semana del " . $lunesPasado->isoFormat('D MMM') . " al " . $viernesPasado->isoFormat('D MMM'),
    //                 'vendedor' => $dato->nomven,
    //                 'kilos21' => number_format($dato->kilos21, 0, ",", "."),
    //                 'tot21' => number_format($totales[trim($dato->nom_suc)][$periodo]['21'], 0, ",", "."),
    //                 'kilos22' => number_format($dato->kilos22, 0, ",", "."),
    //                 'tot22' => number_format($totales[trim($dato->nom_suc)][$periodo]['22'], 0, ",", "."),
    //                 'kilos23' => number_format($dato->kilos23, 0, ",", "."),
    //                 'tot23' => number_format($totales[trim($dato->nom_suc)][$periodo]['23'], 0, ",", "."),
    //             ];
    //         }
    //     }
    //
    //     return $sql;
    // }
    //
    // public static function contratosSucursalesValoresGraficos()
    // {
    //     $contratos = [];
    //     $semana = (7 * 24 * 60 * 60);
    //     $anio = (365 * 24 * 60 * 60);
    //     $fecha = time() - $anio;
    //     $acumulado = [];
    //     $acumulador = [];
    //     $acumuladorFinal = [];
    //     $sumaPorSemana = [];
    //
    //
    //     for ($i = 0; $i < 52; $i++) {
    //         $contratos[] = DB::connection('dbgestion')->select("sp_GetContratosBySemenaBySucursal '"
    //             . date('Y-m-d', $fecha) . "', '"
    //             . date('Y-m-d', ($fecha + $semana)) . "'");
    //         $fecha += $semana;
    //         // dump("sp_GetContratosBySemenaBySucursal '" . date('Y-m-d', $fecha) . "', '" . date('Y-m-d', ($fecha + $semana)) . "'");
    //     }
    //
    //     // obtengo la lista por sucursal por semana con los contratos de cada semana
    //     foreach ($contratos as $llaveSemana => $contrato) {
    //         foreach ($contrato as $llaveContrato => $datos) {
    //             $acumulado[trim($datos->nom_suc)][$llaveSemana]['21'][$llaveContrato] =
    //                 ($acumulado[trim($datos->nom_suc)][$llaveSemana]['21'][$llaveContrato] ?? 0) + $datos->kilos21;
    //
    //             $acumulado[trim($datos->nom_suc)][$llaveSemana]['22'][$llaveContrato] =
    //                 ($acumulado[trim($datos->nom_suc)][$llaveSemana]['22'][$llaveContrato] ?? 0) + $datos->kilos22;
    //
    //             $acumulado[trim($datos->nom_suc)][$llaveSemana]['23'][$llaveContrato] =
    //                 ($acumulado[trim($datos->nom_suc)][$llaveSemana]['23'][$llaveContrato] ?? 0) + $datos->kilos23;
    //         }
    //     }
    //
    //     // suma los contratos de cada semana
    //     foreach ($acumulado as $sucursal => $valoresSemana) {
    //         foreach ($valoresSemana as $nroSemana => $valores) {
    //             $acumulador[$sucursal][$nroSemana]['21'] = array_sum($valores['21']);
    //             $acumulador[$sucursal][$nroSemana]['22'] = array_sum($valores['22']);
    //             $acumulador[$sucursal][$nroSemana]['23'] = array_sum($valores['23']);
    //         }
    //     }
    //
    //     // checkea si hay semanas que no hayan tenido dato y los pone en cero
    //     $missingKeys = [];
    //     foreach ($acumulador as $sucursal =>  $datos) {
    //         $keys = array_keys($datos);
    //         $firstKey = array_key_first($datos);
    //         $lastKey = end($keys);
    //         for ($i = $firstKey; $i < $lastKey; $i++) {
    //             if (!isset($datos[$i])) {
    //                 $missingKeys[] = $i . $sucursal;
    //                 $acumulador[$sucursal][$i]['21'] = 0;
    //                 $acumulador[$sucursal][$i]['22'] = 0;
    //                 $acumulador[$sucursal][$i]['23'] = 0;
    //             }
    //         }
    //     }
    //
    //     // ordena el array
    //     foreach ($acumulador as &$sucursal) {
    //         ksort($sucursal);
    //     }
    //     unset($sucursal); //remueve la referencia para que no de error de string en el foreach
    //
    //     // obtiene el array final con los valores acumulados por semana.
    //     foreach ($acumulador as $sucursal => $valoresSemana) {
    //         $acum = 0;
    //         foreach ($valoresSemana as $nroSemana => $valores) {
    //             $llaveFecha = date('d-m-Y', time() - $anio + ($semana * $nroSemana));
    //             $acumuladorFinal[$sucursal][$llaveFecha] = $valores['21'] + $valores['22'] + $valores['23'] + $acum;
    //             $acum += $valores['21'] + $valores['22'] + $valores['23'];
    //         }
    //     }
    //
    //     // obtiene el array final con el total por sucursal por semana
    //     foreach ($acumulador as $sucursal => $valoresSemana) {
    //         foreach ($valoresSemana as $nroSemana => $valores) {
    //             $llaveFecha = date('d-m-Y', time() - $anio + ($semana * $nroSemana));
    //             $sumaPorSemana[$sucursal][$llaveFecha] = $valores['21'] + $valores['22'] + $valores['23'];
    //         }
    //     }
    //
    //
    //     return [
    //         'lineas' => $acumuladorFinal,
    //         'stackedBars' => $sumaPorSemana,
    //     ];
    // }
    //
    // public static function mercaCorreAcopioPesificar(int $cliente)
    // {
    //     $puertos = [1, 2, 3];       // QUEQUEN, BAHIA, OTROS
    //     $tipoNegocio = [1, 2];       // COMPRA, VENTA
    //
    //     $id = 0;
    //     foreach ($puertos as $puerto) {
    //         foreach ($tipoNegocio as $tipo) {
    //             $db = DB::connection('dbgestion')->select("sp_GetCtontratosAPesificar $puerto, $tipo, $cliente");
    //             foreach ($db as $objeto) {
    //                 //$destino = trim($objeto->destino);
    //                 $destino = in_array(trim($objeto->destino), ['BAHIA BLANCA', 'QUEQUEN']) ? trim($objeto->destino) : 'OTROS';
    //                 $cereal = $objeto->cereal;
    //
    //                 $sql[$destino][$cereal][$tipo == 1 ? 'Compra' : 'Venta'][] = (object) [
    //                     'id' => $id,
    //                     'contrato' => $objeto->contrato,
    //                     'cliente' => $objeto->cliente,
    //                     'tons' => ($objeto->kilos / 1000),
    //                     'entregado' => $objeto->entregado,
    //                     'vto' => date('d/m/y', strtotime($objeto->vto)),
    //                     'precio' => $objeto->precio,
    //                     'pesificado' => $objeto->pesificado,
    //                 ];
    //                 $id++;
    //             }
    //         }
    //     }
    //
    //
    //     $totales = [];
    //     foreach ($sql ?? [] as $puerto => $productos) {
    //         foreach ($productos as $producto => $negocios) {
    //             foreach ($negocios as $tipoNegocio => $filas) {
    //                 foreach ($filas as $value) {
    //                     $totales[$puerto][$producto][$tipoNegocio]['tons'] =
    //                         ($totales[$puerto][$producto][$tipoNegocio]['tons'] ?? 0) + $value->tons;
    //
    //                     $totales[$puerto][$producto][$tipoNegocio]['entregado'] =
    //                         ($totales[$puerto][$producto][$tipoNegocio]['entregado'] ?? 0) + $value->entregado;
    //
    //                     $totales[$puerto][$producto][$tipoNegocio]['pesificado'] =
    //                         ($totales[$puerto][$producto][$tipoNegocio]['pesificado'] ?? 0) + $value->pesificado;
    //                 }
    //             }
    //         }
    //     }
    //
    //     // formato a los numeros
    //     // array_walk_recursive($totales, function (&$value) {
    //     //     if (is_numeric($value)) {
    //     //         $value = number_format($value, 0, ",", ".");
    //     //     }
    //     // });
    //
    //     return [
    //         'sql' => ($sql ?? []),
    //         'totales' => ($totales ?? []),
    //     ];
    // }
    //
    // public static function ventasComprasAgro(int $cliente)
    // {
    //     $sql = DB::connection('dbgestion')->select("spGetVentasComprasAgro $cliente");
    //
    //     $totales = [];
    //     // agrego id para la datagrid
    //     foreach ($sql as $key => $value) {
    //         $sql[$key]->id = $key;
    //         $totales['venta'] = ($totales['venta'] ?? 0) + $value->vende;
    //         $totales['compra'] = ($totales['compra'] ?? 0) + $value->compra;
    //         $totales['diferencia'] = ($totales['diferencia'] ?? 0) + $value->dife;
    //         $totales['directo'] = ($totales['directo'] ?? 0) + $value->directo;
    //     }
    //
    //     $totales['diferenciaDirecto'] = $totales['diferencia'] + $totales['directo'];
    //     // formato a los numeros
    //     array_walk_recursive($totales, function (&$value) {
    //         if (is_numeric($value)) {
    //             $value = number_format($value, 2, ",", ".");
    //         }
    //     });
    //
    //     return [
    //         'sql' => ($sql ?? []),
    //         'totales' => ($totales ?? []),
    //     ];
    // }
    //
    // public static function financiero(int $cliente)
    // {
    //     $directo = array_sum(array_column(DB::connection('dbgestion')->select("spGetVentasComprasAgro $cliente"), 'directo'));
    //     $datos = DB::connection('dbgestion')->select("sp_GetFinanciero $cliente");
    //     // dd($directo);
    //
    //     $sql = [];
    //     $vencimiento = "";
    //     $contador = -1;
    //     $totAnterior = 0;
    //     $ventaMenosCompra = 0;
    //
    //     foreach ($datos as $fila) {
    //         if ($vencimiento != $fila->Vencimiento) {
    //             $contador++;
    //             $sql[$contador]['id'] = $contador;
    //             $sql[$contador]['vencimiento'] = trim($fila->Vencimiento);
    //             $sql[$contador]['V'] = 0;
    //             $sql[$contador]['C'] = 0;
    //         }
    //         $sql[$contador][$fila->tipo] = $fila->ImporteFacturado;
    //         $vencimiento = $fila->Vencimiento;
    //     }
    //
    //     foreach ($sql as $key => $value) {
    //         $ventaMenosCompra = $value['V'] - $value['C'];
    //         $sql[$key]['V-C'] = $ventaMenosCompra + $totAnterior;
    //         $totAnterior += $ventaMenosCompra;
    //         $sql[$key]['V-C+D'] = $totAnterior + $directo;
    //     }
    //
    //     return ['sql' => $sql, 'directo' => $directo];
    // }
    //
    // public static function compraVenta(int $cliente, string $cosecha)
    // {
    //     $calculo = [];
    //     $totales = [];
    //
    //     $sql['dolar']['compra'] = DB::connection('dbgestion')->select("sp_GetComprasVentasByCosechayTipo '$cosecha', 'C-D', $cliente");
    //     $sql['dolar']['venta'] = DB::connection('dbgestion')->select("sp_GetComprasVentasByCosechayTipo '$cosecha', 'V-D', $cliente");
    //     $sql['pesos']['compra'] = DB::connection('dbgestion')->select("sp_GetComprasVentasByCosechayTipo '$cosecha', 'C-P', $cliente");
    //     $sql['pesos']['venta'] = DB::connection('dbgestion')->select("sp_GetComprasVentasByCosechayTipo '$cosecha', 'V-P', $cliente");
    //     $sql['fijar']['compra'] = DB::connection('dbgestion')->select("sp_GetComprasVentasByCosechayTipo '$cosecha', 'C-A', $cliente");
    //     $sql['fijar']['venta'] = DB::connection('dbgestion')->select("sp_GetComprasVentasByCosechayTipo '$cosecha', 'V-A', $cliente");
    //
    //     $contador = 0;
    //     // agrego ventas - compras y hago el calculo final
    //     foreach ($sql as $moneda => $tipoNegocio) {
    //         foreach ($tipoNegocio as $negocio => $filas) {
    //             if ($negocio === "venta") {
    //                 foreach ($filas as $llave => $dato) {
    //                     if (!isset($sql[$moneda]['ventaCompra'][$llave])) $sql[$moneda]['ventaCompra'][$llave] = new stdClass();
    //                     $sql[$moneda]['ventaCompra'][$llave]->Kilos = ($dato->Kilos ?? 0) - ($sql[$moneda]['compra'][$llave]->Kilos ?? 0);
    //                     $sql[$moneda]['ventaCompra'][$llave]->precio = ($dato->precio ?? 0) - ($sql[$moneda]['compra'][$llave]->precio ?? 0);
    //                     $sql[$moneda]['ventaCompra'][$llave]->total = ($dato->total ?? 0) - ($sql[$moneda]['compra'][$llave]->total ?? 0);
    //                     $sql[$moneda]['ventaCompra'][$llave]->cereal = $dato->cereal;
    //                     $sql[$moneda]['ventaCompra'][$llave]->entregado = ($dato->entregado ?? 0) - ($sql[$moneda]['compra'][$llave]->entregado ?? 0);
    //                     $calculo[$moneda][] = ["id" => $contador, "cereal" => $dato->cereal, "kgxdifprecio" => ($dato->Kilos * ($dato->precio - ($sql[$moneda]['compra'][$llave]->precio ?? 0)))];
    //                     $contador++;
    //                 }
    //             }
    //         }
    //     }
    //     // agrego columna total entregado(precio * entregado / 1000) id para el dtagrid
    //     $contador = 0;
    //     foreach ($sql as $moneda => $tipoNegocio) {
    //         foreach ($tipoNegocio as $tipo => $value) {
    //             foreach ($value as $llave => $dato) {
    //                 $dato->precioPorEntregado = $dato->precio * $dato->entregado / 1000;
    //                 $dato->id = $contador;
    //                 $contador++;
    //             }
    //         }
    //     }
    //
    //
    //     foreach ($sql as $moneda => $tipoNegocio) {
    //         foreach ($tipoNegocio as $tipo => $value) {
    //             foreach ($value as $llave => $dato) {
    //                 if (!isset($totales[$moneda][$tipo])) $totales[$moneda][$tipo] = new stdClass();
    //                 $totales[$moneda][$tipo]->Kilos = ($totales[$moneda][$tipo]->Kilos ?? 0) + $dato->Kilos;
    //                 $totales[$moneda][$tipo]->total = ($totales[$moneda][$tipo]->total ?? 0) + $dato->total;
    //                 $totales[$moneda][$tipo]->entregado = ($totales[$moneda][$tipo]->entregado ?? 0) + $dato->entregado;
    //                 $totales[$moneda][$tipo]->precioPorEntregado = ($totales[$moneda][$tipo]->precioPorEntregado ?? 0) + $dato->precioPorEntregado;
    //             }
    //         }
    //     }
    //
    //     return [
    //         'sql' => $sql,
    //         'totales' => $totales,
    //         'calculo' => $calculo,
    //     ];
    // }
}
