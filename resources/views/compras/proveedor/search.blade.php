{!! Form::open(array('url'=>'compras/proveedor', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search', 'class'=>'form-horizontal')) !!}
<div class="container">
    <div class="form-group">
        <label class="col-md-4 control-label">Código del Proveedor</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <input type="text" class="form-control" name="searchText" placeholder="Buscar por código del proveedor"
                       value="{{$searchText}}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Estado del proveedor</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                <select class="form-control" id="selectText" name="selectText">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
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
</div>
{{Form::close()}}