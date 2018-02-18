<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ADVentas | www.incanatoit.com</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href={{asset('css/bootstrap.min.css')}}>
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href={{asset('css/bootstrap-select.min.css')}}>

    <link rel="stylesheet" href={{asset('css/bootstrap-table.css')}}>
    <!-- Font Awesome -->
    <link rel="stylesheet" href={{asset('css/font-awesome.css')}}>
    <!-- Theme style -->
    <link rel="stylesheet" href={{asset('css/AdminLTE.min.css')}}>

    <link rel="stylesheet" href={{asset('css/daterangepicker.css')}}>

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href={{asset('css/_all-skins.min.css')}}>
    <link rel="apple-touch-icon" href={{asset('img/apple-touch-icon.png')}}>
    <link rel="shortcut icon" href={{asset('img/favicon.ico')}}>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>HeC</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Hecho en Candioti</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegación</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <small class="bg-red">Online</small>
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">

                                <p>
                                    {{--www.incanatoit.com - Desarrollando Software--}}
                                    {{--<small>www.youtube.com/jcarlosad7</small>--}}
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">

                                <div class="pull-right">
                                    <a href="{{url('/logout')}}" class="btn btn-default btn-flat">Cerrar</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header"></li>
                @if (Auth::user()->role == 1)
                <li class="active"><a href="{{ url('home') }}"><i class='fa fa-link'></i> <span>Reportes</span></a></li>
                @endif
                <li class="treeview">
                    <a href="#"><i class="fa fa-share"></i> <span>Gestión Interna</span><i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                            <a href="#"><i class='fa fa-folder-open'></i> <span>Almacén</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="{{ url('almacen/articulo?selectText=Activo') }}">Artículo</a></li>
                                <li><a href="{{ url('almacen/categoria?select-categoria=1') }}">Categorías</a></li>
                            </ul>
                            <a href="#"><i class='fa fa-link'></i> <span>Compras</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                {{--<li><a href="{{ url('compras/ingreso') }}">Ingreso</a></li>--}}
                                <li><a href="{{ url('compras/proveedor') }}">Proveedor</a></li>
                                <li><a href="{{ url('compras/stockminimo') }}">Stock Mínimo</a></li>
                            </ul>
                            <a href="#"><i class='fa fa-link'></i> <span>Ventas</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="{{ url('ventas/cliente') }}">Clientes</a></li>
                                <li><a href="{{ url('ventas/venta?daterange') }}">Venta</a></li>
                                {{--<li><a href="{{ url('ventas/venta/create') }}">Facturación</a></li>--}}
                            </ul>
                            <a href="{{ url('precios/actualizar') }}"><i class='fa fa-link'></i> <span>Precios</span> <i class="fa fa-angle-left pull-right"></i></a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="{{ url('ventas/venta/create') }}"><i class='fa fa-folder-open'></i> <span>Facturación</span> <i class="fa fa-angle-left pull-right"></i></a>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-share"></i> <span>Contabilidad</span><i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="treeview">
                            <a href="{{ url('pagos') }}"><i class='fa fa-link'></i> <span>Pagos</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <a href="{{ url('arqueo') }}"><i class='fa fa-link'></i> <span>Arqueos</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <a href="{{ url('balance') }}"><i class='fa fa-link'></i> <span>Balance</span> <i class="fa fa-angle-left pull-right"></i></a>
                        </li>
                          </ul>
                </li>
                @if (Auth::user()->role == 1)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>Configuración</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('seguridad/usuario') }}"><i class="fa fa-circle-o"></i> Usuarios</a></li>

                    </ul>
                </li>
                @endif
                <li>
                    <a href="#">
                        <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                        <small class="label pull-right bg-red">PDF</small>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                        <small class="label pull-right bg-yellow">IT</small>
                    </a>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>





    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sistema de Ventas</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!--Contenido-->
                                    @yield('contenido')
                                    <!--Fin Contenido-->
                                </div>
                            </div>

                        </div>
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        {{--<b>Version</b> 2.3.0--}}
    </div>
    {{--<strong>Copyright &copy; 2015-2020 <a href="www.incanatoit.com">IncanatoIT</a>.</strong> All rights reserved.--}}
</footer>


<!-- jQuery 2.1.4 -->
<script type="text/javascript" src={{asset('js/moment.js')}}></script>
<script type="text/javascript" src={{asset('js/jQuery-2.1.4.min.js')}}></script>

<script type="text/javascript" src={{asset('js/daterangepicker.js')}}></script>
<!-- Bootstrap 3.3.5 -->
@stack('scripts'))

<script src={{asset('js/bootstrap.min.js')}}></script>
<!-- Bootstrap select -->
<script src={{asset('js/bootstrap-select.min.js')}}></script>

<script src={{asset('js/jquery.bootstrap.wizard.min.js')}}></script>

<script src={{asset('js/bootstrap-table.js')}}></script>
<!-- AdminLTE App -->
<script src={{asset('js/app.min.js')}}></script>

<script src={{asset('js/bootstrap3-typeahead.js')}}></script>



</body>
</html>
