@extends('layouts.page')

@section('header-title')
    <h1>
        Usuários
    </h1>
@stop

@section('content')
    <div class="actions">
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo Usuário</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover" id="users-list">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th class="to-center" data-orderable="false">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('users.edit', ['user' => $u]) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil-alt"></i> Editar</a>

                                    {{ Html::bsDelete('Excluir', route('users.destroy', ['user' => $u]), ['button_class' => 'btn btn-danger btn-sm confirmable']) }}
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
