@extends('boilerplate.master')

@section('adminlte_css')
    @yield('css')
@endsection

@section('body')
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
        
            <!-- Área de busca -->
            <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        
            <ul class="navbar-nav ml-auto">
                <!-- Notificações -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">12</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">12 Notificações</span>

                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item">
                            <i class="fa fa-envelope mr-2"></i> 4 novas mensagens
                            <span class="float-right text-muted text-sm">5 mins</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item">
                            <i class="fa fa-users mr-2"></i> 8 solicitações
                            <span class="float-right text-muted text-sm">1 hora</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="#" class="dropdown-item dropdown-footer">Ver todas as notificações</a>
                    </div>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    {{ Form::open(['url' => route('logout')]) }}

                    <button type="submit" class="nav-link btn btn-link confirmable">Sair <i class="fa fa-power-off"></i></button>

                    {{ Form::close() }}
                </li>
            </ul>
        </nav>
    
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Logo -->
            <a href="/" class="brand-link">
                <span class="brand-text font-weight-light"><b>Laravel</b> Boilerplate</span>
            </a>
        
            <div class="sidebar">
                <!-- Usuário -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        {!! Auth::user()->avatar !!}
                    </div>

                    <div class="info">
                        <a href="{{ route('users.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>
        
                <!-- Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        @include('boilerplate.main-menu')
                    </ul>
                </nav>
            </div>
        </aside>
    
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <!-- Título -->
                        <div class="col-sm-6">
                            @yield('header-title')
                        </div>

                        <!-- Breadcrumbs -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('header-breadcrumbs')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Main -->
            <section class="content">
                @if(Session::has('flash.success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>

                        {{ Session::get('flash.success') }}
                    </div>
                @endif

                @if(Session::has('flash.error'))
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        
                        {{ Session::get('flash.error') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </div>
        
        <!-- Footer -->
        <footer class="main-footer">
            <div class="text-right">
                Versão 1.0.0
            </div>
        </footer>
  </div>
@endsection

@section('adminlte_js')
    @yield('js')
@endsection