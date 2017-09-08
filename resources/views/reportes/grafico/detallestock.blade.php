@extends ('layouts.admin')
@section ('contenido')

    {!! Form::open(array('url'=>'/reportes/detallestock', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search')) !!}
    <div class="form-group">
        <div class="input-group">
            <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary">Buscar por Código de Barras o Nombre del Producto</button>
            </span>
        </div>
    </div>
    {{Form::close()}}
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #a94442">
                <th>Artículo</th>
                <th>Código de Barras</th>
                <th>Stock</th>
                <th>Editar Stock</th>
                </thead>
                <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tfoot>
                <tbody>
                @foreach($stock as $det)
                    <tr>
                        <td>{{$det->nombre}}</td>
                        <td>{{$det->barcode}}</td>
                        <td>{{$det->stock}}</td>
                        <td><a href="{{URL::action('ReportesController@volveracero',$det->idarticulo)}}"><button class="btn btn-primary">Volver a cero</button></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $stock->render() !!}
    </div>
@endsection
@push('scripts')
<script>

</script>
@endpush
