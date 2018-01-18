@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Editar Pago</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form  class="form-horizontal" role="form" action="{{ route('pagos.update', $pago->idpago) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
        {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/pagos/update') }}" id="form-pago" >--}}
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="fecha" class="col-sm-2 control-label">Fecha</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="daterange" name="daterange" value="{{$pago->daterange}}" placeholder="Descripcion">
                    </div>
                </div>
                <div class="form-group">
                    <label for="descripcion" class="col-sm-2 control-label">Descripcion</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{$pago->descripcion}}" placeholder="Descripcion">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Monto" class="col-sm-2 control-label">Monto</label>

                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="monto" name="monto" value="{{$pago->monto}}" placeholder="Monto">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Modificar</button>
            </div>
            <!-- /.box-footer -->
        </form>
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
                singleDatePicker: true,

//                startDate: start,
//                endDate: end,
            }
        );

    });

</script>
@endpush