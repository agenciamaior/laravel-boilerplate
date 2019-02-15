@extends('layouts.page')

@section('header-title')
    <h1>
        Usuários
        <small>Editar</small>
    </h1>
@stop

@section('content')
    @include('users.form')
@stop