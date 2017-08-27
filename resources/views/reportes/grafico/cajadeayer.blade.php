@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color: #a94442">
                <th>Fecha venta</th>
                <th>Total compra</th>
                <th>Total venta</th>
                <th>Total venta real</th>
                </thead>
                <tfoot>
                <th></th>
                <th><h4 id="total_compra">{{$collection->sum('total_compra')}}</h4></th>
                <th><h4 id="total_venta">{{$collection->sum('total_venta')}}</h4></th>
                <th><h4 id="total_venta_real">{{$collection->sum('total_venta_real')}}</h4></th>
                </tfoot>
                <tbody>
                @foreach($collection as $det)
                    <tr>
                        <td>{{$det->fecha_hora}}</td>
                        <td>{{$det->total_compra}}</td>
                        <td>{{$det->total_venta}}</td>
                        <td>{{$det->total_venta_real}}</td>
                        {{--<td><a href="{{URL::action('ReportesController@volveracero',$det->idarticulo)}}"><button class="btn btn-primary">Volver a cero</button></a></td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $collection->render() !!}
        </div>
    </div>
@endsection
@push('scripts')
<script>

</script>
@endpush
