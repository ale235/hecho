@extends ('layouts.admin')
@section ('contenido')
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Nuevo Balance</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/balance') }}" id="form-balance" >
            {{ csrf_field() }}
            <div class="box-body">
                <div class="form-group">
                    <label for="fecha" class="col-sm-2 control-label">Fecha</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="daterange" name="daterange" readonly placeholder="Descripcion">
                    </div>
                </div>
                <div class="form-group">
                    <label for="descripcion" class="col-sm-2 control-label">Retiro de Capital</label>

                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="retirodecapital" name="retirodecapital" placeholder="Retiro de Capital">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Monto" class="col-sm-2 control-label">Capital Inicial</label>

                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="capitalinicial" name="capitalinicial" placeholder="Capital Inicial">
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

    });

</script>
@endpush