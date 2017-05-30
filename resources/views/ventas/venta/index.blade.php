@extends ('layouts.admin')
@section ('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3>
            Listado de Ventas
        </h3>
        <a href="venta/create"><button class="btn btn-success pull-left">Nueva Venta</button></a>
        @if (Auth::user()->role == 1)
        <a href="{{URL::action('VentaController@exportResultado',$date)}}"><button class="btn btn-success pull-right">Exportar Resultado <i class="fa fa-file-excel-o"></i></button></a>
        <a href="{{URL::action('VentaController@exportDetalle',$date)}}"><button class="btn btn-success pull-right">Exportar Resultado con Detalle<i class="fa fa-file-excel-o"></i></button></a>
        <a href="{{URL::action('VentaController@cajaDelDia')}}"><button class="btn btn-success pull-right">Caja del día<i class="fa fa-file-excel-o"></i></button></a>
        @endif
        @include('ventas.venta.search')
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                @foreach($ventas as $vent)
                <tr>
                    <td>{{$vent->fecha_hora}}</td>
                    <td>{{$vent->nombre}}</td>
                    <td>{{$vent->total_venta_real}}</td>
                    <td>{{$vent->estado}}</td>
                    <td>
                        <a href="{{URL::action('VentaController@show',$vent->idventa)}}"><button class="btn btn-primary">Detalles</button></a>
                        {{--<a href="{{URL::action('VentaController@edit',$vent->idventa)}}"><button class="btn btn-info">Editar</button></a>--}}
                        <a href="" data-target="#modal-delete-{{$vent->idventa}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
                    </td>
                </tr>
                @include('ventas.venta.modal')
                @endforeach
            </table>
        </div>
        {{$ventas->render()}}
        {{--{!! $articulos->appends(['selectText' => $selectText, 'searchText' => $searchText, 'searchText2' => $searchText2])->render() !!}--}}
    </div>
</div>
@endsection
@push ('scripts')
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
    $('#bt_add').click(function () {
        console.log($('input[name="daterange"]').val())
    });
    var val = getURLParameter('daterange');
    $('#daterange').val(val);

    function getURLParameter(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
    }




</script>
@endpush
