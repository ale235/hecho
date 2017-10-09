@extends ('layouts.admin')
@section ('contenido')
    <html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        </script>
    </head>
    <body>
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><span> {{$articulosSinStock}}</span></h3>

                        <p>Sin stock</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{URL::action('ReportesController@getDetalleStock')}}" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><sup style="font-size: 20px">$</sup><span id="caja_del_dia"></span></h3>
                        <p>Caja actual</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{URL::action('ReportesController@getCajaDeHoy')}}" class="small-box-footer" >Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><sup style="font-size: 20px">$</sup><span id="caja_de_ayer"></span></h3>

                        <p>Caja de ayer</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{URL::action('ReportesController@getCajaDeAyer')}}" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        {{--<div class="row">--}}
                            {{--<div class="col-lg-5">--}}
                                <h3><sup style="font-size: 20px">$</sup><span id="ganancias"></span></h3>
                            {{--</div>--}}
                            {{--<div class="col-lg-7">--}}
                                {{--<input type="text" class="form-control" id="daterange" name="daterange"/>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <p>Ganancias del mes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{URL::action('ReportesController@getDetalleGanancias')}}" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <div style="display: none" class="container col-lg-12 ">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div id="sales-chart" style="position: relative; height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="container col-lg-12 ">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div id="myChart" style="height: 100%;width: 100%;"></div>
                        <div id='myChart2'></div>
                    </div>
                </div>
            </div>
            {{--<div class="container col-lg-12 ">--}}
                {{--<div class="panel panel-info">--}}
                    {{--<div class="panel-body">--}}
                        {{--<div id="graph" style="position: relative; height: 300px;"></div>--}}
                        {{--<div id="myChart"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <!-- Left col -->
            {{--<section class="col-lg-12 connectedSortable">--}}
                {{--<!-- Custom tabs (Charts with tabs)-->--}}
                {{--<div class="nav-tabs-custom">--}}
                    {{--<!-- Tabs within a box -->--}}
                    {{--<ul class="nav nav-tabs pull-right">--}}
                        {{--<li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>--}}
                        {{--<li><a href="#sales-chart" data-toggle="tab">Donut</a></li>--}}
                        {{--<li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>--}}
                    {{--</ul>--}}
                    {{--<div class="tab-content no-padding">--}}
                        {{--<!-- Morris chart - Sales -->--}}
                        {{--<div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>--}}
                        {{--<div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<!-- /.nav-tabs-custom -->--}}


            {{--</section>--}}
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->

    </section>
    </body>
    </html>

@endsection
@push('scripts')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
<script>
//    zingchart.render({
//        id: 'myChart',
//        data: {
//            type: "bar",
//            plotarea: {
//                adjustLayout: true
//            },
//            scaleX: {
//                label: {
//                    text: "Here is a category scale"
//                },
//                labels: ["Jan", "Feb", "March", "April", "May", "June", "July", "Aug"]
//            },
//            series: [{
//                values: [20, 40, 25, 50, 15, 45, 33, 34]
//            }, {
//                values: [5, 30, 21, 18, 59, 50, 28, 33]
//            }]
//        }
//    });
</script>
<script>
    $.ajax({
        type: 'get',
        url: '{!!URL::to('ventasPorProductos')!!}',
        success: function (data) {
        console.log(data);
        var labels = data.map(function (x) {
           return x.nombre;
        });
        var dataChart = data.map(function (x) {
            return x.cantidadTotal;
        });
            zingchart.render({
                id: 'myChart',
                data: {
                    type: "bar",
                    plot: {
                        "tooltip":{ //Standard Tooltips
                            "text":"%kl se vendió <br>%v.",
                            "placement":"node:top",
                            "padding":"10%",
                            "border-radius":"5px"
                        }
                    },
                    plotarea: {
                        adjustLayout: true,
                    },
                    scaleX: {
                        label: {
                            text: "Productos más vendidos"
                        },
                        labels: labels
                    },
                    series: [{
                        values: dataChart
                    }]
                }
            });
        },
        error: function () {

        }
    });

</script>
<script>
    $(document).ready(function () {
        $.ajax({
            type: 'get',
            url: '{!!URL::to('articulosSinStock')!!}',
            success: function (data) {
                //console.log('success');
                $("#sin_stock").text(data[0].cantidad);

            },
            error: function () {

            }
        });

        $.ajax({
            type: 'get',
            url: '{!!URL::to('cajaDelDiaReportes')!!}',
            success: function (data) {
                //console.log('success');
                $("#caja_del_dia").text(data[0].total);
            },
            error: function () {

            }
        });

        $.ajax({
            type: 'get',
            url: '{!!URL::to('cajaDeAyer')!!}',
            success: function (data) {
                //console.log('success');
                $("#caja_de_ayer").text(data[0].total);
            },
            error: function () {

            }
        });

        $.ajax({
            type: 'get',
            url: '{!!URL::to('proveedorQueMasProductosVende')!!}',
            success: function (data) {
                //console.log('success');
                google.charts.load('current', {'packages':['corechart','bar']});

                google.charts.setOnLoadCallback(function(){ drawBar(data) });



                function drawBar(data) {
                    var output =    data.map(function(obj) {
                        return Object.keys(obj).sort().map(function(key) {
                            return obj[key];
                        });
                    });
//                    output.forEach(function(element) {
//                        element[0] = parseInt(element[0]);
//                    });
                    output.reverse();

                    output.unshift(['Cantidad','Proveedor']);
                    var datav = new google.visualization.arrayToDataTable(output);

                    var options = {
                        title: 'Proveedor que más productos vende',
                        legend: { position: 'none' },
//                        chart: { subtitle: 'popularity by percentage' },
                        axes: {
                            x: {
//                                0: { side: 'top', label: 'Productos más vendidos'} // Top x-axis.
                            }
                        },
                        bar: { groupWidth: "100%" }
                    };

                    var chart = new google.charts.Bar(document.getElementById('sales-chart'));
                    // Convert the Classic options to Material options.
                    chart.draw(datav, google.charts.Bar.convertOptions(options));
                };

            },
            error: function () {

            }
        });

        $.ajax({

            type:'get',
            url:'{!!URL::to('ganancias')!!}',
            success:function(result) {
                $("#ganancias").text(result[0].ganancia);

            },
            error: function(a){
            }
        });


    });

</script>
@endpush
