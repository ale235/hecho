@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Editar */: {{$venta->idventa}}
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
    {!! Form::model($venta, ['method'=>'PATCH','route'=>['venta.update',$venta->idventa]])!!}
            {{Form::token()}}
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="from-group">
                <label for="proveedor">Cliente</label>
                <select name="idcliente" id="idcliente" class="lista-clientes form-control">
                    @foreach($personas as $persona)
                        @if($venta->idcliente == $persona->idpersona)
                            <option value="{{$persona->idpersona}}" selected="true">{{$persona->nombre}}</option>
                        @endif
                        <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="from-group">
                        <label for="proveedor">Proveedor</label>
                        <select name="idproveedor" id="idproveedor" class="lista-proveedores form-control">
                            <option value="0" disabled="true" selected="true">Seleccione el Proveedor</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{$proveedor->idpersona}}+{{$proveedor->codigo}}">{{$proveedor->codigo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>Artículo</label>
                        <select name="pidarticulo" id="pidarticulo" style="width: 200px" class="nombre-articulo">
                            <option value="0" disabled="true" selected="true">Artículos</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="pcantidad" id="pcantidad" class="form-control" onkeyup="actualizar()" placeholder="Cantidad">
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_venta">Precio por Unidad</label>
                        <input type="number" name="pprecio_venta" id="pprecio_venta" class="form-control" onkeypress="return valida(event)" onkeyup="actualizar()" placeholder="Precio de Venta" disabled>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="pprecio_venta_cantidad">Precio * Cantidad</label>
                        <input type="number" name="pprecio_venta_cantidad" id="pprecio_venta_cantidad" class="form-control" placeholder="Precio * Cantidad" disabled>
                    </div>
                </div>
                {{--<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="pprecio_venta_concretado">Precio de venta concretado</label>--}}
                        {{--<input type="number" name="pprecio_venta_concretado" id="pprecio_venta_concretado" class="form-control" placeholder="Precio de venta concretado">--}}
                    {{--</div>--}}
                {{--</div>--}}
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
                            <th>Precio Venta</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                        </thead>
                        <tfoot>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><h4 id="total">$ 0.00</h4> <input type="hidden" name="total_venta" id="total_venta"></th>
                        </tfoot>
                        <tbody>
                        @foreach($detalles as $detalle)
                            <tr class="selected" id="fila{{$loop->index}}">
                                <td><button type="button" class="btn btn-warning" onclick="eliminar({{$loop->index}})">X</button></td>
                                <td><input type="hidden" name="idarticulo[]" value="{{$detalle->idarticulo}}">{{$detalle->idarticulo}}</td>
                                <td><input type="number" name="cantidad[]" value="{{$detalle->cantidad}}"></td>
                                <td><input type="number" name="precio_venta[]" value="{{$detalle->precio_venta}}"></td>
                                <td></td>
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
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <button class="btn btn-danger" type="reset">Reset</button>
                </div>
            </div>
    </div>

            {!! Form::close()!!}
@push ('scripts')
<script>
    $(document).ready(function () {
       $('#bt_add').click(function () {
           agregar();
       });

        $(document).on('change','.lista-proveedores',function(){
            // console.log("hmm its change");

            var cat_id=$(this).val();
            var input = cat_id.split('+')
            cat_id = input[1];
            // console.log(cat_id);
            var div=$(this).parent();

            var op=" ";

            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarArticuloPorProveedor')!!}',
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
//                    div.find('.nombre-articulo').html(" ");
//                    div.find('.nombre-articulo').append(op);
//                    alert($(".lista-proveedores").prop('selectedIndex'));
                    //div.parent().parent().parent().parent().parent().find('.nombre-articulo').unbind('change')
                },
                error:function(){

                }
            });
        });

        $(document).on('change','.nombre-articulo',function(){
            // console.log("hmm its change");

            var cat_id=$(this).val();
//            var input = cat_id.split('+');
//            cat_id = input[1];
            // console.log(cat_id);
            var div=$(this).parent();

            var op=" ";

            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarPrecioArticulo')!!}',
                data:{'id':cat_id},
                success:function(data){
                    //console.log('success');

                    console.log(data);

                    $('#pprecio_venta').val(data[0].precio);
                    //console.log(data.length);
//                    op+='<option value="0" selected disabled>Elegí un artículo</option>';
//                    for(var i=0;i<data.length;i++){
//                        op+='<option value="'+data[i].idarticulo+'">'+data[i].nombre+'</option>';
//                    }
//                    div.parent().parent().parent().parent().parent().find('.nombre-articulo').html(" ");
//                    div.parent().parent().parent().parent().parent().find('.nombre-articulo').append(op);
//                    div.find('.nombre-articulo').html(" ");
//                    div.find('.nombre-articulo').append(op);
//                    alert($(".lista-proveedores").prop('selectedIndex'));
                    //div.parent().parent().parent().parent().parent().find('.nombre-articulo').unbind('change')
                },
                error:function(){

                }
            });
        });
    });
    var cont = 0;
    total = 0;
    subtotal=[];
    $('#guardar').show();

    while($('#fila' + cont).find("td:eq(3)").length != 0 ){
        subtotal[cont] = ($($('#fila' + cont).find("td:eq(2)").html()).val() * $($('#fila' + cont).find("td:eq(3)").html()).val());
        total +=  $($('#fila' + cont).find("td:eq(2)").html()).val() *  $($('#fila' + cont).find("td:eq(3)").html()).val();
        $($('#fila' + cont)).append('<td>'+subtotal[cont]+'</td>');
        cont++;
    }
    $('#total').html('$: ' + total);

    function actualizar() {
        var b =  $('#pprecio_venta').val();
        var cantidad =  $('#pcantidad').val();
        $('#pprecio_venta_cantidad').val(cantidad *b);
    }
    
    function limpiar(){
        $('#pcantidad').val("");
        $('#pdescuento').val("");
        $('#pprecio_venta').val("");
    }

    function evaluar(){
        if(total>0){
            $('#guardar').show();
        }
        else{
            $('#guardar').hide();
        }
    }
    
    function agregar() {
        datosArticulo = document.getElementById('pidarticulo').value.split('_');

        idarticulo=datosArticulo[0];
        articulo = $('#pidarticulo option:selected').text();
        cantidad = $('#pcantidad').val();

        precio_venta = $('#pprecio_venta').val();

        if(idarticulo!='' && cantidad!='' && cantidad>0 && precio_venta!=''){

                subtotal[cont] = (cantidad*precio_venta);
                total = total+subtotal[cont];
                var fila = '<tr class="selected" id="fila'+cont+'">' +
                    '<td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td>' +
                    '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>' +
                    '<td><input type="number" name="cantidad[]" value="'+cantidad+'"></td>' +
                    '<td><input type="number" name="precio_venta[]" value="'+precio_venta+'"></td>' +
                    '<td><input type="number" name="descuento[]" value="0"></td>' +
                    '<td>'+subtotal[cont]+'</td>' +
                    '</tr>';
                cont++;
                limpiar();
                $('#total').html('$: ' + total);
                $('#total_venta').val(total);
                evaluar();
                $('#detalles').append(fila);


        }
        else{
            alert("error al ingresar uun detalle de la venta, revise los datos del articulo");
        }
    }

    function eliminar(index) {
        total=total - subtotal[index];
        $('#total').html("S/. " + total);
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