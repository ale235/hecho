@extends ('layouts.admin')
@section ('contenido')

     <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>
                Editar Cliente: {{$persona->nombre}}
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
            {!! Form::model($persona, ['method'=>'PATCH','route'=>['cliente.update',$persona->idarticulo]])!!}
            {{Form::token()}}
     <div class="row">
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
             <div class="from-group">
                 <label for="nombre">Nombre</label>
                 <input type="text" name="nombre" required value="{{$persona->nombre}}" class="form-control" placeholder="Nombre...">
             </div>
         </div>
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
             <div class="from-group">
                 <label for="direccion">Direccion</label>
                 <input type="text" name="direccion" value="{{$persona->direccion}}" class="form-control" placeholder="Direccion...">
             </div>
         </div>
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
             <div class="form-group">
                 <label>Documento</label>
                 <select name="tipo_documento" selected class="form-control">
                     <option value="DNI">DNI</option>
                 </select>
             </div>
         </div>
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
             <div class="from-group">
                 <label for="num_documento">Numero de Documento</label>
                 <input type="text" name="num_documento" value="{{$persona->num_documento}}" class="form-control" placeholder="Número de documento...">
             </div>
         </div>
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
             <div class="from-group">
                 <label for="telefono">Teléfono</label>
                 <input type="text" name="telefono" value="{{$persona->telefono}}" class="form-control" placeholder="Teléfono...">
             </div>
         </div>
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
             <div class="from-group">
                 <label for="email">E-mail</label>
                 <input type="text" name="email" value="{{$persona->email}}" class="form-control" placeholder="E-mail...">
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