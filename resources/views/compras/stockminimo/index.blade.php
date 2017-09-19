@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Estado del Stock Mínimo<br><br></h3>
            {{--@if($porcentaje != 0)--}}
             @if($porcentaje <=25)
             <div id="myAlert" class="alert alert-danger alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h4><i class="icon fa fa-ban"></i> COMPRA!!!</h4>
                 Demasiados productos están por debajo del Stock Mínimo. Hacer la compra urgente
             </div>
             @elseif($porcentaje > 25 && $porcentaje <= 50)
             <div class="alert alert-warning alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h4><i class="icon fa fa-warning"></i> ATENTO!!!</h4>
                 Muchos productos están por debajo del Stock Mínimo. Evaluar que productos comprar.
             </div>
             @elseif($porcentaje > 50 && $porcentaje <= 75)
             <div class="alert alert-info alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h4><i class="icon fa fa-info"></i> Bien</h4>
                 A un paso de estar complicado con el Stock. Ya ir hablando con los proveedores.
             </div>
             @else
             <div class="alert alert-success alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                 <h4><i class="icon fa fa-check"></i> No es necesaria la reposición del Stock Mínimo</h4>
                 Buen Stock. No desesperarse. Todavía hay suficiente pero tener en cuenta que es Stock Mínimo.
             </div>
             @endif
            {{--@endif--}}
            <!-- /.col -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <form>
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Código de Barras</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Stock mínimo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articulos as $art)
                            <tr id="row{{$loop->index}}">
                                <td>{{$art->nombre}}</td>
                                <td>{{$art->barcode}}</td>
                                <td>{{$art->ultimoprecio}}</td>
                                <td>{{$art->stock}}</td>
                                <td>{{$art->stock_minimo}}
                                    @if($art->diferencia >= 5)
                                    <span class="text-green">
                                        <i class="fa fa-angle-up">
                                        </i>{{$art->diferencia}}
                                    </span>
                                    @elseif($art->diferencia < 5 && $art->diferencia >= 1)
                                    <span class="text-yellow">
                                        <i class="fa fa-angle-up">
                                        </i>  {{$art->diferencia}}
                                    </span>
                                    @else
                                     <span class="text-red">
                                        <i class="fa fa-angle-down">
                                        </i>{{$art->diferencia}}
                                     </span>
                                     @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

        <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <a href="stockminimo/create" class="btn btn-app">
            <i class="fa fa-edit"></i> Editar Stock Mínimo
        </a>
        {{--<div class="box-footer clearfix">--}}
            {{--<a href="stockminimo/create"  class="btn btn-sm btn-default btn-flat pull-right">Cambiar Stock Mínimo</a>--}}
        {{--</div>--}}
        <!-- /.box-footer -->
    </div>
@endsection
@push ('scripts')
<script>
//    $('.alert').hide();
//
//    $('.close').click(function() {
//        $('.alert').hide();
//    })
</script>
@endpush