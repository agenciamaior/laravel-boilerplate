@extends('boilerplate.master')

@section('adminlte_css')
    @yield('css')
@endsection

@section('body_class', 'login-page')

@section('body')
    @yield('content')
@endsection

@section('adminlte_js')
    @yield('js')
@endsection