@extends('admin/layouts.header-sidebar')
@section('title', 'Sales Reports')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style type="text/css">
    td{
        padding: 5px !important;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            
            <div class="col-md-8 col-sm-12">
                <h2>Sales Reports</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard')  }}"><i class="icon-speedometer"></i> Dashboard</a>
                        </li>

                        <li class="breadcrumb-item active">
                            <i class="fa fa-shopping-cart"></i> Sales Reports
                        </li>

                    </ol>
                </nav>
            </div>

            <div class="col-md-4 col-sm-12 text-right hidden-xs">
                <a href="{{ url('admin.dashboard') }}" class="btn btn-outline-primary btn-round"><i class="fa fa-angle-double-left"></i> Go Back</a>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12">
            
            @php
                $salesStatus = array('0' => ['Unpaid', 'danger'],
                                     '1' => ['Paid', 'success'],
                                     '2' => ['Mix', 'primary']
                                    );
            @endphp
            
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link show active" data-toggle="tab" href="#SalesReports">Sales Reports</a>
                </li>
            </ul>

            <div class="tab-content mt-0">
                
                <div class="tab-pane show active" id="SalesReports">
                    <div class="card border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="title mb-0">{{ isset($_GET['vendor']) && $_GET['vendor'] != 'None' ? ucfirst(str_replace('-', ' ', $_GET['vendor'])). ' - Sales Report' : 'Sales Reports' }}</h6>
                        </div>
                        <div class="card-body p-2">
                            <form action="{{ url()->full() }}" method="get">
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Vendor</span>
                                            </div>
                                            <select class="form-control" name="vendor">
                                                <option selected="">None</option>
                                                @foreach($vendors as $key => $vendor)
                                                    <option {{ isset($_GET['vendor']) && $_GET['vendor'] == $vendor->slug ? 'selected' : ''}} value="{{ $vendor->slug }}">{{ $vendor->store_name }}</option>
                                                    {{-- <a href="{{ route('admin.sales-reports.list',['vendor' => $vendor->slug]) }}" class="btn btn-info dropdown-item" >{{ $vendor->store_name }}</a> --}}
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">

                                        <div class="input-daterange input-group" id="datepicker">

                                            <input type="text" class="input-sm form-control" name="start_date" required placeholder="Start Date" value="{{ isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m',strtotime("-1 month")) }}" />

                                            <div class="input-group-append" style="background-color: #e1e8ed;">
                                                <span class="input-group-text">to</span>
                                            </div>
                                            
                                            <input type="text" class="input-sm form-control" name="end_date" required placeholder="End Date" value="{{ isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m',strtotime("-1 month")) }}"/>

                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fa fa-search"></i> Filter</button>
                                            </div>
                                        </div>

                                    </div>
                                    @if(request()->get('vendor'))
                                    <div class="col-md-2">
                                        <a href="{{ route('admin.sales-reports.list') }}" class="btn btn-sm btn-outline-danger"><i class="fa fa-times"></i> Clear Filter</a>
                                    </div>
                                    @endif
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        
                                        <strong>
                                            <span style="font-size: 24px;">S</span>ales <span style="font-size: 24px;">R</span>eport :
                                            <span style="font-size: 20px; color: brown;">
                                                @if($report_start_from->equalTo($report_end_to))
                                                    {{ $report_start_from->format('F Y') }}
                                                @else
                                                    {{ $report_start_from->format('F Y') }} to {{ $report_end_to->format('F Y') }}
                                                @endif
                                            </span>
                                        </strong>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing5 dataTable sales-table">
                                            <thead style="color: brown;">
                                                <tr>
                                                    <th><strong>Date</strong></th>
                                                    <th><strong>Vendor </strong></th>
                                                    <th class="text-right"><strong>Total Sales</strong></th>
                                                    <th class="text-right"><strong>Return/Refund</strong></th>
                                                    <th class="text-right"><strong>Total Net Sales</strong></th>
                                                    <th class="text-right"><strong>Commission</strong></th>
                                                    <th class="text-right"><strong>Payment to Vendor</strong></th>
                                                    <th><strong>Status</strong></th>
                                                    <th class="text-center"><strong>Actions</strong></th>
                                                </tr>
                                            </thead>
                                            @if(count($sales_reports) > 0)
                                            <tbody id="product_variations_field">

                                                @foreach($sales_reports as $sales_report)

                                                <tr>
                                                    <td>{{ date('M Y',strtotime($sales_report->report_date)) }}</td>
                                                    <td >
                                                        <strong>{{ $sales_report->vendor_name }}</strong>
                                                        @php
                                                            
                                                            $words = explode(" ", $sales_report->vendor_name);
                                                            $acronym = "";

                                                            foreach ($words as $w) {
                                                                $acronym .= $w[0];
                                                            }

                                                        @endphp

                                                        <small>({{ $acronym. (2021) }})</small>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="total_sales">{{ number_format($sales_report->total_sales, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="return_refund">{{ number_format($sales_report->return_refund, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        <strong>$<span class="total_net_sales">{{ number_format($sales_report->total_net_sales, 2, '.', '') }}</span></strong>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="commission">
                                                            {{ number_format($sales_report->commission, 2, '.', '') }} 
                                                            <small>({{ $sales_report->commission_percentage }}%)</small>
                                                        </span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="total_payment_to_vendor">{{ number_format($sales_report->total_payment_to_vendor, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="sales-report-status" id="salesReportStatus{{ $sales_report->id }}" width="5%">
                                                        <small class="badge badge-pill badge-{{ $salesStatus[$sales_report->status][1] }}" >
                                                            {{ $salesStatus[$sales_report->status][0] }}
                                                        </small>
                                                    </td>

                                                    <td>
                                                        {{-- <div class="btn-group" role="group">
                                                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary btn-sm text-right dropdown-toggle" data-toggle="dropdown" >
                                                                Status
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @for($i = 0; $i < count($salesStatus); $i++)
                                                                <button class="btn btn-info dropdown-item sales-report-status-btn" data-sales-report-id="{{ $sales_report->id }}" data-status="{{ $i }}" href="">{{ $salesStatus[$i][0] }}</button>
                                                                @endfor
                                                            </div>
                                                        </div> --}}

                                                        @php
                                                            $payment_report = $sales_report->payment_reports()->latest()->first();
                                                            
                                                            if ($payment_report) {
                                                                $due_amount = $payment_report->due_amount;
                                                            }else{
                                                                $due_amount = $sales_report->total_payment_to_vendor;
                                                            }
                                                        @endphp
                                                        @if($due_amount > 0)
                                                            <a href="#paymentModal" class="btn btn-sm btn-success pull-right payment-btn" data-toggle="modal" id="payment{{ $sales_report->id }}" data-remaining-payment="{{ $due_amount }}" data-sales-report-id="{{ $sales_report->id }}"> Pay</a>
                                                        @endif
                                                        {{-- <a href="{{ route('admin.sales-reports.edit',['id' => base64_encode($sales_report->id)]) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa fa-edit"></i></a> --}}

                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot style="border-top: 5px solid;">
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td class="text-right"><strong>$<span id="totalSalesSum"></span></strong></td>
                                                    <td class="text-right"><strong>$<span id="returnRefundSum"></span></strong></td>
                                                    <td class="text-right"><strong>$<span id="totalNetSalesSum"></span></strong></td>
                                                    <td class="text-right"><strong>$<span id="commissionSum"></span></strong></td>
                                                    <td class="text-right"><strong>$<span id="paymentToVendorSum"></span></strong></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
                                            @else
                                                <tr>
                                                    <td class="text-center" colspan="9">
                                                        <p>No Sales Report Found</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay to Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.sales-reports.pay-amount') }}" method="POST">
                    @csrf
                    <input type="hidden" id="salesReportId" name="sales_report_id" required>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Due Amount : <strong>$<span id="dueAmount"></span></strong></p>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Amount($)</span>
                                    </div>
                                    <input id="paidAmount" class="form-control decimal-input" type="text" name="paid_amount" placeholder="Enter Paying Amount" required>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <div class="modal-footer ">
                        {{-- <button type="button" class="btn btn-round btn-outline-danger pull-left" data-dismiss="modal"> Cancel</button> --}}
                        <button type="submit" class="btn btn-round btn-success"><i class="fa fa-money"></i> Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Delete Product.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-white">
                    <p>Are you Sure?!</p>
                    <span>All the images and associated details will be deleted.</span>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-round btn-default" data-dismiss="modal">Close</button>
                    <a href="" class="btn btn-round btn-primary">Delete</a>
                </div>
            </div>
        </div>
    </div>

    

</div>


@endsection
@section('script')

<script>
    $(".payment-btn").click(function(){
        var remaining_payment = $(this).data('remaining-payment');
        var sales_report_id = $(this).data('sales-report-id');
        $("#dueAmount").html(remaining_payment);
        $("#paidAmount").val(remaining_payment);
        $("#salesReportId").val(sales_report_id);
    });



    $(document).ready(function() {

        $(".decimal-input").keypress(function(event){
            return isDecimalNumber(event, this);
        });

        $('#datepicker').datepicker({
            format: "yyyy-mm",
            endDate: "-1m",
            startView: 1,
            minViewMode: 1,
            maxViewMode: 2,
            clearBtn: true
        });

        $('.sales-table').DataTable({
            "ordering": false,
            "paging": false,
            "searching": false,
            dom: 'Bfrtip',
            buttons: [ 'csv', 'excel', 'pdf', 'print']
        });

        $('#selectVendor').select2({
            width: '100%',
            placeholder: 'Select One Product',
            language: {
                noResults: function() {
                    return 'No Match Found';
                },
            },
            escapeMarkup: function(markup) {
                return markup;
            },
        });

    });

</script>

<script type="text/javascript">

    $(document).ready(function(){

        
        var vendor_id = $("#selectVendor").val();

        // call_ajax_function(vendor_id);

        $('#selectVendor').change(function(){  
            var vendor_id = $(this).val();
        });  

        $(".do-calculation").keypress(function(event){
            return isDecimalNumber(event, this);
        });

        $(".do-calculation").keyup(function(){
            do_calculation();
        });

        do_calculation();

        var total_sales_sum = 0
        var return_refund_sum = 0
        var total_net_sales_sum = 0
        var commission_sum = 0
        var total_payment_to_vendor_sum = 0

        var sum_array = [
                            total_sales_sum, return_refund_sum, total_net_sales_sum, commission_sum, total_payment_to_vendor_sum
                        ];

        var id_array = [ 'totalSalesSum', 'returnRefundSum', 'totalNetSalesSum', 'commissionSum', 'paymentToVendorSum' ];

        var field_array = [ 'total_sales', 'return_refund', 'total_net_sales', 'commission', 'total_payment_to_vendor' ];

        for (var i = 0; i < field_array.length; i++) {

            $('.'+field_array[i]).each(function(){
                // console.log($(this).text());
                sum_array[i] += parseFloat($(this).text());
            });

            $('#'+id_array[i]).html(sum_array[i].toFixed(2));
        }

    });

    function do_calculation() {
        var total_sales = $("#totalSalesInput").val();
        
        if (total_sales == '') {
            total_sales = 0;
        }

        var return_refund = $("#returnRefundInput").val();
        
        if (return_refund == '') {
            return_refund = 0;
        }

        var total_net_sales = parseFloat(total_sales) - parseFloat(return_refund);
        total_net_sales = total_net_sales.toFixed(2)
        // console.log(total_net_sales);
        var commission = (0.1 * total_net_sales).toFixed(2);
        var payment_to_vendor = (total_net_sales - commission).toFixed(2);

        $("#totalNetSalesInput").val(total_net_sales);
        $("#commissionInput").val(commission);
        $("#paymentToVendorInput").val(payment_to_vendor);
    }

    function isDecimalNumber(evt, element){ 

        var charCode = (evt.which) ? evt.which : event.keyCode 
        
        if  ((charCode != 46 || ($(element).val().match(/\./g) || []).length > 0) && (charCode < 48 || charCode > 57))
            return false; 
        return true; 
    }

    $(".sales-report-status-btn").click(function(){
        var status = $(this).data('status');
        var sales_report_id = $(this).data('sales-report-id');

        $.ajax({
            url : "{{ URL::route('admin.sales-reports.change-sales-report-status') }}",
            type : "POST",
            data :{ '_token': '{{ csrf_token() }}',
                    id: sales_report_id,
                    status: status
                },
            beforeSend: function(){                

            },
            success : function(response)
            {
                console.log("response "+ response);
                var obj = jQuery.parseJSON( response);

                if (obj.status == 'success') {
                    
                    
                    $('#salesReportStatus'+sales_report_id).load(document.URL + ' #salesReportStatus'+sales_report_id+'>*');

                    toastr['success']('Status Updated');
                    

                }else {

                    toastr['error']('Something went wrong!');
                    

                };
            }
        });
    });
</script>

{{-- ===========================DATE FILTER =========================================== --}}
<!-- Include Required Prerequisites -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script type="text/javascript">
        $(function () {
            let dateInterval = getQueryParameter('date_filter');
            let start = moment().subtract(6, 'days');
            let end = moment();
            if (dateInterval) {
                dateInterval = dateInterval.split(' - ');
                start = dateInterval[0];
                end = dateInterval[1];
            }
            $('#date_filter').daterangepicker({
                "showDropdowns": true,
                "showWeekNumbers": false,
                "alwaysShowCalendars": false,
                startDate: start,
                endDate: end,
                locale: {
                    format: 'YYYY-MM-DD',
                    firstDay: 1,
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                    'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
                }
            });
        });
        function getQueryParameter(name) {
            const url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    </script>



@endsection
