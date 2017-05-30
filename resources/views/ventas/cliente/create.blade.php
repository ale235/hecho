@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Nuevo Cliente
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
            {!! Form::open(array('url'=>'ventas/cliente', 'method'=>'POST', 'autocomplete'=>'off'))!!}
            {{Form::token()}}
    <div class="row">
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="codigo">Codigo</label>
                <input type="text" name="codigo" value="{{old('codigo')}}" class="form-control" placeholder="Código del cliente...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" value="{{old('telefono')}}" class="form-control" placeholder="Teléfono...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="direccion">Direccion</label>
                <input type="text" name="direccion" value="{{old('direccion')}}" class="form-control" placeholder="Direccion...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label>Documento</label>
                <select name="tipo_documento" class="form-control">
                    <option value="DNI">DNI</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="num_documento">Numero de Documento</label>
                <input type="text" name="num_documento" value="{{old('num_documento')}}" class="form-control" placeholder="Número de documento...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="facebook">Facebook</label>
                <input type="text" name="facebook" value="{{old('facebook')}}" class="form-control" placeholder="Facebook...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="instagram">Instagram</label>
                <input type="text" name="instagram" value="{{old('intagram')}}" class="form-control" placeholder="Instagram...">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="from-group">
                <label for="email">E-mail</label>
                <input type="text" name="email" value="{{old('email')}}" class="form-control" placeholder="E-mail...">
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Reset</button>
            </div>
        </div>
    </div>

            {!! Form::close()!!}
@endsection