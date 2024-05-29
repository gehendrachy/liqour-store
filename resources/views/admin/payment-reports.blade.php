@extends('admin/layouts.header-sidebar')
@section('title', 'Payment Reports')
@section('content')


<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-8 col-sm-12">

                <h2>Payment Reports</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard')  }}"><i class="icon-speedometer"></i> Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.payment-reports.list') }}"><i class="fa fa-clipboard"></i> Payment Reports</a>
                        </li>

                        @if($id != 0)
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($payment_report->vendor->vendor_details) ? $payment_report->vendor->vendor_details->store_name : $payment_report->vendor->name }}</li>
                        @endif
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
                $paymentStatus = array('0' => ['Unpaid', 'danger'],
                                     '1' => ['Paid', 'success'],
                                     '2' => ['Mix', 'primary']
                                    );
            @endphp
            <ul class="nav nav-tabs">
                @if($id == 0)
                    <li class="nav-item">
                        <a class="nav-link show active" data-toggle="tab" href="#PaymentReports">Payment Reports</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addPaymentReport">{{ $id == 0 ? 'Add Payment Report' : 'Update Payment Report' }}</a>
                </li>
            </ul>
            <div class="tab-content mt-0">
                @if($id == 0)
                <div class="tab-pane show active" id="PaymentReports">
                    <div class="card border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="title mb-0">All {{ isset($vendor) ? (isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name) . '- Payment Report' : 'Payment Reports' }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div style="width: 100%;" class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-info btn-sm text-right dropdown-toggle" data-toggle="dropdown" >
                                            Filter By Vendor
                                        </button>
                                        <div class="dropdown-menu" style="width: 100%;">
                                            @foreach($vendors as $key => $vendor)
                                                <a href="{{ route('admin.payment-reports.list',['vendor' => $vendor->slug]) }}{{ isset($_GET['date_filter']) ? '&date_filter='.$_GET['date_filter'] : '' }}" class="btn btn-info dropdown-item" >{{ $vendor->store_name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <form action="{{ url()->full() }}" method="get">
                                        @if(isset($_GET['vendor']))
                                            <input type="hidden" name="vendor" value="{{ request()->get('vendor') }}">
                                        @endif
                                        <div class="row">
                                            <div class="col-md-9 text-right">

                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="date_filter" id="date_filter"/>

                                                    <div class="input-group-append">
                                                        <input type="submit" name="filter_submit" class="btn btn-sm btn-success" value="Filter" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>

                                @if(request()->get('date_filter') || request()->get('vendor'))
                                <div class="col-md-2">
                                    <a href="{{ route('admin.payment-reports.list') }}" class="btn btn-sm btn-outline-danger"><i class="fa fa-times"></i> Clear Filter</a>
                                </div>
                                @endif
                            </div>
                            <hr>
                                
                            <div class="row">
                                <div class="col-12">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover table-custom spacing5 dataTable payment-table">
                                            <thead>
                                                <tr style="font-size: 12px; color: brown;">
                                                    <th><strong>Payment ID</strong></th>
                                                    <th><strong>Report Date</strong></th>
                                                    <th><strong>Vendor</strong></th>
                                                    <th class="text-right"><strong>Total Amount</strong></th>
                                                    <th class="text-right"><strong>Return/Refund</strong></th>
                                                    <th class="text-right"><strong>Adjustment</strong></th>
                                                    <th class="text-right"><strong>Commission</strong></th>
                                                    <th class="text-right"><strong>Net Amount</strong></th>
                                                    <th class="text-right"><strong>Paid Amount</strong></th>
                                                    <th class="text-right"><strong>Due</strong></th>
                                                    <th><strong>Paid Date</strong></th>
                                                    {{-- <th class="text-center">Paid By</th> --}}
                                                </tr>
                                            </thead>
                                            @if($payment_reports->count() > 0)
                                            <tbody id="product_variations_field">
                                                
                                                
                                    
                                                @foreach($payment_reports as $key => $payment_report)
                                                <tr>
                                                    <td style="font-size: 12px;">{{ $payment_report->payment_id }}</td>
                                                    <td style="font-size: 12px;">{{ date('M Y',strtotime($payment_report->sales_report->report_date)) }}</td>
                                                    <td style="font-size: 10px;">
                                                        <strong>{{ $payment_report->vendor->vendor_details->store_name }}</strong><br>
                                                        @php
                                                            $words = explode(" ", $payment_report->vendor->vendor_details->store_name);
                                                            $acronym = "";

                                                            foreach ($words as $w) {
                                                                $acronym .= $w[0];
                                                            }
                                                        @endphp
                                                        ({{ $acronym. (2021+ $payment_report->vendor_id) }})
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="total_amount">{{ number_format($payment_report->total_amount, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="return_refund">{{ number_format($payment_report->return_refund, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="adjustment">{{ number_format($payment_report->adjustment, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="commission">{{ number_format($payment_report->commission, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="net_amount">{{ number_format($payment_report->net_amount, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="paid_amount">{{ number_format($payment_report->paid_amount, 2, '.', '') }}</span>
                                                    </td>

                                                    <td class="text-right">
                                                        $<span class="due_amount">{{ number_format($payment_report->due_amount, 2, '.', '') }}</span>
                                                    </td>

                                                    <td style="font-size: 10px;">{{ date('Y-m-d H:i:s', strtotime($payment_report->created_at)) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <th colspan="3"></th>
                                                    <th class="text-right" >$<span id="totalAmountSum"></span></th>
                                                    <th class="text-right" >$<span id="returnRefundSum"></span></th>
                                                    <th class="text-right" >$<span id="adjustmentSum"></span></th>
                                                    <th class="text-right" >$<span id="commissionSum"></span></th>
                                                    <th class="text-right" >$<span id="netAmountSum"></span></th>
                                                    <th class="text-right" >$<span id="paidAmountSum"></span></th>
                                                    <th class="text-right" >$<span id="dueAmountSum"></span></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                            @else
                                            <tr>
                                                <td class="text-center" colspan="11">
                                                    <p>No Payment Report Found</p>
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
                @endif
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addPaymentReport">
                    <div class="card border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="title mb-0">{{ isset($vendor) ? (isset($vendor->vendor_details) ? $vendor->vendor_details->store_name : $vendor->name) . '- Payment Report' : 'Add Payment Reports' }}</h6>
                        </div>
                        <form id="parsley-form" method="post" action="{{ $id == 0 ? route('admin.payment-reports.create') : route('admin.payment-reports.update') }}" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ isset($payment_report) ? $payment_report->id : '' }}">
                                @csrf
                            <div class="card-body">                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            
                                            <select name="vendor_id" class="custom-select" required id="selectVendor">

                                                <option selected disabled>Choose Vendor...</option>
                                                
                                                @foreach($vendors as $key => $vendor)
                                                    <option {{ $id != 0 && $payment_report->vendor_id == $vendor->user_id ? 'selected' : (old('vendor_id') == $vendor->user_id ? 'selected' : '') }} value="{{ $vendor->user_id }}">{{ $vendor->store_name }}</option>
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6 clearfix">

                                    </div>


                                    <div class="col-md-12">
                                        <style>
                                            .light_version .table tr td, .light_version .table tr th {
                                                border-color: #00000038 !important;
                                                background: #fff !important;
                                            }
                                        </style>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th><strong>Total Amount</strong></th>
                                                        <th><strong>Return/Refund</strong></th>
                                                        <th><strong>Adjustment</strong></th>
                                                        <th><strong>Commission (10%)</strong></th>
                                                        <th><strong>Net Amount</strong></th>
                                                        <th><strong>Paid Amount</strong></th>
                                                        <th><strong>Due</strong></th>
                                                        
                                                    </tr>
                                                </thead>
                                                
                                                <tbody id="paymentTable">
                                                    <!-- Payment Input Fields Here by Ajax -->
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    
                                    <div class="clearfix"></div>
                                </div>
                                
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($id != 0)
                                            <a href="{{ route('admin.payment-reports.list') }}" class="btn btn-outline-danger">CANCEL</a>

                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                        @else
                                            <button type="submit" style="float: right;" class="btn btn-outline-success"> SAVE</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


        <div class="clearfix"></div>
        <div class="col-md-12">

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

    $(document).ready(function() {

        $('.payment-table').DataTable({
            "ordering": false,
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

        
        var total_amount_sum = 0;
        var return_refund_sum = 0;
        var adjustment_sum = 0;
        var commission_sum = 0;
        var net_amount_sum = 0;
        var paid_amount_sum = 0;
        var due_amount_sum = 0;

        var sum_array = [
                            total_amount_sum, return_refund_sum, adjustment_sum, commission_sum, net_amount_sum, paid_amount_sum, due_amount_sum
                        ];

        var id_array = [
                            'totalAmountSum', 'returnRefundSum', 'adjustmentSum', 'commissionSum', 'netAmountSum', 'paidAmountSum', 'dueAmountSum'
                        ]
        var field_array = [ 'total_amount', 'return_refund', 'adjustment', 'commission', 'net_amount', 'paid_amount', 'due_amount'];

        for (var i = 0; i < field_array.length; i++) {

            $('.'+field_array[i]).each(function(){
                // console.log($(this).text());
                sum_array[i] += parseFloat($(this).text());
            });

            $('#'+id_array[i]).html(sum_array[i].toFixed(2));
        }
        
        // $('#grandTotalID').html(sum);

    });

</script>

<script type="text/javascript">

    $(document).ready(function(){
        
        var vendor_id = $("#selectVendor").val();

        call_ajax_function(vendor_id);

        $('#selectVendor').change(function(){  
            $('#modal-loader').show();
            var vendor_id = $(this).val();
            call_ajax_function(vendor_id);
        });  

        

    });

    function do_calculation() {
        var total_amount = $("#totalAmountInput").val();
        
        if (total_amount == '') {
            total_amount = 0;
        }

        var return_refund = $("#returnRefundInput").val();
        // console.log(total_amount);
        if (return_refund == '') {
            return_refund = 0;
        }

        var adjustment = parseFloat(total_amount) - parseFloat(return_refund);
        adjustment = adjustment.toFixed(2)
        // console.log(adjustment);
        var commission = (0.1 * adjustment).toFixed(2);

        var net_amount = (adjustment - commission).toFixed(2);

        $("#adjustmentInput").val(adjustment);
        $("#netAmountInput").val(net_amount)
        $("#commissionInput").val(commission);
        $("#netAmountInput").val(net_amount);

        var paid_amount = $("#paidAmountInput").val();
        
        if (paid_amount == '') {
            paid_amount = 0;
        }

        var old_due_amount = $("#oldDueAmountInput").val();
        // console.log(old_due_amount);
        if (old_due_amount == '') {
            old_due_amount = 0;
        }

        var due_amount = (parseFloat(net_amount) + parseFloat(old_due_amount) - parseFloat(paid_amount)).toFixed(2);

        $("#dueAmountInput").val(due_amount);

    }

    function isDecimalNumber(evt, element){ 

        var charCode = (evt.which) ? evt.which : event.keyCode 
        
        if  ((charCode != 46 || ($(element).val().match(/\./g) || []).length > 0) && (charCode < 48 || charCode > 57))
            return false; 
        return true; 
    }

    $(".payment-report-status-btn").click(function(){
        var status = $(this).data('status');
        var payment_report_id = $(this).data('payment-report-id');

        $.ajax({
            url : "{{ URL::route('admin.payment-reports.change-payment-report-status') }}",
            type : "POST",
            data :{ '_token': '{{ csrf_token() }}',
                    id: payment_report_id,
                    status: status
                },
            beforeSend: function(){                

            },
            success : function(response)
            {
                console.log("response "+ response);
                var obj = jQuery.parseJSON( response);

                if (obj.status == 'success') {
                    
                    
                    $('#paymentReportStatus'+payment_report_id).load(document.URL + ' #paymentReportStatus'+payment_report_id+'>*');

                    toastr['success']('Status Updated');
                    

                }else {

                    toastr['error']('Something went wrong!');
                    

                };
            }
        });
    });

    function call_ajax_function(vendor_id) {
        $.ajax({
            url : "{{ URL::route('admin.payment-reports.get-last-vendor-payment-report') }}",
            type : "POST",
            data : {
                '_token': '{{ csrf_token() }}',
                vendor_id: vendor_id
            },
            cache : false,
            beforeSend : function (){
                
            },
            complete : function($response, $status){

                if ($status != "error" && $status != "timeout") {

                    var obj = jQuery.parseJSON($response.responseText);

                    $('#paymentTable').html(obj.tableResponse);

                    $(".do-calculation").keypress(function(event){
                        return isDecimalNumber(event, this);
                    });

                    $(".do-calculation").keyup(function(){
                        do_calculation();
                    });

                    do_calculation();

                    $(".decimal-input").keypress(function(event){
                        return isDecimalNumber(event, this);
                    });

                    $(".number-input").keypress(function(event){
                        return isNumberKey(event, this);
                    });
                    $('#modal-loader').hide();
                }
            },
            error : function ($responseObj){
                alert("Something went wrong while processing your request.\n\nError => "
                    + $responseObj.responseText);
            }
        });
    }
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
            let start = moment().startOf('isoWeek');
            let end = moment().endOf('isoWeek');
            if (dateInterval) {
                dateInterval = dateInterval.split(' - ');
                start = dateInterval[0];
                end = dateInterval[1];
            }
            $('#date_filter').daterangepicker({
                "showDropdowns": true,
                "showWeekNumbers": true,
                "alwaysShowCalendars": true,
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
                    'All time': [moment("01-01-2019", "DD-MM-YYYY"), moment().endOf('month')],
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
