@php
    $routeArray = app('request')->route()->getAction();
    $controllerAction = class_basename($routeArray['controller']);
    list($controller, $action) = explode('@', $controllerAction);
@endphp

@php
    $class = '';

    if ($controller == 'HomeController') {
        $class = 'active';
    }
@endphp

<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ $class }}">
        <i class="nav-icon fa fa-home"></i>
        <p>Home</p>
    </a>
</li>

@php
    $class = '';

    if ($controller == 'UsersController') {
        $class = 'menu-open';
    }
@endphp

<li class="nav-item has-treeview {{ $class }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-cog"></i>
        <p>Configurações <i class="fa fa-angle-left right"></i></p>
    </a>
    
    <ul class="nav nav-treeview">
        @can('index', \App\User::class)    
            @php
                $class = '';
            
                if ($controller == 'UsersController' && $action <> 'profile') {
                    $class = 'active';
                }
            @endphp

            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ $class }}">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Usuários</p>
                </a>
            </li>
        @endcan

        @php
            $class = '';
        
            if ($controller == 'UsersController' && $action == 'profile') {
                $class = 'active';
            }
        @endphp

        <li class="nav-item">
            <a href="{{ route('users.profile') }}" class="nav-link {{ $class }}">
                <i class="fa fa-user nav-icon"></i>
                <p>Perfil</p>
            </a>
        </li>
    </ul>
</li>