<form hx-get="/posiciones/create" hx-target="#resultado">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="btn-group my-2" role="group" aria-label="Botones Cliente">
                    <input type="radio" class="btn-check" name="cliente" value="8730" id="boton_ciancaglini" autocomplete="off" @checked($request->cliente == 8730 || is_null($request->cliente))>
                    <label class="btn btn-outline-success" for="boton_ciancaglini">Ciancaglini</label>

                    <input type="radio" class="btn-check" name="cliente" value="9685" id="boton_civera" autocomplete="off" @checked($request->cliente == 9685) >
                    <label class="btn btn-outline-success" for="boton_civera">Civera</label>
                </div>
                    <button id="consulta" type="submit" class="btn btn-success"><i class="fa-solid fa-circle-check"></i></button>
                    <button type="submit" class="btn btn-success htmx-indicator"><i class="fa-solid fa-spinner fa-spin"></i></button>
            </div>

            <div class="col-12 my-2 formulario">
                <select id="producto" name="producto" class="form-select shadow-sm" aria-label="Lista de Productos" required>
                    <option @selected(is_null($request->producto)) hidden disabled></option>
                    @forelse ($lista_productos as $producto)
                    <option value="{{ $producto->id_producto }}" @selected($request->producto == $producto->id_producto)>{{ $producto->nombre }}</option>
                    @empty
                    @endforelse
                </select>
                <label class="formulario-label" for="producto"> Producto</label>
            </div>

            <div class="col-12 my-2 formulario">
                <select id="cosecha" name="cosecha[]" multiple="multiple" class="form-select shadow-sm" aria-label="Lista de Cosechas" required>
                    {{-- <option @selected(is_null($request->producto)) hidden disabled></option> --}}
                    @forelse ($lista_cosechas as $cosecha)
                    <option value="{{ $cosecha->nombre }}" @selected($request->cosecha == $cosecha->nombre)>{{ $cosecha->nombre }}</option>
                    @empty
                    @endforelse
                </select>
                <label class="formulario-label" for="cosecha"> Cosecha</label>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('.form-select').select2({
        });
});
</script>
