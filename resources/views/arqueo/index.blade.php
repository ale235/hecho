@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Arqueos de Hoy</h3>
            <a href="articulo/create" class="btn btn-app pull-right">
                <span class="badge bg-green"></span>
                <i class="fa fa-barcode"></i> Ver Arqueos Anteriores
            </a>
            {{--<a  href="{{URL::action('ArticuloController@getPorCodigo')}}" class="btn btn-app pull-right">--}}
                {{--<span class="badge bg-green"></span>--}}
                {{--<i class="fa fa-barcode"></i> Ingresar Producto por Codigo--}}
            {{--</a>--}}
            {{--<a class="btn btn-app pull-right">--}}
                {{--<span class="badge bg-green"></span>--}}
                {{--<i class="fa fa-file-excel-o"></i> Exportar Pagos--}}
            {{--</a>--}}
        </div>
        @if(count($errors)>0)
            <div class="alert alert-danger">
                <u>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </u>
            </div>
        @endif
        <!-- /.box-header -->
        <div class="box-body">
            <form class="form-horizontal" role="form" method="GET" action="{{ url('/arqueo') }}" >
                <div class="container">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Fecha a Filtrar</label>
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
            </form>
            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <ul class="todo-list">
                @foreach($arqueos as $arqueo)
                    <li>

                        <!-- todo text -->
                        <span class="text">{{$arqueo->descripcion}}</span>
                        <!-- Emphasis label -->
                        <small class="label label-default pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$arqueo->monto}}</small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools">
                            <a href="{{URL::action('ArqueoController@edit',$arqueo->idarqueo)}}"><i class="fa fa-edit"></i></a>
                            <a href="" data-target="#modal-delete-{{$arqueo->idarqueo}}" data-toggle="modal"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </li>
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{$arqueo->idarqueo}}">
                        {{Form::open(array('action'=>array('ArqueoController@destroy', $arqueo->idarqueo), 'method'=>'delete'))}}
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                    <h4 class="modal-title">Cancelar Arqueo</h4>
                                </div>
                                <div class="modal-body">
                                    <p> ¿Está seguro que desea cancelar este ingreso?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"> Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar</button>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                @endforeach
            </ul>
            <hr>
            <ul class="todo-list">
                    <li>

                        <!-- todo text -->
                        <span class="text">Total</span>
                        <!-- Emphasis label -->
                        <small class="label label-default pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$total}}</small>
                        <!-- General tools such as edit or delete-->
                    </li>
            </ul>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix no-border">
            <a href="arqueo/create"><button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Agregar Arqueo</button></a>
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
     var val = getURLParameter('daterange');
     $('#daterange').val(val);

     function getURLParameter(name) {
         return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
     }


</script>
@endpush