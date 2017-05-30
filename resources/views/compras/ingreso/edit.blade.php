@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           <h3>
                Editar */: {{$ingreso->idingreso}}
            </h3>
            @if(count($errors)>0)
            <div class="alert alert-danger">
                <u>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </u>
            </div>
            @endif
        </div>
    </div>
    {!! Form::model($ingreso, ['method'=>'PATCH','route'=>['ingreso.update',$ingreso->idingreso]])!!}
    {{Form::token()}}
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="from-group">
                <label for="proveedor">Proveedor</label>
                {{--<select name="idproveedor" id="idproveedor" class="lista-proveedores form-control selectpicker"--}}
                        {{--data-live-search="true">--}}
                @foreach($persona as $p)
                <label class="lista-proveedores"  name="{{$p->codigo}}">{{$p->codigo}}</label>
                    @endforeach
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label>Artículo</label>
                        <select name="pidarticulo" id="pidarticulo" style="width: 200px" class="nombre-articulo">
                            {{--<select name="pidarticulo" class="nombre-articulo form-control selectpicker" id="pidarticulo" data-live-search="true">--}}
                            {{--@foreach($articulos as $articulo)--}}
                            {{--<option value="{{$articulo->idarticulo}}">{{$articulo->articulo}}</option>--}}
                            <option value="0" disabled="true" selected="true">Artículos</option>
                            {{--@endforeach--}}
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="pcantidad" id="pcantidad" class="form-control" onkeyup="actualizar()"
                               placeholder="Cantidad">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_compra_costo">Precio de Compra</label>
                        <input type="number" name="pprecio_compra_costo" id="pprecio_compra_costo" class="form-control"
                               onkeyup="actualizar()" placeholder="Precio de Compra">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="porcentaje_venta">Porcentaje de venta</label>
                        <input type="number" name="pporcentaje_venta" id="pporcentaje_venta" class="form-control"
                               onkeypress="return valida(event)" onkeyup="actualizar()" placeholder="Porcentaje de Venta">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_venta">Precio de venta Esperado</label>
                        <input type="number" name="pprecio_venta_esperado" id="pprecio_venta_esperado"
                               class="form-control">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color: #a94442">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>Precio Compra</th>
                        <th>Porcentaje venta</th>
                        <th>Subtotal</th>
                        </thead>
                        <tfoot>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><h4 id="total">$ 0.00</h4>  <input type="hidden" name="total_venta" id="total_venta"></th>
                        </tfoot>
                        <tbody>
                        @foreach($detalles as $detalle)
                            <tr class="selected" id="fila{{$loop->index}}">
                                <td><button type="button" class="btn btn-warning" onclick="eliminar({{$loop->index}})">X</button></td>
                                <td><input type="hidden" name="idarticulo[]" value="{{$detalle->idarticulo}}">{{$detalle->idarticulo}}</td>
                                <td><input type="number" name="cantidad[]" value="{{$detalle->cantidad}}"></td>
                                <td><input type="number" name="precio_compra_costo[]" value="{{$detalle->precio_compra_costo}}"></td>
                                <td><input type="number" name="porcentaje_venta[]" value="{{$detalle->porcentaje_venta}}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
            <div class="form-group">
                <input name="_token" value="{{csrf_token()}}" type="hidden">
                {{--<button class="btn btn-primary" type="submit">Guardar</button>--}}
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"
                        onclick="estacorrecto()">Open Modal
                </button>
                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Modal Header</h4>
                            </div>
                            <div class="modal-body cuerpo">
                                <p>Some text in the modal.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
                <button class="btn btn-danger" type="reset">Reset</button>
            </div>
        </div>
    </div>
    {!! Form::close()!!}
@push ('scripts')
<script>
    $(document).ready(function(){
        // console.log("hmm its change");

        var cat_id=$(this).find(".lista-proveedores").text();
//        var input = cat_id.split('+');
//        cat_id = input[1];
//        // console.log(cat_id);
        var div=$(this).find(".lista-proveedores").parent();

        var op=" ";

        $.ajax({
            type:'get',
            url:'{!!URL::to('buscarArticuloPorProveedorEnIngreso')!!}',
            data:{'codigo':cat_id},
            success:function(data){
                //console.log('success');

                console.log(data);

                //console.log(data.length);
                op+='<option value="0" selected disabled>Elegí un artículo</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].idarticulo+'">'+data[i].nombre+'</option>';
                }
                div.parent().parent().parent().parent().parent().find('.nombre-articulo').html(" ");
                div.parent().parent().parent().parent().parent().find('.nombre-articulo').append(op);
            },
            error:function(){

            }
        });
    });
    $(document).ready(function () {
       $('#bt_add').click(function () {
           agregar();
       });


    });
    var cont = 0;
    total = 0;
    subtotal=[];
    $('#guardar').show();

    while($('#fila' + cont).find("td:eq(3)").length != 0 ){
        subtotal[cont] = ($($('#fila' + cont).find("td:eq(2)").html()).val() * $($('#fila' + cont).find("td:eq(3)").html()).val());
        total +=  $($('#fila' + cont).find("td:eq(2)").html()).val() *  $($('#fila' + (cont)).find("td:eq(3)").html()).val();
        $($('#fila' + (cont))).append('<td>'+subtotal[cont]+'</td>');
        cont++;
    }
    $('#total').html('$: ' + total);


    function actualizar() {
        var a =  $('#pprecio_compra_costo').val();
        var b =  $('#pporcentaje_venta').val()/100 + 1;
        var cantidad =  $('#pcantidad').val();
        $('#pprecio_venta_esperado').val(cantidad * a*b);
    }

    function estacorrecto() {
        if($('#pcantidad').val()!=""  || $('#pprecio_compra_costo').val()!=""  || $('#pprecio_venta').val()!=""){
            $('.cuerpo').text('Quedaron datos por agregar. ¿Está seguro que desea guardar?');
        }
    }


    function limpiar(){
        $('#pcantidad').val("");
        $('#pprecio_compra_costo').val("");
        $('#pporcentaje_venta').val("");
        $('#pprecio_venta_esperado').val("");
    }

    function evaluar() {

            $('#guardar').show();

    }

    function agregar() {
        idarticulo=$('#pidarticulo').val();
        articulo = $('#pidarticulo option:selected').text();
        cantidad = $('#pcantidad').val();
        precio_compra_costo = $('#pprecio_compra_costo').val();
        precio_venta = $('#pporcentaje_venta').val();

        if(idarticulo!='' && cantidad!='' && cantidad>0 && precio_compra_costo!='' && precio_venta!=''){
            subtotal[cont] = (cantidad*precio_compra_costo);
            total = total+subtotal[cont];
            var fila = '<tr class="selected" id="fila'+cont+'">' +
                '<td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>' +
                '<td><input type="number" name="cantidad[]" value="'+cantidad+'"></td>' +
                '<td><input type="number" name="precio_compra_costo[]" value="'+precio_compra_costo+'"></td>' +
                '<td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td>' +
                '<td>'+subtotal[cont]+'</td></tr>';
            cont++;
            limpiar();
            $('#total').html('$' + total);
            evaluar();
            $('#detalles').append(fila);
        }
        else{
            alert("error al ingresar un ingreso, revise los datos del articulo");
        }
    }

    function eliminar(index) {
        total=total - subtotal[index];
        $('#total').html("$ " + total);
        $('#total_venta').val(total);
        $('#fila' + index).remove();
        evaluar();
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
@endsection