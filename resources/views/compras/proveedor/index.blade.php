@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <h3>
                Listado de proveedores
                <a href="proveedor/create"><button class="btn btn-success">Nuevo</button></a>
            </h3>
            @include('compras.proveedor.search')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Tipo Doc</th>
                    <th>Numero Doc</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                    </thead>
                    @foreach($personas as $per)
                        <tr>
                            <td>{{$per->idpersona}}</td>
                            <td>{{$per->nombre}}</td>
                            <td>{{$per->codigo}}</td>
                            <td>{{$per->tipo_documento}}</td>
                            <td>{{$per->num_documento}}</td>
                            <td>{{$per->telefono}}</td>
                            <td>{{$per->email}}</td>
                            <td>{{$per->estado}}</td>
                            <td>
                                <a href="{{URL::action('ProveedorController@edit',$per->idpersona)}}"><button class="btn btn-info">Editar</button></a>
                                <a href="{{URL::action('ProveedorController@cambiarEstado',$per->idpersona)}}"><button class="btn btn-warning">Cambiar estado</button></a>
                                <a href="" data-target="#modal-delete-{{$per->idpersona}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
                            </td>
                        </tr>
                        @include('compras.proveedor.modal')
                    @endforeach
                </table>
            </div>
            {!! $personas->appends(['selectText' => $selectText, 'searchText' => $searchText])->render() !!}
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
    //     $('#selectText').val('select');


</script>
@endpush