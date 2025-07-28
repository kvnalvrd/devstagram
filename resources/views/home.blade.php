@extends('layouts.app')

@section('titulo')
    Página Principal
@endsection

@section('contenido')

    <x-listar-post :posts="$posts" :emptyMessage="'No hay posts, sigue a alguien para poder mostrar sus posts'" />

@endsection