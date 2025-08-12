<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Confirmar contraseña | Panadería</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link rel="stylesheet" href="{{ url('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Confirmar</b> Contraseña</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Por seguridad, confirma tu contraseña</p>

    @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
      @csrf

      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Contraseña actual" required autofocus>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @error('password')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-warning btn-block btn-flat">Confirmar</button>
        </div>
      </div>
    </form>

    <br>
    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
  </div>
</div>

<script src="{{ url('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>
