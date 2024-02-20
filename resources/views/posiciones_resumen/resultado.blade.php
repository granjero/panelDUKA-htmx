<div class="card me-3 my-3">
    <h5 class="card-header" style="background-color: var(--bs-success-bg-subtle)">Resumen Posiciones</h5>
    <div class="card-body">

        <table class="table table-striped tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Tot. Tns. Compra</th>
                    <th>Ponderado Compra</th>
                    <th>Tot. Tns. Venta</th>
                    <th>Ponderado Venta</th>
                    <th>Compra - Venta</th>
                    <th>Pond. Com. - Ven.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sql as $producto => $valores)
                <tr>
                    <td>{{ $producto }}</td>
                    <td>{{ $valores['TotTonsCompra'] }}</td>
                    <td>{{ $valores['TotPondCompra'] }}</td>
                    <td>{{ $valores['TotTonsVenta'] }}</td>
                    <td>{{ $valores['TotPondVenta'] }}</td>
                    <td>{{ $valores['TotTonsCompra'] - $valores['TotTonsVenta']}}</td>
                    <td>{{ $valores['TotPondCompra'] - $valores['TotPondVenta']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
        searching: false,
        ordering: false,
        language: {
            url: '/json/es-ES.json',
        },
        columnDefs: [{
            targets: [1, 2, 3, 4, 5, 6],
            className: 'dt-body-right',
        }],
    });
</script>
