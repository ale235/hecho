@extends ('layouts.admin')
@section ('contenido')
    <style type="text/css">
        img{
            padding-left: 20px;
        }
    </style>
    {!! Form::open(array('url'=>'/barcode', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search')) !!}
    <div class="form-group">
        <div class="input-group">
            <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}">
            <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </span>
        </div>
    </div>
    {{Form::close()}}

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <th>Nombre</th>
                    <th>Codigo</th>
                    <th>Código de Barras</th>
                    </thead>
                    @foreach($articulo as $art)
                        <tr>
                            <td>{{$art->nombre}}</td>
                            <td>{{$art->codigo}}</td>
                            <td> <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($art->barcode, 'EAN13')}}" alt="barcode" /></td>
                            {{--<td>{{DNS1D::getBarcodePNG($art->barcode, "S25")}}</td>--}}
                        </tr>
                        @include('almacen.articulo.modal')
                    @endforeach
                </table>
            </div>
            {{--{!! $articulos->appends(['selectText' => $selectText, 'searchText' => $searchText, 'searchText2' => $searchText2])->render() !!}--}}
            {{$articulo->render()}}
        </div>
    </div>

@endsection
@push ('scripts')
<script>

</script>
@endpush