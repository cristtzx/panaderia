 <header class="main-header">
    <!-- Logo -->
    <a href="{{ url('Inicio') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">

        <img src="{{ url('storage/plantilla/pan.png') }}" class="img-responsive" style="padding: 0px; width: 120px; height: auto;">

      </span>

      <span class="logo-lg">
          <b>Caserita</b>Inv
          <img src="{{ url('storage/plantilla/pan.png') }}" class="img-responsive" style="padding-left: 10px; width: 75px; height: auto; display: inline;">
      </span>

    </a>



    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

             @if(auth()->user()->foto == '')

              <img src="{{ auth()->user()->foto }}" class="user-image" alt="User Image">

             @else

              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">

             @endif

              
              <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ url('Mis-Datos') }}" class="btn btn-primary btn-flat">Mis Datos</a>
                </div>
              <div class="pull-right">
                <a href="{{ route('logout') }}" class="btn btn-danger btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fa fa-sign-out"></i> Salir
               </a>
              </div>

                <form method="post" id="logout-form" action="{{ route ('logout') }}">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>