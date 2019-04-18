# Laravel Boilerplate

Biblioteca para gerar um template padrão de um painel administrativo baseado em AdminLTE. Já vem com algumas funções pré-definidas, como: controle de usuários, campos com máscara, etc.

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

public static $roles = [
    self::ROLE_ADMIN => 'Administrador',
    self::ROLE_COMMON => 'Comum',
];

public function getRoleStringAttribute() {
    return self::$roles[$this->role];
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

mascaras

validacoes

i18n
js