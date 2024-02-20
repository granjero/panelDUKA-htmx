@extends('layout.layout')

@section('titulo')
PANEL.duka
@stop

@section('filtros')
    @include('posiciones_resumen.formulario_filtro')
@endsection

@section('resultado')
    @include('posiciones_resumen.resultado')
@stop
