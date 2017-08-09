{!! Form::open(array('url'=>'ventas/venta', 'method'=>'GET', 'autocomplete'=>'off', 'role'=>'search', 'class'=>'form-horizontal', 'id'=>'elform')) !!}
<div class="container" style="padding-top: 10px">
    <div class="form-group">
        <label class="col-md-4 control-label">Fecha a Filtrar</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="daterange" name="daterange"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Acción</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
               <span class="input-group-btn">
            <button id="submit_ventas" type="submit" class="btn btn-primary">Filtrar</button>
                </span>
            </div>
        </div>
    </div>
</div>
{{--<input type="text" name="daterange"/>--}}
{{Form::close()}}