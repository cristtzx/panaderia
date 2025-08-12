<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro | La caserita</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts - Pacifico -->
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <style type="text/css">
    .register-page {
      background: linear-gradient(135deg, #f5d7b3 0%, #e8b25f 100%);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Roboto, sans-serif;
    }

    .register-page #back {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('storage/plantilla/fondo.jpg') center/cover no-repeat;
      opacity: 0.1;
      z-index: 0;
    }

    .register-box {
      width: 420px;
      background: rgba(255, 255, 255, 0.98);
      border-radius: 15px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      z-index: 1;
      transition: transform 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .register-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    .register-logo {
      text-align: center;
      margin: 30px 0 20px;
    }

    .register-logo a {
      font-size: 32px;
      font-weight: 700;
      color: #d35400;
      text-decoration: none;
      font-family: 'Pacifico', cursive;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
    }

    .register-box-body {
      padding: 30px 40px;
    }

    .register-box-msg {
      margin: 0 0 25px;
      text-align: center;
      color: #7f8c8d;
      font-size: 18px;
      font-weight: 500;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
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

    .btn-register {
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

    .btn-register:hover {
      background-color: #e67e22;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
    }

    .btn-register:active {
      transform: translateY(0);
    }

    .register-links {
      text-align: center;
      margin-top: 20px;
    }

    .register-links a {
      color: #d35400;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s;
      display: inline-block;
      margin: 0 10px;
    }

    .register-links a:hover {
      color: #e67e22;
      text-decoration: none;
      transform: translateY(-1px);
    }

    .error-feedback {
      color: #e74c3c;
      font-size: 0.85rem;
      margin-top: 5px;
      font-weight: 500;
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50px;
      cursor: pointer;
      color: #95a5a6;
      z-index: 2;
    }

    .password-strength {
      height: 4px;
      background: #e0e0e0;
      margin-top: 8px;
      border-radius: 2px;
      overflow: hidden;
    }

    .strength-bar {
      height: 100%;
      width: 0%;
      transition: width 0.3s, background 0.3s;
    }

    .form-text {
      font-size: 0.75rem;
      color: #95a5a6;
      margin-top: 5px;
    }

    .terms-check {
      display: flex;
      align-items: center;
      margin-top: 15px;
    }

    .terms-check input {
      margin-right: 10px;
    }

    .terms-check label {
      color: #7f8c8d;
      font-size: 0.9rem;
    }

    .terms-check a {
      color: #d35400;
      text-decoration: none;
      font-weight: 500;
    }

    .terms-check a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-page">
    <div id="back"></div>
    <div class="register-box">
      <div class="register-logo">
        <a href="#"><i class="fas fa-bread-slice"></i> La caserita</a>
      </div>
      <div class="register-box-body">
        <p class="register-box-msg">Regístrate para comenzar</p>
        
        <form id="registroForm" method="POST" action="{{ route('register') }}" novalidate>
          @csrf
          
          <div class="form-group">
            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" required>
            <div id="error-name" class="error-feedback"></div>
          </div>
          
          <div class="form-group">
            <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
            <div id="error-email" class="error-feedback"></div>
          </div>
          
          <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            <div class="password-strength">
              <div class="strength-bar" id="strengthBar"></div>
            </div>
            <small class="form-text">Mínimo 8 caracteres con mayúsculas, números y símbolos</small>
            <div id="error-password" class="error-feedback"></div>
          </div>
          
          <div class="form-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" required>
            <i class="fas fa-eye password-toggle" id="togglePasswordConfirm"></i>
            <div id="error-password-confirm" class="error-feedback"></div>
          </div>
          
          <div class="terms-check">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">Acepto los <a href="#">términos y condiciones</a></label>
          </div>
          <div id="error-terms" class="error-feedback"></div>
          
          <button type="submit" class="btn btn-register">
            <i class="fas fa-user-plus"></i> CREAR CUENTA
          </button>
        </form>
        
        <div class="register-links">
          <a href="{{ url('Inicio') }}"><i class="fas fa-sign-in-alt"></i> Ya tengo cuenta</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
    });
    
    togglePasswordConfirm.addEventListener('click', function() {
      const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordConfirmInput.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
    });
    
    // Validación de fortaleza de contraseña
    passwordInput.addEventListener('input', function() {
      const strengthBar = document.getElementById('strengthBar');
      const password = this.value;
      let strength = 0;
      
      // Validar longitud
      if (password.length >= 8) strength += 1;
      
      // Validar mayúsculas
      if (/[A-Z]/.test(password)) strength += 1;
      
      // Validar números
      if (/[0-9]/.test(password)) strength += 1;
      
      // Validar caracteres especiales
      if (/[^A-Za-z0-9]/.test(password)) strength += 1;
      
      // Actualizar barra de fortaleza
      switch(strength) {
        case 0:
          strengthBar.style.width = '0%';
          strengthBar.style.background = '#e74c3c';
          break;
        case 1:
          strengthBar.style.width = '25%';
          strengthBar.style.background = '#e74c3c';
          break;
        case 2:
          strengthBar.style.width = '50%';
          strengthBar.style.background = '#f39c12';
          break;
        case 3:
          strengthBar.style.width = '75%';
          strengthBar.style.background = '#f39c12';
          break;
        case 4:
          strengthBar.style.width = '100%';
          strengthBar.style.background = '#2ecc71';
          break;
      }
    });
    
    // Validación del formulario
    document.getElementById('registroForm').addEventListener('submit', function(e) {
      let isValid = true;
      const name = document.getElementById('name');
      const email = document.getElementById('email');
      const password = document.getElementById('password');
      const passwordConfirm = document.getElementById('password_confirmation');
      const terms = document.getElementById('terms');
      
      // Limpiar errores
      document.querySelectorAll('.error-feedback').forEach(el => el.textContent = '');
      
      // Validar nombre (3-50 caracteres)
      if (name.value.trim().length < 3 || name.value.trim().length > 50) {
        document.getElementById('error-name').textContent = 'El nombre debe tener entre 3 y 50 caracteres';
        isValid = false;
      }
      
      // Validar email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email.value)) {
        document.getElementById('error-email').textContent = 'Por favor ingresa un correo válido';
        isValid = false;
      }
      
      // Validar contraseña (mínimo 8 caracteres, 1 mayúscula, 1 número, 1 especial)
      const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
      if (!passwordRegex.test(password.value)) {
        document.getElementById('error-password').textContent = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial';
        isValid = false;
      }
      
      // Validar confirmación
      if (password.value !== passwordConfirm.value) {
        document.getElementById('error-password-confirm').textContent = 'Las contraseñas no coinciden';
        isValid = false;
      }
      
      // Validar términos
      if (!terms.checked) {
        document.getElementById('error-terms').textContent = 'Debes aceptar los términos y condiciones';
        isValid = false;
      }
      
      if (!isValid) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>