@extends('welcome')

@section('ingresar')
<style type="text/css">
  .login-page,
  .register-page {
    background: linear-gradient(135deg, #f5d7b3 0%, #e8b25f 100%);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Roboto, sans-serif;
  }

  .login-page #back {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('storage/plantilla/fondo.jpg') center/cover no-repeat;
    opacity: 0.1;
    z-index: 0;
  }

  .login-box {
    width: 420px;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    z-index: 1;
    transition: transform 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  .login-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
  }

  .login-logo {
    text-align: center;
    margin: 30px 0 20px;
  }

  .login-logo a {
    font-size: 32px;
    font-weight: 700;
    color: #d35400;
    text-decoration: none;
    font-family: 'Pacifico', cursive;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
  }

  .login-box-body {
    padding: 30px 40px;
  }

  .login-box-msg {
    margin: 0 0 25px;
    text-align: center;
    color: #7f8c8d;
    font-size: 18px;
    font-weight: 500;
  }

  .form-group {
    margin-bottom: 25px;
  }

  .form-control {
    height: 52px;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding-left: 20px;
    font-size: 16px;
    transition: all 0.3s;
    background-color: #f9f9f9;
  }

  .form-control:focus {
    border-color: #d35400;
    box-shadow: 0 0 0 3px rgba(211, 84, 0, 0.1);
    background-color: #fff;
  }

  .btn-login {
    background-color: #d35400;
    border: none;
    height: 54px;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    text-transform: none;
    margin-top: 10px;
    width: 100%;
    color: white;
  }

  .btn-login:hover {
    background-color: #e67e22;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
  }

  .btn-login:active {
    transform: translateY(0);
  }

  .social-auth-links {
    text-align: center;
    margin: 25px 0;
  }

  .social-auth-links p {
    color: #95a5a6;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    text-align: center;
    font-size: 14px;
  }

  .social-auth-links p:before,
  .social-auth-links p:after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e0e0e0;
    margin: 0 15px;
  }

  .btn-google {
    background-color: #dd4b39;
    color: white;
    border-radius: 10px;
    height: 50px;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
  }

  .btn-google:hover {
    background-color: #c23321;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(221, 75, 57, 0.3);
  }

  .btn-google i {
    font-size: 20px;
    margin-right: 10px;
  }

  .login-links {
    text-align: center;
    margin-top: 20px;
  }

  .login-links a {
    color: #d35400;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-block;
    margin: 0 10px;
  }

  .login-links a:hover {
    color: #e67e22;
    text-decoration: none;
    transform: translateY(-1px);
  }

  .form-control-feedback {
    color: #d35400;
    line-height: 52px;
  }
</style>

<div id="back"></div>

<div class="login-box">
  <div class="login-logo">
  </div>
  
  <div class="login-box-body">
    <p class="login-box-msg">Inicia sesión en tu cuenta</p>

    <form action="{{ route('login') }}" method="post">

     @csrf

      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <div class="alert alert-danger mt-2" id="email-error" style="display: none;">
           Por favor ingresa un correo electrónico válido
        </div>
      </div>

      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Contraseña" required minlength="3">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          <div class="alert alert-danger mt-2" id="password-error" style="display: none;">
           La contraseña debe tener al menos 6 caracteres
          </div>
      </div>


      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
             <input type="checkbox" name="remember" id="remember">
              <label for="remember">Recordar sesión</label>
            </label>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-login">INICIAR SESIÓN</button>
    </form>

    <div class="social-auth-links">
      <p>o ingresa con</p>
      <a href="{{ route('google.login') }}" class="btn btn-google">
        <i class="fa fa-google"></i> Google
      </a>
    </div>

    
    <!-- Mensaje de estado desactivado (más arriba del login-links y bien visible) -->
      @if($errors->has('estado'))
        <div class="alert alert-danger text-center" style="margin-top: 15px; font-weight: bold; border-radius: 5px;">
          <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
          {{ $errors->first('estado') }}
        </div>
      @endif

      <div class="login-links d-flex justify-content-between mt-3">
        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        <a href="{{ route('register') }}" class="text-center">Registrarse</a>
      </div>


  </div>
</div>

<!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
  });
</script>
@endsection

