@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Latest Orders</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! Form::open(array('url'=>'compras/stockminimo', 'method'=>'POST', 'autocomplete'=>'off', 'id'=>'myForm'))!!}
            {{Form::token()}}
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Código de Barras</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Stock mínimo</th>
                            {{--<th class="text-center">¿Es Stock Mínimo?</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articulos as $art)
                            <tr id="row{{$loop->index}}">
                                <td style="display: none;">
                                    <input name="idarticulo[]" type="text" value="{{$art->idarticulo}}">
                                </td>
                                <td>{{$art->nombre}}</td>
                                <td>{{$art->barcode}}</td>
                                <td>{{$art->ultimoprecio}}</td>
                                <td>{{$art->stock}}</td>
                                <td>
                                    @if($art->stock_minimo != null)
                                        <input class="form-control input-sm" name="stockminimo[]" type="text" value="{{$art->stock_minimo}}">
                                    @else
                                        <input class="form-control input-sm" name="stockminimo[]" type="text" placeholder="El stock mínimo, por defecto es: {{$art->stock}}">
                                    @endif
                                </td>
                                {{--<td class="text-center">--}}
                                    {{--<input type="hidden" name="checkActivo[]" value="0" />--}}
                                    {{--<input type="checkbox" name="checkActivo[]" value="1" />--}}
                                    {{--<input checked="checked" name="checkActivo[]" type="checkbox"/>--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</button>
        {!! Form::close()!!}

            <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            {{--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>--}}
            {{--<a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>--}}
        </div>
        <!-- /.box-footer -->
    </div>
@endsection
@push ('scripts')
<script>
</script>
@endpush