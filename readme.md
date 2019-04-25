# Laravel Boilerplate

Biblioteca para gerar um template padrão de um painel administrativo baseado em [AdminLTE 3](https://adminlte.io/themes/dev/AdminLTE/index.html). Já vem com algumas funções pré-definidas, como: controle de usuários, campos com máscara, etc.

## Índice

1. [Dependências](#dependencias)
2. [Instalação](#instalacao)
3. [Recursos disponíveis](#recursos)
    1. [Formulários](#forms)
    2. [Login](#login)
    3. [Perfis de usuário](#roles)
    4. [Template](#template)
    5. [ACLs](#acl)
    6. [Usuários](#users)
    7. [Rotas](#routes)
    8. [UI](#ui)
        1. [Máscaras](#masks)
        2. [Datepicker](#datepicker)
        3. [Select2](#select2)
        4. [Editor](#editor)
    9. [Validações](#validations)
    10. [Confirmable](#confirmable)
    11. [Animação no submit do formulário](#form-spinner)
    12. [Parsers](#parsers)
    13. [Estados Brasileiros](#ufs)

## <a name="dependencias"></a> Dependências

* [Laravel 5.5 ou superior](https://laravel.com/)
* [NodeJs com NPM](https://nodejs.org/)

## <a name="instalacao"></a> Instalação

```sh
$ composer require agenciamaior/laravel_boilerplate
```

Após baixar o pacote via Composer, você deve rodar o comando abaixo para instalar a biblioteca:

```sh
$ php artisan boiler:install
```

**AVISO**: A instalação da biblioteca vai substituir VÁRIOS arquivos dentro das pastas do seu projeto Laravel. Recomenda-se executar esse comando numa instalação limpa do Laravel.

A instalação pode levar algum tempo, já que vários pacotes NPM serão baixados para seu projeto.

Essa biblioteca não instala nem configura nenhum banco de dados no seu projeto. Você deve fazê-lo manualmente como faria com qualquer outro projeto recém-criado do Laravel.

A instalação roda automaticamente o comando ``php artisan make:auth`` para gerar um sistema de Autenticação com algumas *migrations* já pré-configuradas para uma tabela de usuários, modificada para adicionar alguns campos a mais dos que já vêm na instalação padrão do Laravel.

Após a instalação, você pode executar as migrations através do comando:

```sh
$ php artisan migrate
```

A instalação copia vários arquivos de estilos e scripts para dentro da pasta *resources*. O arquivo de estilo se encontra em *resources/sass/_boilerplate.scss* e você deve importa-lo manualmente dentro do arquivo *resources/sass/app.scss*:

```scss
@import 'boilerplate';
```

Depois de importado, você deve compilar os scripts através do comando:

```sh
$ npm run dev
```

Ou, se você preferir que a compilação seja feita automaticamente toda vez que um asset for modificado, execute e deixe rodando o comando:

```sh
$ npm run watch
```

No seu arquivo de rotas *routes/web.php*, aponte o endereço raiz para a Home:

**Antes:**

```php
Route::get('/', function() {
    return view('welcome');
});
```

**Depois:**

```php
Route::get('/', 'HomeController@index')->name('home');

\AgenciaMaior\LaravelBoilerplate\LaravelBoilerplateServiceProvider::routes();
```

A instalação adiciona automaticamente suas próprias rotas. Na seção [Rotas](#routes) dentro desse documento, você pode saber como sobrescrever essas rotas.

## <a name="recursos"></a> Recursos disponíveis

### <a name="forms"></a> Formulários

Todos os formulários dentro das views utilizam a biblioteca **BootstrapForms**. Para ler a documentação completa dessa biblioteca, clique [aqui](https://github.com/agenciamaior/bootstrap_forms).

### <a name="login"></a> Login

Ao entrar pela primeira vez, o sistema vai pesquisar na tabela de usuários se ela está vazia. Se estiver, você será apresentado a uma tela para cadastrar o primeiro usuário do tipo Administrador.

Esse formulário já conta com uma validação via javascript através da biblioteca [jQuery Validation](https://jqueryvalidation.org/).

Se houverem usuários cadastrados na tabela, você será redirecionado para a tela de login normalmente.

O layout dessa página pode ser encontrado e modificado livremente em */resources/views/auth/login.blade.php*.

### <a name="roles"></a> Perfis de usuário

Essa biblioteca utiliza constantes numéricas para representar os perfis de usuário, são esses:

* Administrador => 0
* Usuário comum => 1

Essas constantes são definidas dentro do model *app/User.php*:

```php
//...

const ROLE_ADMIN = 0;
const ROLE_COMMON = 1;

public static function roles() {
    return [
        self::ROLE_ADMIN => 'Administrador',
        self::ROLE_COMMON => 'Comum',
    ];
}

public function getRoleStringAttribute() {
    return self::roles()[$this->role];
}

//...
```

Você pode adicionar novos perfis ou alterar os existentes se preferir. Essas constantes são salvas num campo *role* do tipo *INTEGER* dentro da tabela *users* (gerado pela migration da instalação).

A função **getRoleStringAttribute** é um *Accessor* para se obter a representação em formato texto do perfil do usuário. Para saber mais sobre Accessors no Laravel, consulte a [documentação](https://laravel.com/docs/master/eloquent-mutators#accessors-and-mutators).

Exemplo de utilização das constantes:

```php
$user->role = \App\User::ROLE_ADMIN;

// Equivalente a:
$user->role = 0;
```

Exemplo de utilização do Acessor:

```html
<p><b>Perfil do Usuário:</b> {{ $user->role_string }}</p>

<!-- Equivalente a: -->
<p><b>Perfil do Usuário:</b> Administrador</p>
```

### <a name="template"></a> Template

Todos os templates se encontram dentro de */resources/views/boilerplate*. Dentro desse diretório, existe um template *master* onde podem ser adicionadas as meta tags do projeto, assim como alterar o título e outros atributos globais da página.

O template *raw* é usado para telas de login e outras páginas com o mesmo formato.

As demais páginas vão herdar do template *page*.

Para alterar o menu, acesse a view *main-menu* dentro desse diretório. Na seção [Menu](#menu), você pode ver mais detalhes sobre como adicionar itens do menu.

Estrutura da view *resources/views/home.blade.php*, seguindo o template da biblioteca:

```html
@extends('boilerplate.page')

@section('css')
    {{-- Seus estilos específicos de página aqui --}}
@endsection

@section('header-title')
    <h1>
        Bem-vindo
    </h1>
@stop

@section('header-breadcrumbs')
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    {{-- Seu conteúdo aqui --}}
@stop

@section('js')
    {{-- Seus scripts específicos de página aqui --}}
@endsection
```

#### Estilos e Scripts

Os arquivos de estilo se encontram dentro de *resources/sass/_boilerplate.scss*. Como mencionado na instalação, esse arquivo não é importado automaticamente para dentro de *resources/sass/app.scss* para não causar conflitos com os estilos atuais do seu projeto. Para importa-lo, utilize no arquivo *app.scss* o comando:

```scss
@import 'boilerplate';
```

Você vai precisar resolver manualmente qualquer conflito de estilos que surgir depois dessa importação.

Ao contrário do arquivo de estilos, o arquivo de scripts *resources/js/boilerplate.js* já é adicionado automaticamente ao final do arquivo *resources/js/bootstrap.js*.

Assim, você pode definir seus próprios estilos e scripts globais separadamente dos fornecidos pela biblioteca, mas nada impede que você possa modificar esses arquivos, se assim desejar.

Os estilos e scripts específicos por página podem ser colocados em suas respectivas ``@section`` dentro das views.

### <a name="menu"></a> Menu

O template do menu se encontra em *resources/boilerplate/main-menu.blade.php*. No começo desse arquivo, duas variáveis são geradas para identificar qual o Controller atual e qual a Action atual, com isso, é possível tornar o menu ativo quando a página estiver aberta no seu respectivo item de menu.

**Exemplo de um novo item de menu:**

```html
@php
    $class = '';

    if ($controller == 'AlgumController') {
        $class = 'active';
    }
@endphp

<li class="nav-item">
    <a href="link/do/menu" class="nav-link {{ $class }}">
        <i class="nav-icon fa fa-icone"></i>
        <p>Novo Link</p>
    </a>
</li>
```

A biblioteca utiliza os ícones do [FontAwesome](https://fontawesome.com/), mas você pode utilizar qualquer outra biblioteca de ícones que desejar.

**Excemplo de um novo grupo com dois itens de menu:**

```html
@php
    $class = '';

    if ($controller == 'AlgumController') {
        $class = 'menu-open';
    }
@endphp

<li class="nav-item has-treeview {{ $class }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fa fa-icone"></i>
        <p>Grupo <i class="fa fa-angle-left right"></i></p>
    </a>
    
    <ul class="nav nav-treeview">
        @php
            $class = '';
        
            if ($controller == 'AlgumController' && $action == 'algumaAction') {
                $class = 'active';
            }
        @endphp

        <li class="nav-item">
            <a href="link/do/menu" class="nav-link {{ $class }}">
                <i class="fa fa-icone nav-icon"></i>
                <p>Item 1</p>
            </a>
        </li>

         @php
            $class = '';
        
            if ($controller == 'OutroController') {
                $class = 'active';
            }
        @endphp

        <li class="nav-item">
            <a href="link/do/menu" class="nav-link {{ $class }}">
                <i class="fa fa-icone nav-icon"></i>
                <p>Item 2</p>
            </a>
        </li>
    </ul>
</li>
```

Todas as outras opções de menu podem ser visualizadas na página oficial da biblioteca [AdminLTE 3](https://adminlte.io/themes/dev/AdminLTE/index.html).

### <a name="acl"></a> ACLs

A instalação cria automaticamente uma *Policy* para o *Model* de Usuários em *app/Policies/UserPolicy.php*, que já é importada automaticamente pra dentro do arquivo *app/Providers/AuthServiceProvider.php*. As demais Policies devem ser criadas e importadas manualmente. Para saber mais sobre Policies, consulte a [documentação](https://laravel.com/docs/master/authorization) do Laravel.

Uma diretiva padrão para permitir que o usuário administrador tenha acesso a qualquer coisa também já é adicionada ao *AuthServiceProvider.php*:

```php
// ...

class AuthServiceProvider extends ServiceProvider
{
    
    // ...

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if ($user->role == User::ROLE_ADMIN) {
                return true;
            }
        });

        // ...
    }
}
```

#### Exemplos de uso das Policies

```html
@can('create', \App\User::class)
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo Usuário</a>
@endcan

@foreach($users as $u)
    @can('edit', $u)
        <a href="{{ route('users.edit', ['user' => $u]) }}" class="btn btn-default btn-sm"><i class="fa fa-pencil-alt"></i> Editar</a>
    @endcan
@endforeach
```

### <a name="users"></a> Usuários

A biblioteca gera um CRUD simples de Usuários. Suas views podem ser visualizadas ou modificadas em *resources/views/users* e seu respectivo controller em *app/Http/Controllers/UsersController.php*.

A lista de usuários utiliza o plugin jQuery [DataTables](https://datatables.net/).

Essa área também conta com a função de bloquear/desbloquear os usuários.

Por padrão, você não pode bloquear ou excluir o seu próprio usuário nessa lista. Esse comportamento pode ser alterado dentro dessa mesma *view*.

#### Perfil

A biblioteca tem uma página de Perfil, onde você pode alterar sua senha ou enviar uma foto de avatar. Se nenhuma foto for enviada, será usado um *placeholder* com as primeiras letras do seu nome e sobrenome. Esse comportamento pode ser alterado no Accessor **getAvatarAttribute()** dentro do model *app/User.php*.

### <a name="routes"></a> Rotas

A instalação vai adicionar, ao final do arquivo *routes/web.php*, o seguinte comando:

```php
\AgenciaMaior\LaravelBoilerplate\LaravelBoilerplateServiceProvider::routes();
```

Que consiste das seguintes rotas:

```php
Route::group(['prefix' => '/users'], function () {
    Route::post('/first-user', 'UsersController@storeFirstUser')->name('users.first-user');
    Route::get('/profile', 'UsersController@profile')->name('users.profile');
    Route::post('/profile', 'UsersController@updateProfile')->name('users.save-profile');
    Route::post('/check-email', 'UsersController@checkEmail')->name('users.check-email');
    Route::post('/check-profile-email', 'UsersController@checkProfileEmail')->name('users.check-profile-email');
    Route::post('/check-profile-password', 'UsersController@checkProfilePassword')->name('users.check-profile-password');
    Route::get('/block/{user}', 'UsersController@block')->name('users.block');
    Route::get('/unblock/{user}', 'UsersController@unblock')->name('users.unblock');
});
Route::resource('/users', 'UsersController');
```

Qualquer uma dessas rotas pode ser sobrescrita, contanto que sejam definidas antes do comando acima.

### <a name="ui"></a> UI

#### <a name="masks"></a> Máscaras

As máscaras utilizam as bibliotecas jQuery [MaskPlugin](https://github.com/igorescobar/jQuery-Mask-Plugin) e [MaskMoney](https://github.com/plentz/jquery-maskmoney).

Para utilizar essas máscaras, adicione a respectiva classe ao campo:

* **.cpf-mask** - CPF (000.000.000-00)
* **.cnpj-mask** - CNPJ (00.000.000/0000-00)
* **.cpf-cnpj-mask** - CPF ou CNPJ no mesmo campo, muda a medida que você vai digitando
* **.tel-ddd-mask** - Telefone com DDD, aceita com ou sem o dígito 9 ((00) 00000000 ou (00) 900000000)
* **.cep-mask** - CEP (00000-000)
* **.time-mask** - Horas, formato *horas:minutos* (00:00)
* **.date-mask** - Data, formato *dia/mês/ano* (99/99/9999)
* **.money-mask** - Dinheiro (R$ 999.999,99)
* **.number-mask** - Número com casas decimais (999.999,99)
* **.percent-mask** - Porcentagem (999,99%)

Você pode ver um exemplo de utilização das máscaras nesse [link](http://laravel-boilerplate.agenciamaior.com.br/inputs).

A configuração das máscaras pode ser alterada em *resources/js/boilerplate.js*.

#### <a name="datepicker"></a> Datepicker

Adicione a classe **.datepicker** a um campo para utilizar o Datepicker da biblioteca [jQueryUI](https://jqueryui.com/datepicker/).

**Obs.:** A classe **.date-mask** pode ser usada em conjunto com o Datepicker.

Você pode ver um exemplo de utilização do Datepicker nesse [link](http://laravel-boilerplate.agenciamaior.com.br/inputs).

A configuração do Datepicker pode ser alterada em *resources/js/boilerplate.js*.

#### <a name="select2"></a> Select2

Adicione a classe **.select-2** a um campo do tipo Select para utilizar o [Select2](https://select2.org/).

Você pode ver um exemplo de utilização do Select nesse [link](http://laravel-boilerplate.agenciamaior.com.br/inputs).

A configuração do Select2 pode ser alterada em *resources/js/boilerplate.js*.

#### <a name="editor"></a> Editor

Acidione a classe **.editor** a um campo do tipo TextArea para utilizar o [Summernote](https://summernote.org/).

Você pode ver um exemplo de utilização do Select nesse [link](http://laravel-boilerplate.agenciamaior.com.br/inputs).

A configuração do Editor pode ser alterada em *resources/js/boilerplate.js*.

### <a name="validations"></a> Validações

Essa seção descreve algumas validações customizadas para serem utilizadas na biblioteca [jQuery Validation](https://jqueryvalidation.org/).

Todas as validações podem ser alteradas em *resources/js/boilerplate.js*.

#### dateBR

Valida um campo para uma data válida no formato *dd/mm/aaaa*.

**Exemplo:**

```php
{{ Form::bsText('data', 'Data', ['class' => 'datepicker date-mask']) }}
```

```javascript
$('#id-do-formulario').validate({
    rules: {
        data: {
            required: true,
            dateBR: true,
        },
    }
});
```

Um exemplo de utilização desse validator pode ser visto nesse [link](http://laravel-boilerplate.agenciamaior.com.br/validators).

#### period

Valida um campo de data em relação a outro campo de data para um período válido entre os dois.

**Exemplo:**

```html
 <div class="row">
    <div class="col-md-6">
        {{ Form::bsText('data_inicio', 'Data Início', ['class' => 'datepicker date-mask']) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsText('data_fim', 'Data Fim', ['class' => 'datepicker date-mask']) }}
    </div>
</div>
```

```javascript
$('#id-do-formulario').validate({
    rules: {
        data_inicio: {
            required: true,
            dateBR: true,
        },
        data_fim: {
            required: true,
            dateBR: true,
            period: ['data_inicio'],
        },
    }
});
```

Um exemplo de utilização desse validator pode ser visto nesse [link](http://laravel-boilerplate.agenciamaior.com.br/validators).

#### filesize

Valida um campo do tipo File para um tamanho de arquivo específico, em Megabytes.

**Exemplo:**

```php
{{ Form::bsFile('arquivo', 'Arquivo <small>máx 2MB</small>') }}
```

```javascript
$('#id-do-formulario').validate({
    rules: {
        arquivo: {
            required: true,
            extension: ['jpg', 'jpeg', 'png'],
            filesize: 2,
        },
    }
});
```

#### cpf

Valida um campo contendo a máscara **.cpf-mask** para um CPF válido, utilizando a validação de módulo 11.

**Exemplo:**

```php
{{ Form::bsText('cpf', 'CPF', ['class' => 'cpf-mask']) }}
```

```javascript
$('#id-do-formulario').validate({
    rules: {
        cpf: {
            required: true,
            cpf: true,
        },
    }
});
```

#### cnpj

Valida um campo contendo a máscara **.cnpj-mask** para um CNPJ válido, utilizando a validação de módulo 11.

**Exemplo:**

```php
{{ Form::bsText('cnpj', 'CNPJ', ['class' => 'cnpj-mask']) }}
```

```javascript
$('#id-do-formulario').validate({
    rules: {
        cnpj: {
            required: true,
            cnpj: true,
        },
    }
});
```
#### cpfCnpj

Valida um campo contendo a máscara **.cpf-cnpj-mask** para um CPF/CNPJ válido, utilizando a validação de módulo 11.

**Exemplo:**

```php
{{ Form::bsText('cpf_cnpj', 'CPF/CNPJ', ['class' => 'cpf-cnpj-mask']) }}
```

```javascript
$('#id-do-formulario').validate({
    rules: {
        cpf_cnpj: {
            required: true,
            cpfCnpj: true,
        },
    }
});
```

Um exemplo de utilização desses validators pode ser visto nesse [link](http://laravel-boilerplate.agenciamaior.com.br/validators).

#### editorRequired

Verifica se um campo contendo a máscara **.editor** está vazio ou não.

**Exemplo:**

```php
{{ Form::bsTextarea('editor', 'Editor', ['class' => 'editor']) }}
```

```javascript
$('#id-do-formulario').validate({
    ignore: [], // Essa diretiva é obrigatória para esse validator funcionar
    rules: {
        editor: 'editorRequired',
    }
});
```

Um exemplo de utilização desse validator pode ser visto nesse [link](http://laravel-boilerplate.agenciamaior.com.br/validators).

### <a name="confirmable"></a> Confirmable

Adicione a classe **.confirmable** a qualquer botão ou link para adicionar uma janela de confirmação a ele. Essa função é muito usada em botões de excluir.

### <a name="form-spinner"></a> Animação no submit do formulário

Por padrão, ao fazer o submit de um formulário, o botão de submit vai ficar desabilitado e o texto dentro dele será substituído por um spinner. Isso impede o usuário de clicar várias vezes no botão de submit.

Para desabilitar esse comportamento, adicione a classe **.without-spinner** à tag ``<form>`` da sua página.

### <a name="parsers"></a> Parsers

Dentro do arquivo *app/Http/Controller/Controller.php*, a instalação vai copiar duas funções para transformar datas e valores do tipo dinheiro.

**Exemplo de utilização dentro de um Controller:**

```php
$date = '03/05/2019';
$newDate = $this->parseDate($date); // 2019-05-03

$price = 'R$ 300,00';
$newPrice = $this->parseCurrency($price); // 300
```

### <a name="ufs"></a> Estados Brasileiros

Dentro do arquivo *app/Http/Controller/Controller.php*, a instalação vai copiar um array com todos os estados brasileiros e suas respectivas siglas:

```php
const ESTADOS_BRASILEIROS = [
    'AC' => 'Acre',
    'AL' => 'Alagoas',
    'AP' => 'Amapá',
    'AM' => 'Amazonas',
    'BA' => 'Bahia',
    'CE' => 'Ceará',
    'DF' => 'Distrito Federal',
    'ES' => 'Espírito Santo',
    'GO' => 'Goiás',
    'MA' => 'Maranhão',
    'MT' => 'Mato Grosso',
    'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais',
    'PA' => 'Pará',
    'PB' => 'Paraíba',
    'PR' => 'Paraná',
    'PE' => 'Pernambuco',
    'PI' => 'Piauí',
    'RJ' => 'Rio de Janeiro',
    'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul',
    'RO' => 'Rondônia',
    'RR' => 'Roraima',
    'SC' => 'Santa Catarina',
    'SP' => 'São Paulo',
    'SE' => 'Sergipe',
    'TO' => 'Tocantins'
];
```

**Exemplo de utilização num Select:**

```php
{{ Form::bsSelect('estados', 'Estados', \App\Http\Controller::ESTADOS_BRASILEIROS) }}
```

## <a name="libraries"></a> Bibliotecas utilizadas

* [AdminLTE 3](https://adminlte.io/themes/dev/AdminLTE/index.html)
* [FontAwesome 5](https://fontawesome.com/)
* [Bootstrap 4](https://getbootstrap.com/)
* [jQuery Validation](https://jqueryvalidation.org/)
* [Select2](https://select2.org/)
* [jQuery MaskMoney](https://github.com/plentz/jquery-maskmoney)
* [jQuery MaskPlugin](https://github.com/igorescobar/jQuery-Mask-Plugin)
* [Moment.js](https://momentjs.com/)
* [Numeral.js](http://numeraljs.com/)
* [jQueryUI](https://jqueryui.com/)
* [Summernote](https://summernote.org/)
* [Chart.js](https://www.chartjs.org/)
* [BootstrapForms](https://github.com/agenciamaior/bootstrap_forms)