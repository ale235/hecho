@extends ('layouts.admin')
@section ('contenido')
<div class="row">
    <div class="container col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2>Actualizar Precios</h2>
        <p><strong>Nota:</strong> Seleccioná la forma en que querés actualizar los precios.</p>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Actualizar el precio de un solo ártículo.</a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <div class="panel-body">
                        {!! Form::open(array('url'=>'precios/actualizar', 'method'=>'POST', 'autocomplete'=>'off'))!!}
                        {{Form::token()}}
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
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
                                <select name="pidarticulo" id="pidarticulo" class="lista-articulo form-control">
                                    <option value="0" disabled="true" selected="true">Artículos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                            <div class="form-group">
                                <label>Porcentaje de Venta</label>
                                <input type="number" name="pporcentaje_venta" id="pporcentaje_venta" class="form-control" onkeypress="return valida(event)" onkeyup="actualizar()" placeholder="Porcenaje de venta">
                            </div>
                        </div>
                        <input name="_token" value="{{csrf_token()}}" type="hidden">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Actualizar el precio de una familia (proveedor) de artículos.</a>
                    </h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                    <div class="panel-body">
                        {!! Form::open(array('url'=>'precios/actualizar', 'method'=>'POST', 'autocomplete'=>'off', 'class'=>'form-horizontal'))!!}
                        {{Form::token()}}
                        <div class="container">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Nombre del artículo</label>
                                <div class="col-md-4 inputGroupContainer">
                                    <select name="fproveedor" id="fproveedor" class="lista-fproveedores form-control">
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
                                <th>Porcentaje Actual</th>
                                <th>Precio de compra actual</th>
                                <th>Precio de venta actial</th>
                                <th>Nuevo porcentaje</th>
                                {{--<th>Subtotal</th>--}}
                                </thead>
                                <tfoot>
                                <th>TOTAL</th>
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push ('scripts')
<script>
    $(document).ready(function () {
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
                div.parent().parent().parent().parent().parent().find('.lista-articulo').html(" ");
                div.parent().parent().parent().parent().parent().find('.lista-articulo').append(op);
            },
            error:function(){

            }
        });
    });
    $(document).on('change','.lista-articulo',function(){
            var cat_id=$(this).val();
            var div=$(this).parent();

            var op=" ";

            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarPrecioArticulo')!!}',
                data:{'id':cat_id},
                success:function(data){
                    //console.log('success');

                    console.log(data);

                    $('#pporcentaje_venta').val(data[0].porcentaje);
                },
                error:function(){

                }
            });
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
                    '<td><label type="number">$'+data.precio_compra+'</label></td>' +
                    '<td><label type="number">$'+data.precio_venta+'</label></td>' +
                    '<td><input type="number" name="nuevo_porcentaje[]" value='+data.porcentaje+'></td>' +
                    '</tr>';
                cont++;


                $('#detalles').append(fila);

        }
    });
</script>
@endpush