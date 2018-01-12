@extends ('layouts.admin')
@section ('contenido')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Editar Artículo</h3>
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
        {!! Form::model($articulo, ['method'=>'PATCH','route'=>['articulo.update',$articulo->idarticulo], 'files'=>'true'])!!}
        {{Form::token()}}
        <div class="box-body">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                <input  type="text" name="barcode" id="barcode" value="{{$articulo->barcode}}"  class="form-control" placeholder="Código de Barras">
            </div>
            <br>

            <div class="input-group">
                <span class="input-group-addon">Nombre</span>
                <input type="text" name="nombre" id="nombre" value="{{$articulo->nombre}}" class="form-control" placeholder="Nombre">
            </div>
            <br>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Categoría</span>
                    <select name="idcategoria" class="form-control">
                        @foreach($categorias as $cat)
                            @if ($cat->idcategoria == $articulo->idcategoria)
                                <option value="{{$cat->idcategoria}}" selected>{{$cat->nombre}}</option>
                            @else
                                <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a href="{{ url('almacen/categoria/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nueva Categoría</button></a>
                    </span>
                </div>
            </div>
            <br>

            {{--<div class="form-group">--}}
                {{--<div class="input-group">--}}
                    {{--<span class="input-group-addon">Proveedor</span>--}}
                    {{--<select name="idproveedores" class="form-control">--}}
                        {{--@foreach($proveedores as $prov)--}}
                            {{--<option value="{{$prov->idpersona}}">{{$prov->codigo}}</option>--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                    {{--<span class="input-group-btn">--}}
                        {{--<a href="{{ url('compras/proveedor/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nuevo Proveedor</button></a>--}}
                    {{--</span>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<br>--}}

            <div style="display: none" class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <div class="from-group">
                    <label for="stock">Codigo</label>
                    <input type="text" name="codigo" id="codigo" value="{{$articulo->codigo}}" class="form-control" placeholder="Código...">
                </div>
            </div>

            <hr size="60" />

            <div class="input-group">
                <span id="inputdelexistencia" style="display: none" class="input-group-addon">Hay <span id="existencia"></span> artículos en Stock</span>
                <input type="number" name="pcantidad" id="pcantidad" value="{{$articulo->stock}}" class="form-control" placeholder="Cantidad">
                <span class="input-group-addon">Cantidad de árticulos en existencia (Stock)</span>
            </div>
            <br>
            <!-- /input-group -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="reset" class="btn btn-default">Cancelar</button>
            <button type="submit" class="btn btn-info pull-right">Cargar Artículo</button>
        </div>
        {!! Form::close()!!}
    </div>
@endsection