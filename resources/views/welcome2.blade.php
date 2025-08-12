<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Panadería | Dashboard</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- CSS -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="{{ url('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ url('bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ url('dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ url('dist/css/skins/_all-skins.min.css') }}">
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="{{ url('bower_components/morris.js/morris.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/jvectormap/jquery-jvectormap.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ url('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('bower_components/datatables.net-bs/css/responsive.bootstrap.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Google Font -->
  

  <!-- HTML5 Shim y Respond.js para IE8 -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini login-page">

@if(auth::user())
  <div class="wrapper">
    @include('Modulos/users/cabezera')
    @include('Modulos/users/Menu')
    @yield('contenido')
  </div>
@else
  @yield('ingresar')
@endif

<!-- SCRIPTS -->
<!-- Core JS -->
<script src="{{ url('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Plugins JS -->
<!-- DataTables -->
<script src="{{ url('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ url('bower_components/datatables.net-bs/js/dataTables.responsive.min.js') }}"></script>
<!-- Morris -->
<script src="{{ url('bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ url('bower_components/morris.js/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ url('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jQuery Knob -->
<script src="{{ url('bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<!-- Date/Time -->
<script src="{{ url('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- UI -->
<script src="{{ url('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- AdminLTE -->
<script src="{{ url('dist/js/adminlte.min.js') }}"></script>
<script src="{{ url('dist/js/pages/dashboard.js') }}"></script>
<script src="{{ url('dist/js/demo.js') }}"></script>




<!-- Configuración -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- JS Personalizado -->
<script src="{{ url('js/plantilla.js') }}"></script>

</body>
</html>

