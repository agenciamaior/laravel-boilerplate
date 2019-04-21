@extends('boilerplate.page')

@section('header-title')
    <h1>Usuários</h1>
@stop

@section('header-breadcrumbs')
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Usuários</li>
@endsection

@section('content')
    <div class="my-2">
        @can('create', \App\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo Usuário</a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover" id="users-list">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        @php
                            $class = '';

                            if ($u->locked) {
                                $class = 'text-muted';
                            }
                        @endphp

                        <tr class="{{ $class }}">
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->role_string }}</td>
                            <td>
                                <div class="table-actions">
                                    @can('edit', $u)
                                        <a href="{{ route('users.edit', ['user' => $u]) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil-alt"></i> Editar</a>
                                    @endcan

                                    @if (!$u->locked)
                                        @can('block', $u)
                                            @if ($u->id != Auth::user()->id)
                                                <a href="{{ route('users.block', ['user' => $u]) }}" class="btn btn-default btn-sm confirmable"><i class="fa fa-lock"></i> Bloquear</a>
                                            @endif
                                        @endcan
                                    @else
                                        @can('unblock', $u)
                                            @if ($u->id != Auth::user()->id)
                                                <a href="{{ route('users.unblock', ['user' => $u]) }}" class="btn btn-default btn-sm confirmable"><i class="fa fa-lock-open"></i> Desbloquear</a>
                                            @endif
                                        @endcan
                                    @endif

                                    @can('destroy', $u)
                                        @if ($u->id != Auth::user()->id)
                                            {{ Html::deleteLink('Excluir', route('users.destroy', ['user' => $u]), ['button_class' => 'btn btn-danger btn-sm confirmable']) }}
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script>
        $('#users-list').DataTable();
    </script>
@stop
