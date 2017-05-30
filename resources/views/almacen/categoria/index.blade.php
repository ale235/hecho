@extends ('layouts.admin')
@section ('contenido')
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>
            Listado de Categorías
            <a href="categoria/create"><button class="btn btn-success">Nuevo</button></a>
        </h3>
        @include('almacen.categoria.search')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Opciones</th>
                </thead>
                @foreach($categorias as $cat)
                <tr>
                    <td>{{$cat->nombre}}</td>
                    <td>{{$cat->descripcion}}</td>
                    <td>
                        <a href="{{URL::action('CategoriaController@edit',$cat->idcategoria)}}"><button class="btn btn-info">Editar</button></a>
                        <a href="{{URL::action('CategoriaController@editarEstado',$cat->idcategoria)}}"><button class="btn btn-warning">Cambiar estado</button></a>
                        <a href="" data-target="#modal-delete-{{$cat->idcategoria}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                    </td>
                </tr>
                @include('almacen.categoria.modal')
                @endforeach
            </table>
        </div>
        {{--{{$categorias->render()}}--}}
        {!! $categorias->appends(['select-categoria' => $cat->condicion, 'searchText' => $searchText])->render() !!}
    </div>
</div>
@endsection
@push ('scripts')
<script>
    var val = getURLParameter('select-categoria');
    $('#select-categoria').val(val);

    function getURLParameter(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
    }
    //     $('#selectText').val('select');


</script>
@endpush