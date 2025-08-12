@extends('welcome')

@section('contenido')
<div class="content-wrapper">

    <section class="content-header">
        <h1>Gestor de su perfil</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('Inicio') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Perfil de usuario</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar información personal</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> ¡Éxito!</h4>
                            {!! session('success') !!}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> ¡Error!</h4>
                            {{ session('error') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Errores en el formulario:</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="post" action="{{ route('ActualizarDatos') }}" enctype="multipart/form-data" id="formPerfil">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre completo *</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control input-lg" name="name" 
                                                   value="{{ old('name', auth()->user()->name) }}" 
                                                   required
                                                   pattern="[\p{L}\s\-]+"
                                                   title="Solo letras y espacios">
                                        </div>
                                        <small class="text-muted">Solo letras y espacios</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Correo electrónico *</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control input-lg" name="email" 
                                                   value="{{ old('email', auth()->user()->email) }}" 
                                                   required
                                                   pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                                   title="Ingrese un correo válido">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Nueva contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control input-lg" name="password" 
                                                   placeholder="Dejar en blanco si no desea cambiar"
                                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                                   title="Mínimo 8 caracteres con mayúscula, minúscula, número y carácter especial">
                                        </div>
                                        <small class="text-muted">Mínimo 8 caracteres con mayúscula, minúscula, número y carácter especial</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Confirmar nueva contraseña</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control input-lg" name="password_confirmation" 
                                                   placeholder="Repita la nueva contraseña">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <label>Foto de perfil</label>
                                        <div class="text-center">
                                            @if(auth()->user()->foto)
                                                <img src="{{ asset('storage/' . auth()->user()->foto) }}?v={{ time() }}" 
                                                     class="img-circle" 
                                                     style="width: 150px; height: 150px; object-fit: cover;" 
                                                     id="previewFoto">
                                            @else
                                                <img src="{{ asset('img/user-default.png') }}" 
                                                     class="img-circle" 
                                                     style="width: 150px; height: 150px; object-fit: cover;" 
                                                     id="previewFoto">
                                            @endif
                                        </div>
                                        <br>
                                        <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('foto').click()">
                                            <i class="fa fa-upload"></i> Cambiar imagen
                                        </button>
                                        <small class="text-muted">Formatos: JPEG, PNG, JPG, GIF, WEBP (Máx. 2MB)</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Último acceso</label>
                                        <p class="form-control-static">
                                            @if(auth()->user()->ultimo_login)
                                                {{ \Carbon\Carbon::parse(auth()->user()->ultimo_login)->format('d/m/Y H:i:s') }}
                                            @else
                                                No registrado
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Rol de usuario</label>
                                        <p class="form-control-static text-capitalize">
                                            {{ auth()->user()->rol ?? 'No definido' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-save"></i> Guardar cambios
                                </button>
                                <a href="{{ url('Inicio') }}" class="btn btn-default btn-lg">
                                    <i class="fa fa-times"></i> Cancelar
                                </a>
                            </div>
                            
                            <div class="callout callout-info">
                                <h4><i class="icon fa fa-info-circle"></i> Campos requeridos</h4>
                                <p>Los campos marcados con (*) son obligatorios. La contraseña solo es requerida si deseas cambiarla.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Mostrar vista previa de la imagen seleccionada
    document.getElementById('foto').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            // Validar tamaño de archivo
            if (e.target.files[0].size > 2097152) {
                alert('La imagen no debe pesar más de 2MB.');
                this.value = '';
                return;
            }
            
            // Validar dimensiones
            var img = new Image();
            img.onload = function() {
                if (this.width !== this.height) {
                    alert('La imagen debe ser cuadrada (mismo ancho que alto).');
                    document.getElementById('foto').value = '';
                    return;
                }
                
                if (this.width > 2000 || this.height > 2000) {
                    alert('La imagen no debe exceder 2000x2000 píxeles.');
                    document.getElementById('foto').value = '';
                    return;
                }
                
                // Mostrar vista previa si pasa validaciones
                var reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewFoto').src = event.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            };
            img.src = URL.createObjectURL(e.target.files[0]);
        }
    });

    // Validación en tiempo real del formulario
    document.getElementById('formPerfil').addEventListener('submit', function(e) {
        var password = document.querySelector('input[name="password"]');
        var passwordConfirmation = document.querySelector('input[name="password_confirmation"]');
        
        if (password.value !== passwordConfirmation.value) {
            alert('Las contraseñas no coinciden.');
            e.preventDefault();
            return false;
        }
        
        return true;
    });
</script>
@endsection