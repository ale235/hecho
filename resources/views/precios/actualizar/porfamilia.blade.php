@extends ('layouts.admin')
@section ('contenido')

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Cambiar Precio por Familia</h3>
        </div>
        <div class="box-body">
            {!! Form::open(array('url'=>'precios/porfamilia', 'method'=>'POST', 'autocomplete'=>'off', 'class'=>'form-horizontal'))!!}
            {{Form::token()}}
            <div class="container">
                <div class="form-group">
                    <label class="col-md-4 control-label">Nombre del artículo</label>
                    <div class="col-md-4 inputGroupContainer">
                        <select name="fproveedor" id="fproveedor" class="lista-fproveedores form-control selectpicker" data-live-search="true">
                            <option value="0" disabled="true" selected="true">Seleccione el Proveedor
                            </option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{$proveedor->idpersona}}+{{$proveedor->codigo}}">{{$proveedor->codigo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Acción</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                                        <span class="input-group-btn">
                                            <input name="_token" value="{{csrf_token()}}" type="hidden">
                                            <button class="btn btn-primary" type="submit">Guardar</button>
                                        </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color: #a94442">
                    <th>Opciones</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>% de venta actual</th>
                    <th>Costo Actual <br> <input name="porcentajeporcolumna" id="porcentajeporcolumna" type="number" placeholder="Porcentaje a esta columna"></th>
                    <th>Nuevo % para la venta</th>
                    <th>Precio de venta actual</th>
                    {{--<th>Subtotal</th>--}}
                    </thead>
                    <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    {{--<th><h4 id="total">$ 0.00</h4> <input type="hidden" name="total_venta" id="total_venta"></th>--}}
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
            </div>
            {!! Form::close()!!}
        </div>
        <!-- /.box-body -->
    </div>
@endsection
@push ('scripts')
<script>
    var anterior = 0;
    $(document).on('change', '#porcentajeporcolumna', function () {
        $('#detalles > tbody  > tr').each(function(data){
            var num = parseInt($($(this).find('td').eq(7)[0]).children().val());
            $($(this).find('td').eq(4)[0]).children().val(Math.round(num + num*$('#porcentajeporcolumna').val()/100));
            var costo = num + num*$('#porcentajeporcolumna').val()/100;
            var porcentaje_venta = $($(this).find('td').eq(5)[0]).children().val();
            $($(this).find('td').eq(6)[0]).children().text('$' + Math.round(costo + costo*porcentaje_venta/100))
        });
    });

    $(document).on('change', '#detalles', function () {
        $('#detalles > tbody  > tr').each(function(data){
            var costo = parseInt($($(this).find('td').eq(4)[0]).children().val());
            var porcentaje_venta = $($(this).find('td').eq(5)[0]).children().val();
            $($(this).find('td').eq(6)[0]).children().text('$' + Math.round(costo + costo*porcentaje_venta/100))
        });
    });




    $(document).ready(function () {

        $(document).on('change', '.selectpicker', function () {
            $('.selectpicker').selectpicker('refresh');
        });
        $(document).on('change','.lista-fproveedores',function(){
            // console.log("hmm its change");

            var cat_id=$(this).val();
            var input = cat_id.split('+')
            cat_id = input[1];
            // console.log(cat_id);
            var div=$(this).parent();

            var cb=" ";

            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarArticuloPorPrecioYPorProveedor')!!}',
                data:{'codigo':cat_id},
                success:function(data){

                    var hash = {};
                    data = data.filter(function(current) {
                        var exists = !hash[current.codigo] || false;
                        hash[current.codigo] = true;
                        return exists;
                    });

                    for(var i=0; i<data.length; i++){
                        agregar(data[i]);
                    }
                    $('.lista-fproveedores').attr('disabled',true);
                },
                error:function(){

                }
            });
        });
        var cont = 0;
        function agregar(data) {

            var precio = data.precio

            var fila = '<tr class="selected" id="fila'+cont+'">' +
                '<td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td>' +
                '<td><input type="hidden" name="codigoarticulo[]" value="'+data.codigo+'">'+data.codigo+'</td>' +
                '<td><input type="hidden" name="idarticulo[]" value="'+data.idarticulo+'">'+data.nombre+'</td>' +
                '<td><label type="number">%'+data.porcentaje+'</label></td>' +
                '<td><input type="number" name="nuevo_precio_compra[]" value="'+data.precio_compra+'"></td>' +
                '<td><input type="number" id="nuevo_porcentaje[]" name="nuevo_porcentaje[]" value='+data.porcentaje+'></td>' +
                '<td><label type="number">$'+data.precio_venta+'</label></td>' +
                '<td><input type="hidden" value="'+data.precio_compra+'"></td>' +
                '</tr>';
            cont++;


            $('#detalles').append(fila);

        }
    });
</script>
@endpush