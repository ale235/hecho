@extends ('layouts.admin')
@section ('contenido')
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Nuevo Pago</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/pagos') }}" id="form-pago" >
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="fecha" class="col-sm-2 control-label">Fecha</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="daterange" name="daterange" readonly placeholder="Descripcion">
                    </div>
                </div>
                <div class="form-group">
                    <label for="descripcion" class="col-sm-2 control-label">Descripcion</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Monto" class="col-sm-2 control-label">Monto</label>

                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="monto" name="monto" placeholder="Monto">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Agregar</button>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
    <!-- /.box -->
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
                singleDatePicker: true,

//                startDate: start,
//                endDate: end,
            }
        );

        var path ="{{ route('autocomplete') }}";
        $("#descripcion").typeahead({
            minLength: 3,
            autoSelect: true,
            dataType: 'json',
            source: function (query, process) {

                return $.get(path, {query:query}, function (data) {
                    var nombres = data.map(function (item) {
                        return item.descripcion;
                    });
                    return process(nombres);
                })
            },
            updater:function (item,data) {
                console.log(item)
                //item = selected item
                var input = item.split(' ')

                {{--$.ajax({--}}
                {{--type:'get',--}}
                {{--url:'{!!URL::to('buscarPrecioArticuloVentasPorCodigo')!!}',--}}
                {{--data:{'codigo':input[0]},--}}
                {{--success:function(data){--}}
                {{--//console.log('success');--}}

                {{--console.log(data);--}}

                {{--$('#pprecio_venta').val(data[0].precio_venta);--}}
                {{--$('#pidarticulo').val(data.codigo);--}}
                {{--$('#pidarticuloidarticulo').val(data.idarticulo);--}}
                {{--$('#pidarticulonombre').val(data.nombre);--}}

                {{--$('#nombretemporal').text(data.nombre);--}}

                {{--},--}}
                {{--error:function(){--}}

                {{--}--}}
                {{--});--}}

            }
        });

    });

</script>
@endpush