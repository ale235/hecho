@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Balance de Hoy</h3>
            <a href="{{URL::action('BalanceController@balanceHastaElDiaDeHoy')}}"><button class="btn btn-success pull-right">Balance hasta Hoy<i class="fa fa-file-excel-o"></i></button></a>
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
            <form class="form-horizontal" role="form" method="GET" action="{{ url('/balance') }}" >
                <div class="container">
                    <div class="form-group">
                        <select name="fbalance" id="fbalance" class="selectpicker" data-live-search="true">
                            <option value="0" disabled="true" selected="true">Seleccione la Fecha
                            </option>
                            @foreach($fechasDeBalances as $fechasDeBalance)
                                <option value="{{$fechasDeBalance->fecha}}">{{$fechasDeBalance->fecha}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
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
                {{--@foreach($balances as $balance)--}}
                    <li>
                    <!-- todo text -->
                        <span class="text">Fecha de Inicio del Balance: {{$balance->fecha}}</span>
                        <!-- Emphasis label -->
                        <div class="tools">
                            <a href="{{URL::action('ArqueoController@edit',$balance->idbalance)}}"><i class="fa fa-edit"></i></a>
                            <a href="" data-target="#modal-delete-{{$balance->idbalance}}" data-toggle="modal"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </li>
                    <hr>
                    <h3>Ingresos</h3>
                    <li>
                        <!-- todo text -->
                        <span class="text">Capital Inicial</span>
                        <!-- Emphasis label -->
                        <small class="label label-success pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$balance->capitalinicial}}</small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools">
                            <a href="{{URL::action('ArqueoController@edit',$balance->idbalance)}}"><i class="fa fa-edit"></i></a>
                            <a href="" data-target="#modal-delete-{{$balance->idbalance}}" data-toggle="modal"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </li>
                    @foreach($arqueo as $a)
                    <li>
                        <!-- todo text -->
                        <span class="text">{{$a->descripcion}}</span>
                        <!-- Emphasis label -->
                        <small class="label label-success pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$a->monto}}</small>
                        <!-- General tools such as edit or delete-->
                    </li>
                    @endforeach
                    <li>
                        <!-- todo text -->
                        <span class="text">Ventas</span>
                        <!-- Emphasis label -->
                        <small class="label label-success pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$ventas}}</small>
                        <!-- General tools such as edit or delete-->
                    </li>
                    <hr>
                    <h3>Egresos</h3>
                    @foreach($pagos as $p)
                        <li>
                            <!-- todo text -->
                            <span class="text">{{$p->descripcion}}</span>
                            <!-- Emphasis label -->
                            <small class="label label-danger pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$p->monto}}</small>
                            <!-- General tools such as edit or delete-->
                        </li>
                    @endforeach
                    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modal-delete-{{$balance->idbalance}}">
                        {{Form::open(array('action'=>array('BalanceController@destroy', $balance->idbalance), 'method'=>'delete'))}}
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                    <h4 class="modal-title">Borrar Capital</h4>
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
                {{--@endforeach--}}
            </ul>
            <hr>
            <ul class="todo-list">
                    <li>

                        <!-- todo text -->
                        <span class="text">Total</span>
                        <!-- Emphasis label -->
                        @if($total >= 0)
                            <small class="label label-success pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$total}}</small>
                        @else
                            <small class="label label-danger pull-right" style="font-size: 15px"><i class="fa fa-money"></i> {{$total}}</small>
                        @endif
                        <!-- General tools such as edit or delete-->
                    </li>
            </ul>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix no-border">
            <a href="balance/create"><button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Capital Inicial</button></a>
        </div>
    </div>
@endsection

@push ('scripts')
 <script>
</script>
@endpush