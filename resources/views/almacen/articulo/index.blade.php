@extends ('layouts.admin')
@section ('contenido')
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <h3>
            Listado de Artículos
            <a href="articulo/create"><button class="btn btn-success">Nuevo</button></a>
            <a href="{{URL::action('ArticuloController@exportArticulo',$selectText)}}"><button class="btn btn-success">Exportar Resultado</button></a>
        </h3>

    </div>
 </div>
<div class="row">
    @include('almacen.articulo.search')
</div>
<div class="row">
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="table-responsive">
             <table class="table table-striped table-bordered table-condensed table-hover">
                 <thead>
                     <th>Nombre</th>
                     <th>Codigo</th>
                     <th>Precio</th>
                     <th>Categoría</th>
                     <th>Estado</th>
                     <th>Opciones</th>
                 </thead>
                 @foreach($articulos as $art)
                 <tr>
                     <td>{{$art->nombre}}</td>
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