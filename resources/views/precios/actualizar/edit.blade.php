@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Editar Categoría: {{$categoria->nombre}}
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

            {{--// el idcategoria es porque en categoriacontroller el metodo update recibe un id.--}}
            {!! Form::model($categoria, ['method'=>'PATCH','route'=>['categoria.update',$categoria->idcategoria]])!!}
            {{Form::token()}}
            <div class="from-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{$categoria->nombre}}">
            </div>
            <div class="from-group">
                <label for="descripcion">Descripcion</label>
                <input type="text" name="descripcion" class="form-control" value="{{$categoria->descripcion}}">
            </div>
            <div class="from-group">
                <label for="descripcion">Condición</label>
                <input type="text" name="descripcion" class="form-control" value="{{$categoria->condicion}}">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Reset</button>
            </div>
            {!! Form::close()!!}
        </div>
    </div>

@endsection