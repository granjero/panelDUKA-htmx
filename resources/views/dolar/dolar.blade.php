@extends('layout.layout')

@section('titulo')
PANEL.duka
@stop

@section('filtros')
    @include('posiciones.formulario_filtro')
@endsection

@section('resultado')
    @include('posiciones.resultado')
@stop
