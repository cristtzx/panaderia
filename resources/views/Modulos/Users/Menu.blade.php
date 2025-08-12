<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- /.search form -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <!-- Inicio -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Inicio') }}">
            <i class="fa fa-home"></i> 
            <span>Inicio</span>
          </a>
        </li>
      </ul>

      <!-- Usuarios -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Usuarios') }}">
            <i class="fa fa-users"></i> 
            <span>Usuarios</span>
          </a>
        </li>
      </ul>

      <!-- Sucursales -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Sucursales') }}">
            <i class="fa fa-building"></i> 
            <span>Sucursales</span>
          </a>
        </li>
      </ul>

      <!-- Ingredientes -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Ingredientes') }}">
            <i class="fa fa-th"></i> 
            <span>Ingredientes</span>
          </a>
        </li>
      </ul>

      <!-- Categorías -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Categorias') }}">
            <i class="fa fa-tags"></i> 
            <span>Categorías</span>
          </a>
        </li>
      </ul>

      <!-- Recetas -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Recetarios') }}">
            <i class="fa fa-book"></i>
            <span>Recetas</span>
          </a>
        </li>
      </ul>

      <!-- Productos -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('Productos') }}">
            <i class="fa fa-shopping-basket"></i> 
            <span>Productos</span>
          </a>
        </li>
      </ul>

      <!-- Ventas -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('ventas') }}">
            <i class="fa fa-cash-register"></i> 
            <span>Ventas</span>
          </a>
        </li>
      </ul>

      <!-- Plan Semanal -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>
          <a href="{{ url('plan-semanal') }}">
            <i class="fa fa-calendar-alt"></i> 
            <span>Plan Semanal</span>
          </a>
        </li>
      </ul>

    </section>
    <!-- /.sidebar -->
</aside>
