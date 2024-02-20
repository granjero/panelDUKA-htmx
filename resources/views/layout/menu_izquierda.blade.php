<div class="offcanvas offcanvas-start" tabindex="-1" id="menu_izquierda" aria-labelledby="menu_izquierda_label">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menu_izquierda_label">Dukarevich</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-2">
            Un saludo para todos lo que me conocen. La radio está buenísima!!!
        </div>
        <ul class="list-group list-group-flush">
            <button
                data-bs-dismiss="offcanvas"
                classtype="button"
                class="list-group-item list-group-item-action rounded-5"
                hx-get="/posiciones"
                hx-trigger="click"
                hx-target="#filtros">
                <i class="fa-solid fa-location-dot mx-1"></i> Posiciones
            </button>

            <button
                data-bs-dismiss="offcanvas"
                classtype="button"
                class="list-group-item list-group-item-action rounded-5"
                hx-get="/posiciones_resumen"
                hx-trigger="click"
                hx-target="#filtros">
                <i class="fa-solid fa-map-pin mx-1"></i> Resumen Posiciones
            </button>

            {{-- GRUPO control--}}
            <button class="list-group-item list-group-item-action rounded-5"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse_control"
                aria-expanded="false"
                aria-controls="control">
                <i class="fa-solid fa-magnifying-glass-dollar mx-1"></i> Control
                <i class="fa-solid fa-chevron-up pt-2 float-end"></i>
            </button>
            <div class="collapse" id="collapse_control">
                <ul class="list-group list-group-flush ms-3">
                    <button
                        data-bs-dismiss="offcanvas"
                        classtype="button"
                        class="list-group-item list-group-item-action rounded-5"
                        hx-get="/dolar"
                        hx-trigger="click"
                        hx-target="#filtros">
                        <i class="fa-solid fa-dollar-sign mx-1"></i> Dolar
                    </button>
                    <button
                        data-bs-dismiss="offcanvas"
                        classtype="button"
                        class="list-group-item list-group-item-action rounded-5">
                        <i class="fa-solid fa-file-signature mx-1"></i> Contratos Sucursales
                    </button>
                </ul>
            </div>

            {{-- GRUPO financiero--}}
            <button class="list-group-item list-group-item-action rounded-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_financiero" aria-expanded="false" aria-controls="control">
                <i class="fa-solid fa-money-bill-trend-up"></i> Financiero <i class="fa-solid fa-chevron-up pt-2 float-end"></i>
            </button>
            <div class="collapse" id="collapse_financiero">
                <ul class="list-group list-group-flush ms-3">
                    <button data-bs-dismiss="offcanvas" classtype="button" class="list-group-item list-group-item-action rounded-5">
                        <i class="fa-solid fa-file-invoice-dollar mx-1"></i> Merca Acopio Pesificar
                    </button>
                    <button data-bs-dismiss="offcanvas" classtype="button" class="list-group-item list-group-item-action rounded-5">
                        <i class="fa-solid fa-tractor mx-1"></i> Ventas Compras Agro
                    </button>
                    <button data-bs-dismiss="offcanvas" classtype="button" class="list-group-item list-group-item-action rounded-5">
                        <i class="fa-solid fa-chart-line mx-1"></i> Financiero
                    </button>
                    <button data-bs-dismiss="offcanvas" classtype="button" class="list-group-item list-group-item-action rounded-5">
                        <i class="fa-solid fa-cart-shopping mx-1"></i> Compra Venta
                    </button>
                </ul>
            </div>

        </ul>
    </div>
</div>
