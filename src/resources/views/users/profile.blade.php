@extends('boilerplate.page')

@section('header-title')
    <h1>Perfil</h1>
@endsection

@section('content')
    {{ Form::model($user, ['id' => 'profile-form', 'url' => route('users.save-profile'), 'files' => true]) }}

    <div class="card">
        <div class="card-body">
            {{ Form::bsText('name', 'Nome') }}

            {{ Form::bsEmail('email', 'E-mail') }}

            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsFile('imagem', 'Avatar', ['default' => ($user->avatar_extension) ? sprintf('/files/users/%s.%s', $user->id, $user->avatar_extension) : null]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Trocar Senha
                <small>Deixe em branco se não quiser trocar</small>
            </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsPassword('old_password', 'Senha Antiga') }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsPassword('new_password', 'Nova Senha') }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsPassword('confirm_new_password', 'Confirmação da Nova Senha') }}
                </div>
            </div>
        </div>
    </div>

    {{ Form::bsSubmit('Salvar') }}

    {{ Form::close() }}
@stop

@section('js')
    <script>
        $('#profile-form').validate({
            rules: {
                'name': 'required',
                'email': {
                    'required': true,
                    'email': true,
                    'remote': {
                        url: '{{ route('users.check-profile-email') }}',
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    },
                },
                'old_password': {
                    'remote': {
                        depends: () => $('#old_password').val() !== '',
                        param: {
                            url: '{{ route('users.check-profile-password') }}',
                            type: 'post',
                            data: {
                                _token: '{{ csrf_token() }}'
                            }
                        }
                    },
                },
                'new_password': {
                    'required': () => $('#old_password').val() !== '',
                },
                'confirm_password': {
                    'required': () => $('#old_password').val() !== '' && $('#new_password').val() !== '',
                    'equalTo': '#new_password'
                }
            },
            messages: {
                'email': {
                    'remote': 'E-mail já está sendo usado'
                },
                'old_password': {
                    'remote': 'Senha inválida'
                },
                'confirm_password': {
                    'equalTo': 'Campo deve ser igual à nova senha'
                }
            }
        })
    </script>
@endsection