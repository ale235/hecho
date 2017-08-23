{!! Form::open(array('url'=>'almacen/articulo', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search')) !!}
<div class="box-body">
    <div class="input-group">
        <input type="text" name="searchText" class="form-control" value="{{$searchText}}" placeholder="Nombre">
        <span class="input-group-addon">Nombre del Artículo</span>
    </div>
    <br>

    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
        <input  type="text" name="searchText2" class="form-control" value="{{$searchText2}}" placeholder="Código de Barras">
        <span class="input-group-addon">Código de Barras</span>

    </div>
    <br>

    <div class="form-group">
        <div class="input-group">
            <select class="form-control" id="selectText" name="selectText">
                @foreach($estados as $estado)
                    <option value="{{$estado->estado}}">{{$estado->estado}}</option>
                @endforeach
            </select>
            <span class="input-group-addon">Estado</span>
        </div>
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