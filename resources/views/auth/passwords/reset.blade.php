<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Restablecer Contraseña | Panadería</title>
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <link rel="stylesheet" href="{{ url('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/font-awesome/css/font-awesome.min.css') }}">
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Restablecer</b> Contraseña</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Ingresa tu nueva contraseña</p>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Correo electrónico" value="{{ $email ?? old('email') }}" required autofocus>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @error('email')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Nueva contraseña" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @error('password')
          <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-success btn-block btn-flat">Restablecer contraseña</button>
        </div>
      </div>
    </form>
    <br>
    <a href="{{ route('login') }}">Iniciar sesión</a>
  </div>
</div>

<script src="{{ url('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>
