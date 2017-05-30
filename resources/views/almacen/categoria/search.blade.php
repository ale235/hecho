{!! Form::open(array('url'=>'almacen/categoria', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search','class'=>'form-horizontal')) !!}
<div class="container">
<div class="form-group">
    <label class="col-md-4 control-label">Nombre de la Categoría</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-check"></i></span>
        <input type="text" class="form-control" name="searchText" placeholder="Buscar por nombre de categoría" value="{{$searchText}}">
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label">Estado de la Categoría</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-check"></i></span>
        <select class="form-control" id="select-categoria" name="select-categoria">
            @foreach($condiciones as $condicion)
                <option value="{{$condicion->condicion}}">{{$condicion->condicion == 1 ? 'Activo' : "Inactivo"}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-md-4 control-label">Filtrar por los campos</label>
    <button type="submit" class="btn btn-primary">Filtrar</button>
</div>
</div>
{{Form::close()}}