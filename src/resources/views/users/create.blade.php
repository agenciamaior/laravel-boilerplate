@extends('boilerplate.page')

@section('header-title')
    <h1>
        Usuários
        <small>Criar</small>
    </h1>
@stop

@section('content')
    @include('users.form')
@stop