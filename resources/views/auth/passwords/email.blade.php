<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Olvidé mi contraseña | Panadería</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link rel="stylesheet" href="{{ url('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Recuperar</b> Contraseña</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Ingresa tu correo para recibir el enlace</p>

    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @error('email')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar enlace</button>
        </div>
      </div>
    </form>

    <br>
    <a href="{{ url('/') }}">Volver al inicio de sesión</a>
  </div>
</div>

<script src="{{ url('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>
