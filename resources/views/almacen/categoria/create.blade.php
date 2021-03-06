@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Nueva Categoría
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

            {!! Form::open(array('url'=>'almacen/categoria', 'method'=>'POST', 'autocomplete'=>'off'))!!}
            {{Form::token()}}
            <div class="from-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre...">
            </div>
            <div class="from-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" class="form-control" placeholder="Descripcion...">
            </div>
            <div class="from-group">
                <label for="descripcion">lastPage</label>
                <input type="hidden" name="lastPage" class="form-control" value="{{isset($_GET['lastPage']) ? $_GET['lastPage'] : ""}}">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Reset</button>
            </div>
            {!! Form::close()!!}
        </div>
    </div>

@endsection