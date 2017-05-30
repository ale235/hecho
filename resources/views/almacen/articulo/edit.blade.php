@extends ('layouts.admin')
@section ('contenido')

     <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Editar Artículo: {{$articulo->nombre}}
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
            {{--// el idcategoria es porque en categoriacontroller el metodo update recibe un id.--}}
            {!! Form::model($articulo, ['method'=>'PATCH','route'=>['articulo.update',$articulo->idarticulo], 'files'=>'true'])!!}
            {{Form::token()}}
    <div class="row">
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{$articulo->nombre}}" class="form-control">
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <label>Categoria</label>
                <select name="idcategoria" class="form-control">
                    @foreach($categorias as $cat)
                        @if ($cat->idcategoria == $articulo->idcategoria)
                            <option value="{{$cat->idcategoria}}" selected>{{$cat->nombre}}</option>
                        @else
                            <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                        @endif
                    @endforeach
                </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <h3>
                <a href="{{ url('almacen/categoria/create?lastPage=art') }}"><button type="button" class="btn btn-success">Nueva Categoría</button></a>
            </h3>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <label>Proveedores</label>
                <select name="idproveedores" class="form-control">
                    @foreach($proveedores as $prov)
                        <option value="{{$prov->idpersona}}">{{$prov->codigo}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <h3>
                <a href="{{ url('compras/proveedor/create?lastPage=art') }}"><button type="button" class="btn btn-success">Nuevo Proveedor</button></a>
            </h3>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <label for="codigo">Codigo</label>
                <input type="text" name="codigo" required value="{{$articulo->codigo}}" class="form-control">
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" class="form-control">
            @if (($articulo->imagen) != '')
            <img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" height="300px" width="300px">
            @endif
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" value="{{$articulo->descripcion}}" class="form-control" placeholder="Descripcion del artículo...">
            </div>
        </div>
        {{--<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">--}}
            {{--<div class="from-group">--}}
                {{--<label for="imagen">Imagen</label>--}}
                {{--<input type="file" name="imagen" class="form-control">--}}
                {{--@if (($articulo->imagen) != '')--}}
                    {{--<img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" height="300px" width="300px">--}}
                {{--@endif--}}
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
    </div>
    {!! Form::close()!!}
@endsection