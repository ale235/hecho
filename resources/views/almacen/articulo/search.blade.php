{!! Form::open(array('url'=>'almacen/articulo', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search', 'class'=>'form-horizontal')) !!}
<div class="container">
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
        <label class="col-md-4 control-label">Código del artículo</label>
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
</div>
{{Form::close()}}