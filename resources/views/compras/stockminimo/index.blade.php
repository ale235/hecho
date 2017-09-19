@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Estado del Stock Mínimo<br><br></h3>
            <div id="myAlert" class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                Danger alert preview. This alert is dismissable. A wonderful serenity has taken possession of my entire
                soul, like these sweet mornings of spring which I enjoy with my whole heart.
            </div>
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-info"></i> Alert!</h4>
                Info alert preview. This alert is dismissable.
            </div>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Warning alert preview. This alert is dismissable.
            </div>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                Success alert preview. This alert is dismissable.
            </div>
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
                            <th>Stock mínimo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articulos as $art)
                            <tr id="row{{$loop->index}}">
                                <td>{{$art->nombre}}</td>
                                <td>{{$art->barcode}}</td>
                                <td>{{$art->ultimoprecio}}</td>
                                <td>{{$art->stock_minimo}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

        <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <a href="stockminimo/create"  class="btn btn-sm btn-default btn-flat pull-right">Cambiar Stock Mínimo</a>
        </div>
        <!-- /.box-footer -->
    </div>
@endsection
@push ('scripts')
<script>
    $('.alert').hide();

    $('.close').click(function() {
        $('.alert').hide();
    })
</script>
@endpush