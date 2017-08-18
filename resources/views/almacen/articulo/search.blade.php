{!! Form::open(array('url'=>'almacen/articulo', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search')) !!}
<div class="box-body">
    <div class="input-group">
        <span class="input-group-addon">Nombre del Artículo</span>
        <input type="text" name="nombre"  value="{{old('nombre')}}" class="form-control" placeholder="Nombre">
    </div>
    <br>

    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">Categoría</span>
            <select name="idcategoria" class="form-control">

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

            </select>
            <input type="hidden" name="idproveedorsolo" id="idproveedorsolo" value="{{old('idproveedorsolo')}}">
            <span class="input-group-btn">
                        <a href="{{ url('compras/proveedor/create?lastPage=art') }}"><button type="button" class="btn btn-info btn-flat">Nuevo Proveedor</button></a>
                    </span>
        </div>
    </div>
    <br>

    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
        <input  type="text" name="barcode" id="barcode" value="{{old('barcode')}}"  class="form-control" placeholder="Código de Barras">
    </div>
    <br>
    <!-- /.box-body -->
</div>
<div class="box-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
{{--<div class="container">
    <div class="form-group">
        <label class="col-md-4 control-label">Nombre del artículo</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <input type="text" class="form-control" name="searchText" placeholder="Buscar por nombre del artículo..." value="{{$searchText}}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Código de Barras</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <input type="text" class="form-control" name="searchText2" placeholder="Buscar..." value="{{$searchText2}}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Estado del artículo</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <select class="form-control" id="selectText" name="selectText">
                    @foreach($estados as $estado)
                        <option value="{{$estado->estado}}">{{$estado->estado}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Acción</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
               <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">Filtrar</button>
                </span>
            </div>
        </div>
    </div>
</div>--}}
{{Form::close()}}