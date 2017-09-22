@extends ('layouts.admin')
@section ('contenido')
    {!! Form::open(array('url'=>'precios/actualizar', 'method'=>'POST', 'autocomplete'=>'off'))!!}
    {{Form::token()}}
    <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Input Addon</h3>
            </div>
            <div class="box-body">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                    <input name="barcode" id="barcode" type="text" class="form-control" placeholder="">
                    <input name="idarticulo" id="idarticulo" type="hidden" class="form-control" placeholder="">
                </div>
                <br>

                <div class="input-group">
                    <span class="input-group-addon">Nombre</span>
                    <input type="text" name="nombre" id="nombre" value="{{old('nombre')}}" class="form-control" placeholder="Nombre">
                </div>
                <br>

                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="number" name="precio_compra" id="precio_compra" class="form-control" onkeyup="actualizar()" placeholder="Compra">
                    <span class="input-group-addon">Costo del Artículo</span>
                </div>
                <br>

                <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="number" name="porcentaje" id="porcentaje" class="form-control" onkeyup="actualizar()" placeholder="Porcentaje de Venta">
                    <span class="input-group-addon">Porcentaje de Venta del Artículo</span>
                </div>
                <br>

                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="number" name="pprecio_venta_esperado" id="pprecio_venta_esperado"  class="form-control" placeholder="Precio Esperado" disabled>
                    <span class="input-group-addon">Precio Esperado (Es el cálculo del Costo x el Porcentaje de Venta)</span>
                </div>
                <br>
                <!-- /input-group -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                 <span class="input-group-btn">
                     <button class="btn btn-primary" type="submit">Guardar</button>
                 </span>
            </div>
    </div>
    {!! Form::close()!!}

@endsection
@push ('scripts')
<script>
    function actualizar() {
        var a =  $('#precio_compra').val();
        var b =  $('#porcentaje').val()/100 + 1;
        $('#pprecio_venta_esperado').val(a*b);
    }

    $(document).ready(function () {
        $(document).on('change','#barcode',function(){
            var cat_id=$(this).val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('existeArticulo')!!}',
                data:{'barcode':cat_id},
                success:function(data){
                    console.log(data)
                    $('#barcode').attr('readonly', true);
                    $('#idarticulo').val(data.idarticulo);
                    $('#nombre').val(data.nombre);
                    $('#nombre').attr('readonly', true);
                    $('#precio_compra').val(data.precio_compra);
                    $('#porcentaje').val(data.porcentaje);
                    $('#pprecio_venta_esperado').removeAttr('disabled');
                    $('#pprecio_venta_esperado').attr('readonly', 'true');
                    $('#pprecio_venta_esperado').val(data.precio_venta);
                },
                error:function(){
                    console.log("aca");
                }
            });

        });
    });
</script>
@endpush