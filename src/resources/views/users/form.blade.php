{{ Form::restForm($user, ['id' => 'user-form']) }}

<div class="card">
    <div class="card-body">
        {{ Form::bsText('name', 'Nome') }}

        {{ Form::bsEmail('email', 'E-mail') }}

        @if(!$user->id)
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsPassword('password', 'Senha', ['autocomplete' => 'new-password']) }}
                </div>

                <div class="col-md-6">
                    {{ Form::bsPassword('confirm_password', 'Repita a Senha') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                {{ Form::bsSelect('role', 'Perfil', \App\User::roles(), ['class' => 'select-2']) }}
            </div>
        </div>
    </div>
</div>

@if ($user->id)    
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
                    {{ Form::bsPassword('new_password',  'Nova Senha', ['autocomplete' => 'new-password']) }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsPassword('confirm_new_password',  'Repita a Nova Senha') }}
                </div>
            </div>
        </div>
    </div>
@endif

{{ Form::bsSubmit('Salvar') }}

{{ Form::close() }}

@section('js')
    <script>
        $('#user-form').validate({
            rules: {
                'name': 'required',
                'email': {
                    'required': true,
                    'email': true,
                    'remote': {
                        url: '{{ route("users.check-email") }}',
                        type: 'post',
                        data: {
                            id: $('#id').val(),
                            _token: '{{ csrf_token() }}'
                        }
                    },
                },
                'password': 'required',
                'confirm_password': {
                    'required': true,
                    'equalTo': '#password'
                },
                'role': 'required',
                'confirm_new_password': {
                    'required': () => $('#new_password').val() !== '',
                    'equalTo': '#new_password'
                }
            },
            messages: {
                'email': {
                    'remote': 'Este e-mail já está sendo utilizado'
                },
                'confirm_password': {
                    'equalTo': 'Deve ser igual à Senha'
                },
                'confirm_new_password': {
                    'equalTo': 'Deve ser igual à Nova Senha'
                }
            }
        });
    </script>
@endsection
