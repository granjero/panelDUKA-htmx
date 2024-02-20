<!doctype html>
<html lang="en">

<head>
    <meta name="htmx-config" content='{"refreshOnHistoryMiss":"true"}' />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>@yield('titulo', 'PANEL')</title>
    <link href={{ asset('css/all.min.css') }} rel="stylesheet">
    <link href={{ asset('css/jquery.dataTables.min.css') }} rel="stylesheet">
    <link href={{ asset('css/bootstrap.min.css') }} rel="stylesheet">
    <link href={{ asset('css/select2.min.css') }} rel="stylesheet">
    {{-- <link href={{ asset('css/select2-bootstrap.min.css') }} rel="stylesheet"> --}}
    <link href={{ asset('css/estilo.css') }} rel="stylesheet">

    <script src={{ asset('js/bootstrap.bundle.min.js') }}></script>
    <script src={{ asset('js/jquery-3.7.1.min.js') }}></script>
    <script src={{ asset('js/jquery.dataTables.min.js') }}></script>
    <script src={{ asset('js/htmx.min.js') }}></script>
    <script src={{ asset('js/select2.min.js') }}></script>
</head>


{{-- <script src={{ asset('js/_hyperscript.min.js') }}></script> --}}

<body hx-history="false">
    @include('layout.navbar')
    @include('layout.menu_izquierda')
    <div class="row">
        <div id="filtros" class="col-2">
        @yield('filtros', '')
        {{-- id filtros viene del menu de la izquierda --}}
        </div>
        <div id="resultado" class="col-10">
        @yield('resultado', '')
        </div>
        {{-- id resultado viene del formulario de los filtros --}}
    </div>
</body>
<div id="scripts">
    <script>
        console.log("layout.blade.php");
        document.body.addEventListener('htmx:pushedIntoHistory', (evt) => {
            localStorage.removeItem('htmx-history-cache')
        })
    </script>
</div>

</html>
