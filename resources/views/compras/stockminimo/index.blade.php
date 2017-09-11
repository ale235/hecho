@extends ('layouts.admin')
@section ('contenido')
    {{--<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Latest Orders</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Popularity</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR9842</a></td>
                        <td>Call of Duty IV</td>
                        <td><span class="label label-success">Shipped</span></td>
                        <td>
                            <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR1848</a></td>
                        <td>Samsung Smart TV</td>
                        <td><span class="label label-warning">Pending</span></td>
                        <td>
                            <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR7429</a></td>
                        <td>iPhone 6 Plus</td>
                        <td><span class="label label-danger">Delivered</span></td>
                        <td>
                            <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR7429</a></td>
                        <td>Samsung Smart TV</td>
                        <td><span class="label label-info">Processing</span></td>
                        <td>
                            <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR1848</a></td>
                        <td>Samsung Smart TV</td>
                        <td><span class="label label-warning">Pending</span></td>
                        <td>
                            <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR7429</a></td>
                        <td>iPhone 6 Plus</td>
                        <td><span class="label label-danger">Delivered</span></td>
                        <td>
                            <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="pages/examples/invoice.html">OR9842</a></td>
                        <td>Call of Duty IV</td>
                        <td><span class="label label-success">Shipped</span></td>
                        <td>
                            <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
            <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
        </div>
        <!-- /.box-footer -->
    </div>--}}
    <div id="rootwizard">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container">
                    <ul>
                        <li><a href="#tab1" data-toggle="tab">First</a></li>
                        <li><a href="#tab2" data-toggle="tab">Second</a></li>
                        <li><a href="#tab3" data-toggle="tab">Third</a></li>
                        <li><a href="#tab4" data-toggle="tab">Forth</a></li>
                        <li><a href="#tab5" data-toggle="tab">Fifth</a></li>
                        <li><a href="#tab6" data-toggle="tab">Sixth</a></li>
                        <li><a href="#tab7" data-toggle="tab">Seventh</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="bar" class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="tab1">
                1
            </div>
            <div class="tab-pane" id="tab2">
                <p>
                    <input type='text' name='name' id='name' placeholder='Enter Your Name'>
                </p>
            </div>
            <div class="tab-pane" id="tab3">
                3
            </div>
            <div class="tab-pane" id="tab4">
                4
            </div>
            <div class="tab-pane" id="tab5">
                5
            </div>
            <div class="tab-pane" id="tab6">
                6
            </div>
            <div class="tab-pane" id="tab7">
                7
            </div>
            <ul class="pager wizard">
                <li class="previous first" style="display:none;"><a href="#">First</a></li>
                <li class="previous"><a href="#">Previous</a></li>
                <li class="next last" style="display:none;"><a href="#">Last</a></li>
                <li class="next"><a href="#">Next</a></li>
            </ul>
        </div>
    </div>
@endsection
@push ('scripts')
<script>
    $(document).ready(function() {
        $('#rootwizard').bootstrapWizard({onNext: function(tab, navigation, index) {
            if(index==2) {
                // Make sure we entered the name
                if(!$('#name').val()) {
                    alert('You must enter your name');
                    $('#name').focus();
                    return false;
                }
            }

            // Set the name for the next tab
            $('#tab3').html('Hello, ' + $('#name').val());

        }, onTabShow: function(tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index+1;
            var $percent = ($current/$total) * 100;
            $('#rootwizard .progress-bar').css({width:$percent+'%'});
        }});
    });
</script>
@endpush