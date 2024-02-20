<div class="card me-3 my-3">
    <div class="card-header">
        <ul class="nav nav-tabs" id="tabs-puerto" role="tablist">
            @forelse($sql as $puerto => $valores)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($loop->first)active @endif" id="{{ str_replace(' ', '', $puerto) }}" data-bs-toggle="tab" data-bs-target="#{{ str_replace(' ', '', $puerto) }}-panel" type="button" role="tab" aria-controls="{{ str_replace(' ', '', $puerto) }}-panel" aria-selected="true">
                    {{ $puerto }}</button>
            </li>
            @empty
            <li class="nav-item" role="presentation">
                <button class="nav-link" active id="sin_datos" data-bs-toggle="tab" data-bs-target="#sin_datos-panel" type="button" role="tab" aria-controls="sin_datos-panel" aria-selected="true"> Sin Datos
                </button>
            </li>
            @endforelse
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="totales" data-bs-toggle="tab" data-bs-target="#totales-panel" type="button" role="tab" aria-controls="totales-panel" aria-selected="true">
                    TOTALES</button>
            </li>
        </ul>

        <div class="tab-content" id="tabs_posiciones">
            @forelse($sql as $puerto => $valores)
            <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ str_replace(' ', '', $puerto) }}-panel" role="tabpanel" aria-labelledby="{{ str_replace(' ', '', $puerto) }}" tabindex="0">
                <div class="row">
                    <div class="col-md-6 my-3">
                        <div class="card">
                            <h5 class="card-header" style="background-color: var(--bs-success-bg-subtle)">Compra</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="alert alert-success text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total Toneladas Compra">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            {{ number_format(($valores['TonsCompra'] ?? 0), 2, ',', '.') ?? 'sin dato' }} tns.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="alert alert-success text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Precio Promedio de Compra">
                                            <i class="fa-solid fa-scale-balanced"></i>
                                            @php
                                            $precioPromedio = ($valores['CompraxPrecio'] ?? 0) / ($valores['TonsCompra'] ?? 0.00000001);
                                            @endphp
                                            ${{ number_format($precioPromedio, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-striped tabla">
                                    <thead>
                                        <tr>
                                            <th>Contrato</th>
                                            <th>Tns.</th>
                                            <th>Precio</th>
                                            <th>Mes</th>
                                            <th>Tns. x Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($valores["Compra"] ?? [] as $valor)
                                        <tr>
                                            <td>{{ trim($valor["contrato"]) }}</td>
                                            <td>{{ number_format($valor["tns"], 0, ",", "") }}</td>
                                            <td>{{ number_format($valor["precio"], 2, ",", ".") }}</td>
                                            <td>{{ trim($valor["mes"]) }}</td>
                                            <td>{{ number_format($valor["txp"], 2, ",", ".") }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 my-3">
                        <div class="card">
                            <h5 class="card-header" style="background-color: var(--bs-warning-bg-subtle)">Venta</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="alert alert-warning text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total Toneladas Venta">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            {{ number_format(($valores['TonsVenta'] ?? 0), 2, ',', '.') ?? 'sin dato' }} tns.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="alert alert-warning text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Precio Promedio de Venta">
                                            <i class="fa-solid fa-scale-balanced"></i>
                                            @php
                                            $precioPromedio = ($valores['VentaxPrecio'] ?? 0) / ($valores['TonsVenta'] ?? 0.00000001);
                                            @endphp
                                            ${{ number_format($precioPromedio, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped tabla">
                                    <thead>
                                        <tr>
                                            <th>Contrato</th>
                                            <th>Tns.</th>
                                            <th>Precio</th>
                                            <th>Mes</th>
                                            <th>Tns. x Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($valores["Venta"] ?? [] as $valor)
                                        <tr>
                                            <td>{{ trim($valor["contrato"]) }}</td>
                                            <td>{{ number_format($valor["tns"], 0, ",", "") }}</td>
                                            <td>{{ number_format($valor["precio"], 2, ",", ".") }}</td>
                                            <td>{{ trim($valor["mes"]) }}</td>
                                            <td>{{ number_format($valor["txp"], 2, ",", ".") }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info" role="alert">
                    <i class="fa-solid fa-calculator"></i>
                    Compras - Ventas:
                    <span class="fw-bolder">
                        {{ number_format((($valores['TonsCompra'] ?? 0) - ($valores['TonsVenta'] ?? 0)), 2, ',', '.') ?? 'sin dato' }} tns.
                    </span>
                </div>

            </div>
            @empty
            <div class="tab-pane fade show active" id="sin_datos-panel" role="tabpanel" aria-labelledby="sin_datos" tabindex="0">
                <div class="row">
                    <div class="col-md-12 my-3">

                        <div class="card">
                            <h5 class="card-header" style="background-color: var(--bs-success-bg-subtle)">Sin datos</h5>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col">
                                        <p>Sin Datos</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse

            <div class="tab-pane fade" id="totales-panel" role="tabpanel" aria-labelledby="totales" tabindex="0">
                <div class="row">
                    <div class="col-md-4 my-3">
                        <div class="card">
                            <h5 class="card-header" style="background-color: var(--bs-success-bg-subtle)">Compra</h5>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="alert alert-success text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total Toneladas Compra">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            {{ number_format(($totTnsC ?? 0), 0, ',', '.') ?? 'sin dato' }} tns.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="alert alert-success text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Precio Ponderado de Compra">
                                            <i class="fa-solid fa-scale-balanced"></i>
                                            ${{ number_format(($totPondC ?? 0), 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped tabla_totales">
                                    <thead>
                                        <tr>
                                            <th>Puerto</th>
                                            <th>Toneladas</th>
                                            <th>Precio Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sql ?? [] as $puerto => $valor)
                                        <tr>
                                            <td>{{ $puerto }}</td>
                                            <td>{{ number_format(($valor["TonsCompra"] ?? 0), 0, ",", ".")  }}</td>
                                            <td>{{ number_format((($valor["TonsCompra"] ?? 0) == 0 ? 0 : $valor["CompraxPrecio"] / $valor["TonsCompra"]), 2, ",", ".")}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 my-3">
                        <div class="card">
                            <h5 class="card-header" style="background-color: var(--bs-warning-bg-subtle)">Venta</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="alert alert-warning text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total Toneladas Venta">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            {{ number_format(($totTnsV ?? 0), 0, ',', '.') ?? 'sin dato' }} tns.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="alert alert-warning text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Precio Ponderado de Venta">
                                            <i class="fa-solid fa-scale-balanced"></i>
                                            ${{ number_format(($totPondV ?? 0), 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped tabla_totales">
                                    <thead>
                                        <tr>
                                            <th>Puerto</th>
                                            <th>Toneladas</th>
                                            <th>Precio Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sql ?? [] as $puerto => $valor)
                                        <tr>
                                            <td>{{ $puerto }}</td>
                                            <td>{{ number_format(($valor["TonsVenta"] ?? 0), 0, ",", ".")  }}</td>
                                            <td>{{ number_format((($valor["TonsVenta"] ?? 0) == 0 ? 0 : $valor["VentaxPrecio"] / $valor["TonsVenta"]), 2, ",", ".")}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 my-3">
                        <div class="card">
                            <h5 class="card-header" style="background-color: var(--bs-info-bg-subtle)">Diferencia ( compra - venta )</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="alert alert-info text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total Toneladas Venta">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            {{ number_format(( (($totTnsC ?? 0) - ($totTnsV ?? 0)) ?? 0), 0, ',', '.') ?? 'sin dato' }} tns.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="alert alert-info text-center" role="alert" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Diferencia de Precios Ponderados">
                                            <i class="fa-solid fa-scale-balanced"></i>
                                            ${{ number_format(( (($totPondC ?? 0) - ($totPondV ?? 0)) ?? 0), 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped tabla_totales">
                                    <thead>
                                        <tr>
                                            <th>Puerto</th>
                                            <th>Toneladas</th>
                                            <th>Precio Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sql ?? [] as $puerto => $valor)
                                        @php
                                        $promedioVenta = (($valor["TonsVenta"] ?? 0) == 0 ? 0 : $valor["VentaxPrecio"] / $valor["TonsVenta"]);
                                        $promedioCompra = (($valor["TonsCompra"] ?? 0) == 0 ? 0 : $valor["CompraxPrecio"] / $valor["TonsCompra"]);
                                        $promedio = $promedioCompra - $promedioVenta;
                                        @endphp
                                        <tr>
                                            <td>{{ $puerto }}</td>
                                            <td>{{ number_format((($valor["TonsCompra"] ?? 0) - ($valor["TonsVenta"] ?? 0)) ,0, ",", ".") }}</td>
                                            <td>{{ number_format($promedio, 2, ",", ".")}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <pre> --}}
{{-- {{ var_dump($sql) }} --}}
{{-- </pre> --}}

<script>
    console.log("resultado.blade.php");
    tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    $('.tabla').DataTable({
        searching: true,
        ordering: false,
        language: {
            url: '/json/es-ES.json',
        },
        columnDefs: [{
            targets: [1, 2, 4],
            className: 'dt-body-right',
        }, ],
    });
    $('.tabla_totales').DataTable({
        searching: false,
        ordering: false,
        language: {
            url: '/json/es-ES.json',
        },
        columnDefs: [{
            targets: [1, 2],
            className: 'dt-body-right',
        }],
    });
</script>
