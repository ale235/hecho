@extends ('layouts.admin')
@section ('contenido')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Escojer como cambiar los precios</h3>
        </div>
        <div class="box-body">
            <a class="btn btn-app" href="{{URL::action('PrecioController@getPorArticulo')}}">
                <i class="fa fa-edit"></i> Por Artículo
            </a>
            <a class="btn btn-app" href="{{URL::action('PrecioController@getPorFamilia')}}">
                <i class="fa fa-edit"></i> Por Familia de Artículo
            </a>
        </div>
        <!-- /.box-body -->
    </div>
@endsection