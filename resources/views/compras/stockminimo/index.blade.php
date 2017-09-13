@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Latest Orders</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
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
                            <th class="text-center">¿Es Stock Mínimo?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articulos as $art)
                            <tr id="row{{$loop->index}}">
                                <td>{{$art->nombre}}</td>
                                <td>{{$art->barcode}}</td>
                                <td>{{$art->ultimoprecio}}</td>
                                <td>{{$art->stock}}</td>
                                <td>
                                    <input class="form-control input-sm" name="stockminimo[]" type="text" placeholder=".input-sm">
                                </td>
                                <td class="text-center">
                                    <input checked="checked" name="checkActivo[]" type="checkbox"/>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</button>
            </form>

        <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>

            <a href="stockminimo/create"  class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
        </div>
        <!-- /.box-footer -->
    </div>
@endsection
@push ('scripts')
<script>
</script>
@endpush