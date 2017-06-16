@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Nuevo Artículo
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
    {!! Form::open(array('url'=>'almacen/articulo', 'method'=>'POST', 'autocomplete'=>'off', 'files'=>'true', 'novalidate' => 'novalidate'))!!}
    {{Form::token()}}
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            {{--<div class="from-group">--}}
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre"  value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
            {{--</div>--}}
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            {{--<div class="form-group">--}}
                <label>Categoria</label>
                <select name="idcategoria" class="form-control">
                    @foreach($categorias as $cat)
                        <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                    @endforeach
                </select>
            {{--</div>--}}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <h3>
                <a href="{{ url('almacen/categoria/create?lastPage=art') }}"><button type="button" class="btn btn-success">Nueva Categoría</button></a>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            {{--<div class="form-group">--}}
                <label>Proveedores</label>
                <select name="idproveedores" id="idproveedores" class="form-control">
                        <option selected>Seleccione el Proveedor</option>
                    @foreach($proveedores as $prov)
                        <option value="{{$prov->codigo}}">{{$prov->codigo}}</option>
                    @endforeach
                </select>
            <input type="hidden" name="idproveedorsolo" id="idproveedorsolo" value="{{old('idproveedorsolo')}}">
            {{--</div>--}}
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <h3>
                <a href="{{ url('compras/proveedor/create?lastPage=art') }}"><button type="button" class="btn btn-success">Nuevo Proveedor</button></a>
            </h3>
        </div>
        <div style="display: none" class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            {{--<div class="from-group">--}}
                <label for="stock">Codigo</label>
                <input type="text" name="codigo" id="codigo" value="{{old('codigo')}}" class="form-control" placeholder="Código...">
            {{--</div>--}}
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            {{--<div class="from-group">--}}
            <label for="barcode">Codigo de Barras</label>
            <input type="text" name="barcode" id="barcode" value="{{old('barcode')}}" class="form-control" placeholder="Codigo de barras...">
            {{--</div>--}}
        </div>
    </div>

    <hr size="20" />

    <div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="number" name="pcantidad" id="pcantidad" class="form-control" onkeyup="actualizar()" placeholder="Cantidad">
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="precio_compra_costo">Precio de Compra</label>
                <input type="number" name="pprecio_compra_costo" id="pprecio_compra_costo" class="form-control" onkeyup="actualizar()" placeholder="Precio de Compra">
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="porcentaje_venta">Porcentaje de venta</label>
                <input type="number" name="pporcentaje_venta" id="pporcentaje_venta" class="form-control" onkeypress="return valida(event)" onkeyup="actualizar()" placeholder="Porcentaje de Venta">
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="precio_venta">Precio de venta Esperado</label>
                <input type="number" name="pprecio_venta_esperado" id="pprecio_venta_esperado" class="form-control">
            </div>
        </div>
    </div>
    {{--<div class="row">--}}
        {{--<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">--}}
            {{--<div class="from-group">--}}
            {{--<label for="imagen">Imagen</label>--}}
            {{--<input type="file" name="imagen" class="form-control">--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">--}}
            {{--<div class="from-group">--}}
                {{--<label for="descripcion">Descripción</label>--}}
                {{--<input type="text" name="descripcion" value="{{old('descripcion')}}" class="form-control" placeholder="Descripcion del artículo...">--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Reset</button>
            </div>
        </div>
    </div>

    {!! Form::close()!!}
@endsection

@push ('scripts')
<script>
    var casa = '<?php echo $articulos ?>';
    $(document).ready(function () {
        articulo = $('#idproveedores option:selected').text();
        $('#idproveedores').change(function () {
            agregarprov();
        })

        $(document).on('change','#idproveedores',function(){
            // console.log("hmm its change");

            var cat_id=$(this).val();

            $.ajax({
                type:'get',
                url:'{!!URL::to('buscarProveedor')!!}',
                data:{'codigo':cat_id},
                success:function(data){
                    $('#idproveedorsolo').val(data[0].idpersona);

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
    });

function agregarprov() {
    var proveedorselected = $('#idproveedores option:selected').text();
    var obj = JSON.parse(casa);
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
    }
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
@endpush