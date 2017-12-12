@extends ('layouts.admin')
@section ('contenido')
    <!-- Input addon -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Nuevo Artículo</h3>
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
        {!! Form::open(array('url'=>'almacen/createPorCodigo', 'method'=>'POST', 'autocomplete'=>'off', 'files'=>'true', 'novalidate' => 'novalidate'))!!}
        {{Form::token()}}
        <div class="box box-body">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Categoría</span>
                    <select name="idcategoria" id="idcategoria" class="form-control">
                        <option selected>Seleccione la Categoría</option>
                        @foreach($categorias as $cat)
                            @if($cat->nombre != 'Kiosco')
                            <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <br>

            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                <input type="text" name="codigo" id="codigo" value="{{old('codigo')}}" class="form-control" placeholder="Código del producto...">
            </div>

            <div class="input-group" style="display: none">
                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                <input  type="text" name="barcode" id="barcode" value="{{old('barcode')}}"  class="form-control" placeholder="Código de Barras">
            </div>
            <br>

            <div class="input-group">
                <span class="input-group-addon">Nombre</span>
                <input type="text" name="nombre" id="nombre" value="{{old('nombre')}}" class="form-control" placeholder="Nombre">
            </div>
            <br>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Proveedor</span>
                    <select  name="idproveedores" id="idproveedores"  class="form-control">
                        <option selected>Seleccione el Proveedor</option>
                        @foreach($proveedores as $prov)
                            <option value="{{$prov->codigo}}">{{$prov->codigo}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="idproveedorsolo" id="idproveedorsolo" value="{{old('idproveedorsolo')}}">
                    <input type="hidden" name="idproveedor" id="idproveedor" value="{{old('idproveedor')}}">
                </div>
            </div>
            <br>

            <hr size="60" />

            <div class="input-group">
                <span id="inputdelexistencia" style="display: none" class="input-group-addon">Hay <span id="existencia"></span> artículos en Stock</span>
                <input type="number" name="pcantidad" id="pcantidad" value="{{old('pcantidad')}}" class="form-control" onkeyup="actualizar()" placeholder="Cantidad">
                <span class="input-group-addon">Cantidad de Artículos a Ingresar al Stock</span>
            </div>
            <br>

            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number" name="pprecio_compra_costo" id="pprecio_compra_costo" value="{{old('pprecio_compra_costo')}}" class="form-control" onkeyup="actualizar()" placeholder="Costo">
                <span class="input-group-addon">Costo del Artículo</span>
            </div>
            <br>

            <div class="input-group">
                <span class="input-group-addon">%</span>
                <input type="number" name="pporcentaje_venta" id="pporcentaje_venta" value="{{old('pporcentaje_venta')}}" class="form-control" onkeypress="return valida(event)" onkeyup="actualizar()" placeholder="Porcentaje de Venta">
                <span class="input-group-addon">Porcentaje de Venta del Artículo</span>
            </div>
            <br>

            <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="number" name="pprecio_venta_esperado" id="pprecio_venta_esperado"  class="form-control" placeholder="Precio Esperado">
                <span class="input-group-addon">Precio Esperado (Es el cálculo del Costo x el Porcentaje de Venta)</span>
            </div>
            <br>
            <!-- /input-group -->
        </div>

        <!-- /.box-body -->
        <div class="box box-footer">
            <button type="reset" class="btn btn-default">Cancelar</button>
            <button type="submit" class="btn btn-info pull-right">Cargar Artículo</button>
        </div>
        {!! Form::close()!!}
    </div>
    <!-- /.box -->
@endsection

@push ('scripts')
<script>
    $("#barcode").keypress(function(event){
        if (event.which == '10' || event.which == '13') {
            event.preventDefault();
        }
    });
    $("#codigo").keypress(function(event){
        if (event.which == '10' || event.which == '13') {
            event.preventDefault();
        }
    });
    $(document).ready(function () {
        $('#idproveedores option[value="'+$('#idproveedor').val()+'"]').attr('selected', 'selected');

        $(document).on('change','#idproveedores',function(){
            // console.log("hmm its change");

            var cat_id=$(this).val();

            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarProveedor')!!}',
                data:{'codigo':cat_id},
                success:function(data){
                    $('#idproveedorsolo').val(data[0].idpersona);
                    $('#idproveedor').val(data[0].codigo)

                },
                error:function(){

                }
            });

        });

        $(document).on('change','#codigo',function(){
            var cat_id=$(this).val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('existeArticulo')!!}',
                data:{'barcode':cat_id},
                success:function(data){
                    $('#barcode').val(data.idarticulo);
                    $('#nombre').val(data.nombre);
                    $('#nombre').attr('readonly', true);
                    $("#idcategoria").val(data.idcategoria);
                    $("#idproveedores").val(data.proveedor);
                    $("#idproveedores").attr('disabled', 'disabled');
                    $("#existencia").text(data.stock);
                    $("#existencia").attr('attr', 'bold');
                    $("#inputdelexistencia").show();
                    $('#pprecio_compra_costo').val(data.precio_compra);
                    $('#pporcentaje_venta').val(data.porcentaje);
                    $('#pprecio_venta_esperado').val(data.precio_venta);
                    $('#idproveedorsolo').val(data.idpersona);
                    $("#idproveedor").val(data.proveedor);

                },
                error:function(){
                    console.log("aca");
                }
            });

        });
    });

    function ajustar(tam, num) {
        if (num.toString().length < tam) return ajustar(tam, "0" + num)
        else return num;
    }
    function actualizar() {
        var a =  $('#pprecio_compra_costo').val();
        var b =  $('#pporcentaje_venta').val()/100 + 1;
        var cantidad =  $('#pcantidad').val();
        $('#pprecio_venta_esperado').val(a*b);
    }
    function valida(e){
        tecla = (document.all) ? e.keyCode : e.which;

        //Tecla de retroceso para borrar, siempre la permite
        if (tecla==8){
            return true;
        }

        // Patron de entrada, en este caso solo acepta numeros
        patron =/[0-9]/;
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
    }


</script>
@endpush