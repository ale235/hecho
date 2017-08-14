@extends ('layouts.admin')
@section ('contenido')

    {!! Form::open(array('url'=>'/barcode', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search')) !!}
    <div class="form-group">
        <div class="input-group">
            <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary">Buscar por Código de barra</button>
            </span>
        </div>
    </div>
    {{Form::close()}}
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #a94442">
                <th>Artículo</th>
                <th>Stock</th>
                <th>Código</th>
                <th>Volver a cero</th>
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
                        <td>{{$det->stock}}</td>
                        <td>{{$det->codigo}}</td>
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
