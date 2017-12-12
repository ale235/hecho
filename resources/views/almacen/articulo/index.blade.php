@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Listado de Artículos</h3>
            <a href="articulo/create" class="btn btn-app pull-right">
                <span class="badge bg-green"></span>
                <i class="fa fa-barcode"></i> Ingresar Producto
            </a>
            <a  href="{{URL::action('ArticuloController@getPorCodigo')}}" class="btn btn-app pull-right">
                <span class="badge bg-green"></span>
                <i class="fa fa-barcode"></i> Ingresar Producto por Codigo
            </a>
            <a class="btn btn-app pull-right">
                <span class="badge bg-green"></span>
                <i class="fa fa-file-excel-o"></i> Exportar Resultado
            </a>
        </div>
        @if(count($errors)>0)
            <div class="alert alert-danger">
                <u>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </u>
            </div>
        @endif
        @include('almacen.articulo.search')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                        <th>Nombre</th>
                        <th>Código de Barras</th>
                        @if (Auth::user()->role == 1)
                            <th>Stock</th>
                        @endif
                        <th>Codigo</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                        </thead>
                        @foreach($articulos as $art)
                            <tr>
                                <td>{{$art->nombre}}</td>
                                <td>{{$art->barcode}}</td>
                                @if (Auth::user()->role == 1)
                                    <th>{{$art->stock}}</th>
                                @endif
                                <td>{{$art->codigo}}</td>
                                <td>{{$art->ultimoprecio}}</td>
                                <td>{{$art->categoria}}</td>
                                <td>{{$art->estado}}</td>
                                <td>
                                    <a href="{{URL::action('ArticuloController@edit',$art->idarticulo)}}"><button class="btn btn-info">Editar</button></a>
                                    <a href="{{URL::action('ArticuloController@cambiarEstadoArticulo',$art->idarticulo)}}"><button class="btn btn-warning">Cambiar estado</button></a>
                                    <a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                                </td>
                            </tr>
                            @include('almacen.articulo.modal')
                        @endforeach
                    </table>
                </div>
                {!! $articulos->appends(['selectText' => $selectText, 'searchText' => $searchText, 'searchText2' => $searchText2])->render() !!}
            </div>
        </div>
    </div>
@endsection

@push ('scripts')
 <script>
     var val = getURLParameter('selectText');
     $('#selectText').val(val);

     function getURLParameter(name) {
         return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
     }



</script>
@endpush