@extends ('layouts.admin')
@section ('contenido')
    {!! Form::open(array('url'=>'reportes/grafico/detalleventa', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search', 'class'=>'form-horizontal', 'id'=>'elform')) !!}
    <div class="container">
        <div class="form-group">
            <label class="col-md-4 control-label">Código del artículo</label>
            <div class="col-md-4 inputGroupContainer">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control" id="daterange" name="daterange"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Acción</label>
            <div class="col-md-4 inputGroupContainer">
                <div class="input-group">
               <span class="input-group-btn">
            <button id="submit_ventas" type="submit" class="btn btn-primary">Filtrar</button>
                </span>
                </div>
            </div>
        </div>
    </div>
    {{--<input type="text" name="daterange"/>--}}
    {{Form::close()}}

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #a94442">
                <th>Fecha venta</th>
                <th>Total compra</th>
                <th>Total venta</th>
                <th>Total venta real</th>
                </thead>
                <tfoot>
                <th></th>
                <th><h4 id="total_compra">{{$venta->sum('total_compra')}}</h4></th>
                <th><h4 id="total_venta">{{$venta->sum('total_venta')}}</h4></th>
                <th><h4 id="total_venta_real">{{$venta->sum('total_venta_real')}}</h4></th>
                </tfoot>
                <tbody>
                @foreach($venta as $det)
                    <tr>
                        <td>{{$det->fecha_hora}}</td>
                        <td>{{$det->total_compra}}</td>
                        <td>{{$det->total_venta}}</td>
                        <td>{{$det->total_venta_real}}</td>
                        {{--<td><a href="{{URL::action('ReportesController@volveracero',$det->idarticulo)}}"><button class="btn btn-primary">Volver a cero</button></a></td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
//        var start = moment();
//        var end = moment();
//        var d = new Date();
//        d.setHours(0,0,0);
        $('input[name="daterange"]').daterangepicker(
            {
                locale: {
//                    useCurrent: false,
                    format: 'YYYY-MM-DD',
//                    defaultDate: d
                },

//                startDate: start,
//                endDate: end,
            }
        );

    });

    var val = getURLParameter('daterange');
    $('#daterange').val(val);

    function getURLParameter(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
    }



</script>
@endpush
