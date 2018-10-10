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

        {!! Form::open(array('url'=>'almacen/articulo', 'method'=>'POST', 'id'=>'form-articulo' ,'autocomplete'=>'off', 'files'=>'true', 'novalidate' => 'novalidate'))!!}
        {{Form::token()}}
        <div class="box box-body">
            <div class="zero-part-form col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="input-group">
                    <span class="input-group-addon barcode"><i class="fa fa-barcode"></i></span>
                    <input  type="number" name="barcode" id="barcode" value="{{old('barcode')}}"  class="form-control barcode" placeholder="Código de Barras">
                </div>
                <br>
            </div>

            <div class="first-part-form col-xs-12 col-sm-6 col-md-4 col-lg-10" style="display: none">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Proveedor</span>
                        <select name="idproveedores" id="idproveedores"  class="form-control">
                            <option selected>Seleccione el Proveedor</option>
                            @foreach($proveedores as $prov)
                                <option value="{{$prov->idpersona}}">{{$prov->codigo}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="idproveedorsolo" id="idproveedorsolo" value="{{old('idproveedorsolo')}}">
                        <input type="hidden" name="idproveedor" id="idproveedor" value="{{old('idproveedor')}}">
                        <span class="input-group-btn">
                        <a href="{{ url('compras/proveedor/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nuevo Proveedor</button></a>
                    </span>
                    </div>
                </div>

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                    <input type="text" name="codigo" id="codigo" value="{{old('codigo')}}" class="form-control"  placeholder="Código del producto...">
                </div>
                <br>

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                    <input type="text" name="atajo" id="atajo" value="{{old('atajo')}}" class="form-control"  placeholder="Atajo del producto...">
                </div>
                <br>

            </div>

            <div class="switch-form col-xs-2 col-sm-2 col-md-2 col-lg-2 pull-right">
                <div class="input-group" style="text-align: center">
                    <label class="switch ">
                        <input id="toogle-switch" type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <a href="#" data-toggle="tooltip" title="En caso de conocer el Código de Barras, dejar activado. Caso contrario, si conoce Código de Producto o el Atajo ingréselo. O en su defecto genere un código seleccionando el proveedor del Artículo.">Ayuda</a>
            </div>

            <div class="second-part-form col-xs-12 col-sm-12 col-md-12 col-lg-12" style="display: none">
                <div class="input-group">
                    <span class="input-group-addon">Nombre</span>
                    <input type="text" name="nombre" id="nombre" value="{{old('nombre')}}" class="form-control" placeholder="Nombre">
                </div>
                <br>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Categoría</span>
                        <select name="idcategoria" id="idcategoria" class="form-control">
                            @foreach($categorias as $cat)
                                <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                        <a href="{{ url('almacen/categoria/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nueva Categoría</button></a>
                    </span>
                        <input type="hidden" name="idcategoriasolo" id="idcategoriasolo" value="{{old('idcategoriasolo')}}">
                    </div>
                </div>
                <br>

                <div class="input-group">
                    <img id="imagen-thumb" class="img-thumbnail img-responsive" height="25%" width="25%"  />
                </div>
                <br>

                <div class="input-group" id="imagen-module">
                    <span class="input-group-addon"><i class="fa fa-image"></i></span>
                    <input type="file" name="imagen" id="imagen" class="form-control" placeholder="Ingresá la imagen">
                    {{--<span class="input-group-addon">Imagen del Artículo</span>--}}
                    <br>
                </div>

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
            </div>
            <!-- /input-group -->
        </div>

        <!-- /.box-body -->
        <div class="box box-footer">
            <button type="reset" class="btn btn-default">Cancelar</button>
            <button type="submit" class="btn btn-info pull-right">Cargar Artículo</button>
        </div>
        {!! Form::close()!!}
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Artículo nuevo</h4>
                    </div>
                    <div class="modal-body">
                        <p>Este Artículo no existe. Por favor ingrese primero el Proveedor de este Artículo.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
@endsection

@push ('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js">

</script>
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

    var options =  {
        onComplete: function(cep) {
            var cat_id=cep;
            console.log(cat_id);
            $.ajax({
                type:'get',
                url:'{!!URL::to('existeArticulo')!!}',
                data:{'barcode':cat_id},
                success:function(data){
                    //Si se activa este IF quiere decir que se va a EDITAR el artículo.
                    if(!(Object.keys(data).length === 0 && data.constructor === Object)){

                        //Controla que se muestre el resto del formulario.
                        $('.second-part-form').css('display','block');
                        $('.switch').css('display','none');

                        $('#barcode').attr('readonly', true);
                        $('#nombre').attr('readonly', true);
                        $('#codigo').attr('readonly', true);
                        $("#codigo").val(data.codigo);
                        $('#nombre').val(data.nombre);
                        $('#barcode').val(data.barcode);
                        $("#idcategoria").val(data.idcategoria);
                        $("#idcategoriasolo").val(data.idcategoria);
                        $("#idproveedores").val(data.idpersona);
                        $("#idcategoria").attr('disabled', 'disabled');
                        $("#idproveedores").attr('disabled', 'disabled');
                        $("#existencia").text(data.stock);
                        $("#inputdelexistencia").show();
                        $('#pprecio_compra_costo').val(data.precio_compra);
                        $('#pporcentaje_venta').val(data.porcentaje);
                        $('#pprecio_venta_esperado').val(data.precio_venta);
                        $('#idproveedorsolo').val(data.idpersona);
                        $("#idproveedor").val(data.proveedor);
                        var res = '{{asset('imagenes/articulos')}}'.concat('/'+data.imagen);
                        $("#imagen-thumb").attr('src',res);
                        $("#imagen-module").css('display','none');

                        $($('#codigo').parent()).addClass('has-success');
                        $($('#barcode').parent()).addClass('has-success');
                        $($('#codigo').parent()).removeClass('has-error');
                        $($('#idcategoria').parent()).addClass('has-success');
                        $($('#idproveedores').parent()).addClass('has-success');
                        $($('#nombre').parent()).addClass('has-success');
                    }
                    else{
//                        $('#codigo').val('');
//                        $($('#codigo').parent()).removeClass('has-error');
//                        $('#modal-default').modal("show");
                    }

                },
                error:function(){
                    console.log("aca");
                }
            });
        },
        onKeyPress: function(cep, event, currentField, options){
            console.log('A key was pressed!:', cep, ' event: ', event,
                'currentField: ', currentField, ' options: ', options);
        },
        onChange: function(cep){
            $($('#codigo').parent()).addClass('has-error');
        },
        onInvalid: function(val, e, f, invalid, options){
            var error = invalid[0];
            console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
        },
        translation: {
            'A': {pattern: /[A-Za-z]/},
            'Y': {pattern: /[0-9]/}
        }
    };
    $('#codigo').mask('AAAAAYYYYY', options);


    $(document).ready(function () {
        $(document).on('change','#toogle-switch',function(){
            if ($('#toogle-switch').is(':checked')) {
                $('#toogle-switch').attr('checked',true);
                $('.barcode').css('display','');
//                $('#codigo').css('display','none');
//                $('#atajo').css('display','none');
                $('.first-part-form').css('display','none');
            } else {
                $('#toogle-switch').attr('checked',false);
                $('.barcode').css('display','none');
//                $('#codigo').css('display','block');
//                $('#atajo').css('display','block');
                $('.first-part-form').css('display','block');
            }

        });

        $(document).on('change','#barcode,#atajo',function(){
            var cat_id=$(this).val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('existeArticulo')!!}',
                data:{'barcode':cat_id},
                success:function(data){
                    //Controla que se muestre el resto del formulario.
                    $('.second-part-form').css('display','block');
                    $('.switch').css('display','none');

                    //Si se activa este IF quiere decir que se va a EDITAR el artículo.
                    if(!(Object.keys(data).length === 0 && data.constructor === Object)){
                        $('#barcode').attr('readonly', true);
                        $('#nombre').attr('readonly', true);
                        $('#codigo').attr('readonly', true);
                        $("#codigo").val(data.codigo);
                        $('#nombre').val(data.nombre);
                        $('#barcode').val(data.barcode);
                        $("#idcategoria").val(data.idcategoria);
                        $("#idcategoriasolo").val(data.idcategoria);
                        $("#idproveedores").val(data.idpersona);
                        $("#idcategoria").attr('disabled', 'disabled');
                        $("#idproveedores").attr('disabled', 'disabled');
                        $("#existencia").text(data.stock);
                        $("#inputdelexistencia").show();
                        $('#pprecio_compra_costo').val(data.precio_compra);
                        $('#pporcentaje_venta').val(data.porcentaje);
                        $('#pprecio_venta_esperado').val(data.precio_venta);
                        $('#idproveedorsolo').val(data.idpersona);
                        $("#idproveedor").val(data.proveedor);
                        var res = '{{asset('imagenes/articulos')}}'.concat('/'+data.imagen);
                        $("#imagen-thumb").attr('src',res);
                        $("#imagen-module").css('display','none');

                        $($('#codigo').parent()).addClass('has-success');
                        $($('#barcode').parent()).addClass('has-success');
                        $($('#idcategoria').parent()).addClass('has-success');
                        $($('#idproveedores').parent()).addClass('has-success');
                        $($('#nombre').parent()).addClass('has-success');
                        $($('#pporcentaje_venta').parent()).addClass('has-success');
                        $($('#pprecio_compra_costo').parent()).addClass('has-success');
//                        $($('#nombre').parent()).addClass('has-success');
//                        $($('#idcategoria').parent()).addClass('has-success');


                    }

                },
                error:function(){
                    console.log("aca");
                }
            });

        });
//
//        $(document).on('change','#codigo',function(){
//            var cod = $('#idproveedores').val()+$('#codigo').val();
//            for(var i = 0; i<art.length;i++){
//                if(art[i].codigo == cod){
//                    alert('El código ya existe');
//                    $('#codigo').val(' ');
//                }
//            }
//
//        });
        $(document).on('change','#idproveedores',function(){

            var cat_id=$(this).val();
            var cat_text=$("#idproveedores option:selected").text();
            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarProveedor')!!}',
                data:{'idpersona':cat_id},
                success:function(data){
                    $('#idproveedorsolo').val(data[0].idpersona);
                    $('#idproveedor').val(data[0].codigo)

                },
                error:function(){

                }
            });
            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarUltimoId')!!}',
                data:{'codigo':cat_text},
                success:function(data){
                    if (data.codigo == null) {
                        var d = ajustar(5, 1);
                        $('#codigo').val(d);
                    }
                    else {
                        var a = data.codigo.substr(data.codigo.length - 5);
                        var a2 = $('#idproveedor').val();
                        var b = parseInt(a) + 1;
                        var c = ajustar(5, b);
                        $('#codigo').val(a2.concat(c));
                        $('.second-part-form').css('display','block');
                        $($('#codigo').parent()).addClass('has-success');
                        $($('#barcode').parent()).addClass('has-success');
                        $($('#codigo').parent()).removeClass('has-error');
                        $($('#idcategoria').parent()).addClass('has-success');
                        $($('#idproveedores').parent()).addClass('has-success');
                        $($('#nombre').parent()).addClass('has-success');
                    }

                },
                error:function(){

                }
            });

        });

        $(document).on('change','#pcantidad,#pprecio_compra_costo,#pporcentaje_venta',function(){
            //$($('#codigo').parent()).addClass('has-success');
            $(this).parent().addClass('has-success');
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

    $( "#form-articulo" ).submit(function( event ) {
        if(!($($('#codigo').parent()).hasClass('has-success') &&
            $($('#barcode').parent()).hasClass('has-success') &&
            $($('#nombre').parent()).hasClass('has-success') &&
            $($('#idcategoria').parent()).hasClass('has-success') &&
            $($('#nombre').parent()).hasClass('has-success') &&
            $($('#idproveedores').parent()).hasClass('has-success') &&
            $($('#pcantidad').parent()).hasClass('has-success') &&
            $($('#pporcentaje_venta').parent()).hasClass('has-success') &&
            $($('#pprecio_compra_costo').parent()).hasClass('has-success')))
        {
            event.preventDefault();
        }

    });


</script>
<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;

        /*margin: -7px 3px -7px 0px;*/
    }

    /* Hide default HTML checkbox */
    .switch input {display:none;}

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
@endpush