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

        {!! Form::open(array('url'=>'almacen/articulo', 'method'=>'POST', 'autocomplete'=>'off', 'files'=>'true', 'novalidate' => 'novalidate'))!!}
        {{Form::token()}}
        <div class="box box-body">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                <input  type="number" name="barcode" id="barcode" value="{{old('barcode')}}"  class="form-control" placeholder="Código de Barras">
                <input type="text" name="codigo" id="codigo" style="display: none" value="{{old('codigo')}}" class="form-control"  placeholder="Código del producto...">
                <input type="text" name="atajo" id="atajo" style="display: none" value="{{old('atajo')}}" class="form-control"  placeholder="Atajo del producto...">
                <div class="input-group-btn">
                    <label class="switch">
                        <input id="toogle-switch" type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <br>
            <div class="second-part-form" style="display: none">
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
                <br>

                <div class="input-group">
                    <img id="imagen-thumb" class="img-thumbnail img-responsive" height="25%" width="25%"  />
                </div>
                <br>

                <div class="input-group" id="imagen-module">
                    <span class="input-group-addon"><i class="fa fa-image"></i></span>
                    <input type="file" name="imagen" id="imagen" class="form-control" placeholder="Ingresá la imagen">
                    <span class="input-group-addon">Imagen del Artículo</span>
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

//    $('#codigo').mask('AAA000');
    $('#codigo').mask('AAAAAYYYYY', {'translation': {
        A: {pattern: /[A-Za-z]/},
        Y: {pattern: /[0-9]/}
    }
    }).cleanVal();


    $(document).ready(function () {
        $(document).on('change','#toogle-switch',function(){
            if ($('#toogle-switch').is(':checked')) {
                $('#toogle-switch').attr('checked',true);
                $('#barcode').css('display','block');
                $('#codigo').css('display','none');
                $('#atajo').css('display','none');
            } else {
                $('#toogle-switch').attr('checked',false);
                $('#barcode').css('display','none');
                $('#codigo').css('display','block');
                $('#atajo').css('display','block');
            }

        });

        $(document).on('change','#barcode,#codigo,#atajo',function(){
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
            // console.log("hmm its change");

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
                    }

                },
                error:function(){

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
<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        margin: -7px 3px -7px 0px;
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