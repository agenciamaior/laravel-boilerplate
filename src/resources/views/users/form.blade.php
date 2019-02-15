{{ Form::restForm($user, ['id' => 'user-form']) }}

<div class="card">
    <div class="card-body">
        {{ Form::bsText('name', 'Nome') }}

        {{ Form::bsEmail('email', 'E-mail') }}

        @if(!$user->id)
            <div class="row">
                <div class="col-md-6">
                    {{ Form::bsPassword('password', 'Senha') }}
                </div>

                <div class="col-md-6">
                    {{ Form::bsPassword('confirm_password', 'Confirme a Senha') }}
                </div>
            </div>
        @endif
    </div>
</div>

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
            },
            messages: {
                'email': {
                    'remote': 'E-mail já está sendo utilizado'
                },
                'confirm_password': {
                    'equalTo': 'Campo deve ser igual à Senha'
                }
            }
        });
    </script>
@endsection
