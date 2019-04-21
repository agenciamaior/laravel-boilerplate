<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Laravel Boilerplate</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  @yield('adminlte_css')
</head>

<body class="hold-transition sidebar-mini @yield('body_class')">
  @yield('body')

  <script src="{{ asset('js/app.js') }}"></script>

  @yield('adminlte_js')
</body>

</html>