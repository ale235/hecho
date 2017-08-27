@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead style="background-color: #a94442">
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Cantidad</th>
                    <th>Precio Venta</th>
                    </thead>
                    <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="color: black;">
                        <?php $a = 0; ?>
                        @foreach($detalle_venta_hoy as $vent)
                            <?php $a = $a + ($vent->precio_venta * $vent->cantidad) ?>
                        @endforeach
                        ${{$a}}
                    </th>
                    </tfoot>
                    <tbody>
                    @foreach($detalle_venta_hoy as $vent)
                        <tr>
                            <td style="color: black;">{{$vent->nombre}}</td>
                            <td style="color: black;">{{$vent->codigo}}</td>
                            <td style="color: black;">{{$vent->cantidad}}</td>
                            <td style="color: black;">{{$vent->precio_venta}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {!! $detalle_venta_hoy->render() !!}
        </div>
    </div>
@endsection
@push('scripts')
<script>

</script>
@endpush
