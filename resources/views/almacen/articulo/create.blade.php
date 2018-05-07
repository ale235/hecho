@extends ('layouts.admin')
@section ('contenido')
    <section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">
                                <i class="fa fa-file"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-picture"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <form role="form">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">
                        <h3>Step 1</h3>
                        <p>This is step 1</p>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-primary next-step">Save and continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                        <h3>Step 2</h3>
                        <p>This is step 2</p>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-primary next-step">Save and continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step3">
                        <h3>Step 3</h3>
                        <p>This is step 3</p>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-default next-step">Skip</button></li>
                            <li><button type="button" class="btn btn-primary btn-info-full next-step">Save and continue</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="complete">
                        <h3>Complete</h3>
                        <p>You have successfully completed all steps.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </section>
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
                    <span class="input-group-addon">Categoría</span>
                    <select name="idcategoria" id="idcategoria" class="form-control">
                        @foreach($categorias as $cat)
                            <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a href="{{ url('almacen/categoria/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nueva Categoría</button></a>
                    </span>
                </div>
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
                    <span class="input-group-btn">
                        <a href="{{ url('compras/proveedor/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nuevo Proveedor</button></a>
                    </span>
                </div>
            </div>
            <br>

            <div style="display: none" class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="from-group">
                    <label for="stock">Codigo</label>
                    <input type="text" name="codigo" id="codigo" value="{{old('codigo')}}" class="form-control" placeholder="Código...">
                </div>
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
            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarUltimoId')!!}',
                data:{'codigo':cat_id},
                success:function(data){
                    if (data.codigo == null) {
                        var d = ajustar(5, 1);
                        $('#codigo').val(d);
                    }
                    else {
                        var a = data.codigo.substr(data.codigo.length - 5);
                        var b = parseInt(a) + 1;
                        var c = ajustar(5, b);
                        $('#codigo').val(c);
                    }

                },
                error:function(){

                }
            });

        });

        $(document).on('change','#codigo',function(){
            var cod = $('#idproveedores').val()+$('#codigo').val();
            for(var i = 0; i<art.length;i++){
                if(art[i].codigo == cod){
                    alert('El código ya existe');
                    $('#codigo').val(' ');
                }
            }

        });

        $(document).on('change','#barcode',function(){
            var cat_id=$(this).val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('existeArticulo')!!}',
                data:{'barcode':cat_id},
                success:function(data){
                    $('#barcode').attr('readonly', true);
                    $('#nombre').val(data.nombre);
                    $('#nombre').attr('readonly', true);
                    $("#idcategoria").val(data.idcategoria);
                    $("#codigo").val(data.codigo);
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

    function agregarprov() {
    var proveedorselected = $('#idproveedores option:selected').text();
    if(ultimoid.length == 0){
        var d= ajustar(5,1);
        $('#codigo').val(d);
    } else{
        var a = ultimoid;
        var b = parseInt(a) + 1;
        var c =ajustar(5,b);
        $('#codigo').val(c);
    }
/*    var obj = JSON.parse(casa);
    for (i = 0; i < obj.length; i++) {
       if(proveedorselected == obj[i].proveedor){
          var a = obj[i].codigo.substr(obj[i].proveedor.length, obj[i].codigo.length);
          var b = parseInt(a) + 1;
          var c =ajustar(5,b);
           $('#codigo').val(c);
       }else{
           var d= ajustar(5,1);
           $('#codigo').val(d);
       }
    }*/
}

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
    .wizard {
        margin: 20px auto;
        background: #fff;
    }

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

    .connecting-line {
        height: 2px;
        background: #e0e0e0;
        position: absolute;
        width: 80%;
        margin: 0 auto;
        left: 0;
        right: 0;
        top: 50%;
        z-index: 1;
    }

    .wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
        color: #555555;
        cursor: default;
        border: 0;
        border-bottom-color: transparent;
    }

    span.round-tab {
        width: 70px;
        height: 70px;
        line-height: 70px;
        display: inline-block;
        border-radius: 100px;
        background: #fff;
        border: 2px solid #e0e0e0;
        z-index: 2;
        position: absolute;
        left: 0;
        text-align: center;
        font-size: 25px;
    }
    span.round-tab i{
        color:#555555;
    }
    .wizard li.active span.round-tab {
        background: #fff;
        border: 2px solid #5bc0de;

    }
    .wizard li.active span.round-tab i{
        color: #5bc0de;
    }

    span.round-tab:hover {
        color: #333;
        border: 2px solid #333;
    }

    .wizard .nav-tabs > li {
        width: 25%;
    }

    .wizard li:after {
        content: " ";
        position: absolute;
        left: 46%;
        opacity: 0;
        margin: 0 auto;
        bottom: 0px;
        border: 5px solid transparent;
        border-bottom-color: #5bc0de;
        transition: 0.1s ease-in-out;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 46%;
        opacity: 1;
        margin: 0 auto;
        bottom: 0px;
        border: 10px solid transparent;
        border-bottom-color: #5bc0de;
    }

    .wizard .nav-tabs > li a {
        width: 70px;
        height: 70px;
        margin: 20px auto;
        border-radius: 100%;
        padding: 0;
    }

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

    .wizard .tab-pane {
        position: relative;
        padding-top: 50px;
    }

    .wizard h3 {
        margin-top: 0;
    }

    @media( max-width : 585px ) {

        .wizard {
            width: 90%;
            height: auto !important;
        }

        span.round-tab {
            font-size: 16px;
            width: 50px;
            height: 50px;
            line-height: 50px;
        }

        .wizard .nav-tabs > li a {
            width: 50px;
            height: 50px;
            line-height: 50px;
        }

        .wizard li.active:after {
            content: " ";
            position: absolute;
            left: 35%;
        }
    }
</style>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {display:none;}

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
<script>
    $(document).ready(function () {
        //Initialize tooltips
        $('.nav-tabs > li a[title]').tooltip();

        //Wizard
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

            var $target = $(e.target);

            if ($target.parent().hasClass('disabled')) {
                return false;
            }
        });

        $(".next-step").click(function (e) {

            var $active = $('.wizard .nav-tabs li.active');
            $active.next().removeClass('disabled');
            nextTab($active);

        });
        $(".prev-step").click(function (e) {

            var $active = $('.wizard .nav-tabs li.active');
            prevTab($active);

        });
    });

    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }

</script>
@endpush