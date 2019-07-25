@extends('layouts.master')

@section('title', 'Cash Receipt Payment | Create')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Cash Receipt (Payment)</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="#">Transaction</a>
                </li>
                <li>
                    <a href="#">Cash Receipt</a>
                </li>
                <li class="active">
                    <strong><a href="{!! route('cash-receipt.index') !!}">Cash Receipt Payment</a></strong>
                </li>
            </ol>
        </div>
    </div>

    <!-- animated fadeInRight -->
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                @if(Session::has('message'))
                    <br/>
                    <div class="col-md-12">
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span><em> {!! session('message') !!}</em>
                      </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#regular-client-tab" data-type="Regular">Regular Client</a></li>
                        <li class=""><a data-toggle="tab" href="#walk-in-tab" data-type="Walk-In">Walk-In Client</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="regular-client-tab" class="tab-pane active">
                            <div class="panel-body">
                                <div class="row">
                                    {{Form::open(array('route'=>array('cash-receipt.store'), 'class' => 'form-horizontal', 'id' => 'regular-payment-form'))}}

                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label">Client:</label>
                                                    <div class="col-lg-6">
                                                        <select class="form-control select2" id="regular-client-select" name="client_id" required autofocus>
                                                                <option value="">Select Client</option>
                                                            @foreach ($regurlarClients as $c)
                                                                <option value="{{$c->id}}">{{$c->profile->full_name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('client_id'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('client_id') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-lg-12" id="rc-list-of-bill">
                                                <div class="ibox float-e-margins">
                                                    <div class="ibox-title">
                                                        <h4>
                                                            Unpaid Bills
                                                        </h4>

                                                        <h4 class="text-right">
                                                            <span>TOTAL:</span>
                                                            <span id='total-amount'>0.00</span>
                                                        </h4>
                                                    </div>
                                                    <div class="ibox-content">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <table class="table table-striped table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th></th>
                                                                            <th>Bill</th>
                                                                            <th>Month</th>
                                                                            <th class="text-right">Tax</th>
                                                                            <th class="text-right">Bill Total</th>
                                                                            <th class="text-right">Op. Fund Total</th>
                                                                            <th class="text-right">Total Amt. Paid</th>
                                                                            <th class="text-right">Balance</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="rc-fee-table-body">
                                                                        <td id="rc-no-data" colspan="8" class="text-center">
                                                                            No Bills data. Please select client!
                                                                        </td>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="type" type="hidden" value="billing">
                                                    <input name="billing_ids" type="hidden" id="billing-ids">
                                                    <input name="amount_due" type="hidden" id="billing-amount-due" value="0">
                                                </div>
                                                <div class="form-group"></div><br/>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" >Date:</label>
                                                    <div class="col-lg-8">
                                                        <input name="payment_date" type="date" class="form-control" id="rc-payment-date" value="{{$date}}" required>
                                                        @if ($errors->has('payment_date'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('payment_date') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" >OR #:</label>
                                                    <div class="col-lg-8">
                                                        <input name="billing_cash_receipt_no" type="text" class="form-control text-right" id="rc-or-no">
                                                        @if ($errors->has('billing_cash_receipt_no'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('billing_cash_receipt_no') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Amount Paid:</label>
                                                    <div class="col-lg-8">
                                                        <input name="billing_amount_paid" type="number" step="any" class="form-control text-right" id="rc-amount-paid" min="0" required>
                                                        @if ($errors->has('billing_amount_paid'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('billing_amount_paid') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-lg-4 control-label">Act Type:</label>
                                                    <div class="col-lg-8">
                                                        <select name="billing_account_id" id="rc-account-id" class="form-control" required>
                                                            @foreach ($cashTypeACcount as $account)
                                                                <option value="{{$account->id}}">{{$account->title}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('billing_account_id'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('billing_account_id') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12" id="button-wrapper">
                                                <button class="btn btn-md btn-success pull-right" id="rc-payment-button" disabled>Receive Payment</button>
                                                <!-- {{Form::submit('Receive Payment', array('id'=>'rc-payment-button', 'class'=>'btn btn-md btn-success pull-right'))}} -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="col-lg-12">
                                        <button type="button" class="btn btn-md btn-info pull-right" id="search-payment">
                                            <i class="fa fa-search"></i>
                                            Search Payments
                                        </button>
                                    </div> -->

                                    {{Form::close()}}
                                </div>
                            </div>
                        </div>
                        <div id="walk-in-tab" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    {{Form::open(array('route'=>array('cash-receipt.store'), 'class' => 'form-horizontal', 'id' => 'walk-in-payment-form'))}}
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="col-lg-1 control-label">Client:</label>
                                                        <div class="col-lg-6">
                                                            <select class="form-control select2" id="walk-in-client-select" name="client_id" required autofocus>
                                                                    <option value="">Select Client</option>
                                                                @foreach ($walkInClients as $c)
                                                                    <option value="{{$c->id}}">{{$c->profile->full_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('client_id'))
                                                                <span class="help-block m-b-none">
                                                                    <strong>{{ $errors->first('client_id') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="row">
                                                <div class="col-lg-12" id="walk-in-payment-info">
                                                    <div class="ibox float-e-margins">
                                                        <div class="ibox-title">
                                                            <h4>Walk-in Charge Information</h4>
                                                        </div>
                                                        <div class="ibox-content">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <table class="table table-striped table-bordered table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Description</th>
                                                                                <th>Amount</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="fee-table-body">
                                                                            <td id="walk-in-no-data" colspan="2" class="text-center">
                                                                                No charge data. Please select client!
                                                                            </td>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input name="type" type="hidden" value="walk-in">
                                                    </div>
                                                    <div class="form-group"></div><br/>
                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label" >Date:</label>
                                                        <div class="col-lg-8">
                                                            <input name="payment_date" type="date" class="form-control" id="walk-in-payment-date" value="{{$date}}">
                                                            @if ($errors->has('payment_date'))
                                                                <span class="help-block m-b-none">
                                                                    <strong>{{ $errors->first('payment_date') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label" >OR #:</label>
                                                        <div class="col-lg-8">
                                                            <input name="walk_in_cash_receipt_no" type="text" class="form-control text-right" id="walk-in-or-no">
                                                            @if ($errors->has('walk_in_cash_receipt_no'))
                                                                <span class="help-block m-b-none">
                                                                    <strong>{{ $errors->first('walk_in_cash_receipt_no') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-4 control-label">Amount Paid:</label>
                                                        <div class="col-lg-8">
                                                            <input name="walk_in_amount_paid" type="number" class="form-control text-right" id="walk-in-amount-paid">
                                                            @if ($errors->has('walk_in_amount_paid'))
                                                                <span class="help-block m-b-none">
                                                                    <strong>{{ $errors->first('walk_in_amount_paid') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group" style="display: none;">
                                                        <label class="col-lg-4 control-label">Act Type:</label>
                                                        <div class="col-lg-8">
                                                            <select name="walk_in_account_id" id="walk-in-account-id" class="form-control">
                                                                @foreach ($cashTypeACcount as $account)
                                                                    <option value="{{$account->id}}">{{$account->title}}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('account_id'))
                                                                <span class="help-block m-b-none">
                                                                    <strong>{{ $errors->first('account_id') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="row">
                                                <div class="col-lg-12" id="button-wrapper">
                                                    {{Form::submit('Receive Payment', array('class'=>'btn btn-md btn-success pull-right'))}}
                                                </div>
                                            </div>
                                        </div>
                                    {{Form::close()}}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            
            <!-- Modal -->
            <div id="view-pdf" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">trust-fund Charge Slip Viewer</h4>
                  </div>
                  <div class="modal-body">
                    <embed id="pdf-embed" width="100%" height="100%">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/select2/select2.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
{!! Html::style('css/plugins/iCheck/custom.css') !!}
<style type="text/css">
    #txt-total-charge {
        font-size: 50px;
        font-weight: bold;
    }
    #txt-total-expense {
        font-size: 30px;
        font-weight: bold;
    }
    .modal {
      margin: 0 auto; 
    }
    .modal-dialog,
    .modal-content {
        /* 95% of window height */
        height: 95%;
        width: 98%;
    }
    .modal-body {
        /* 100% = dialog height, 120px = header + footer */
        height: calc(100% - 120px);
        padding: 0!important;
    }
</style>
@endsection

@section('scripts')
    {!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('js/jquery.inputmask.bundle.js') !!}

    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/jquery.form.min.js') !!}
    {!! Html::script('js/image-uploader.js') !!}
    <script>
        function ReplaceNumberWithCommas(yourNumber) {
            //Seperates the components of the number
            var n= yourNumber.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //Combines the two sections
            return n.join(".");
        }
        $(document).ready(function(){
            var $walkInPaymentForm = $('#walk-in-payment-form'),
                $regularPaymentForm = $('#regular-payment-form'),
                $regularClientSelect = $('#regular-client-select'),
                // $regularClientCaseSelect = $('#regular-case-select'),
                $regularClientPaymentTable = $('#rc-list-of-bill'),
                $regularClientPaymentTableInfo = $('#rc-fee-table-body'),
                $regularClientPaymentTableNodata = $('#rc-no-data'),

                $rcAccountId = $("#rc-account-id"),
                $rcAmountPaid = $("#rc-amount-paid"),
                $rcOrNo = $("#rc-or-no"),
                $rcPaymentDate = $("#rc-payment-date"),

                $walkInClientSelect = $("#walk-in-client-select"),
                $walkInClient = $("#walk-in-client"),
                $walkInPaymentinfo = $("#walk-in-payment-info"),
                $walkInFeeTableBody = $("#fee-table-body"),
                $walkInAccountId = $("#walk-in-account-id"),
                $walkInAmountPaid = $("#walk-in-amount-paid"),
                $walkInOrNo = $("#walk-in-or-no"),
                $walkInPaymentDate = $("#walk-in-payment-date"),


                $feeCategories = $("#type-of-payment-select"),
                $btnSearchFee = $("#search-payment"),
                $trustFundInfo = $("#trust-fund-payment-info"),
                $trustFundAccountId = $("#trust-fund-account-id"),
                $trustFundAmountPaid = $("#trust-fund-amount-paid"),
                $trustFundCashReceipt = $("#trust-fund-or-no"),


                $amountToPay = 0;

            $feeCategories.select2();


            $(document).on('ifToggled', '.bill', function(){
                var $billingIds = $('.bill:checkbox:checked').map(function() {return this.value}).get().join(',');
                $("#billing-ids").val($billingIds);

                $amountToPay = 0;
                $('.bill:checkbox:checked').map(function() {
                    $amountToPay += parseFloat($(this).data('amount'));
                }).get();
                $rcAmountPaid.attr('max', $amountToPay);
                $("#billing-amount-due").val($amountToPay);
                $("#total-amount").text(ReplaceNumberWithCommas($amountToPay.toFixed(2)));

                if ($amountToPay > 0) {
                    $rcAmountPaid.attr('max', $amountToPay);
                    $rcAmountPaid.val($amountToPay);
                    $("#rc-payment-button").attr('disabled', false);
                } else {
                    $("#rc-payment-button").attr('disabled', true);
                }

                // var checkbox = $(this).closest('table').find('.bill').length;
                // var checked = $(this).closest('table').find('.bill:checked').length;

                // console.log($(this).data(), 'test');

                // if(checkbox === checked){
                //     $(this).closest('table').find('.check-all').iCheck('check');
                // }else{
                //     $(this).closest('table').find('.check-all').iCheck('uncheck');
                // }
                // delay(function(){
                //     billBox.empty();
                //     srList();
                // }, 500 );
            });

            $rcAmountPaid.on('keyup', function(){
                var val = parseFloat($rcAmountPaid.val()),
                min = parseFloat($rcAmountPaid.attr('min')),
                max = parseFloat($rcAmountPaid.attr('max'));

                if (val > max) {
                    $rcAmountPaid.val(max);
                } else if (val < min) {
                    $rcAmountPaid.val(min);
                }
            });

            // {
            //   ajax: {
            //     url: '{!! route("clients-list-select2") !!}',
            //     dataType: 'json'
            //     // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            //   },
            //   placeholder: 'Search Regular Client',
            //   minimumInputLength: 2
            // }
            $regularClientSelect.select2().on('select2:select', function (e) {
                var data = e.params.data;
                
                // get latest billing
                $.get('{!! route("unpaid-bills") !!}?id='+data.id, function (result) {
                    // $amountToPay = 0;
                    var bills = result.bills;
                    var $html = "";                    

                    if (bills.length){
                        $regularClientPaymentTableNodata.hide();
                    } else {
                        $regularClientPaymentTableNodata.show();
                        swal({
                            title: 'No Bill!',
                            text: 'No bill for this client.',
                            type: 'info',
                        });
                    }

                    // if (bill.tax_amount) {
                    //     $rcOrNo.attr('required', true);
                    // } else {
                    //     $rcOrNo.attr('required', false);
                    // }

                    $regularClientPaymentTableInfo.html(''); // clear firs the table
                    
                    $.each(bills, function(index, bill) {
                        var taxClass = (bill.percentage_tax) ? 'has-tax':'has-no-tax';
                        var $billOverAllAmountToPay = (bill.paid) ? 
                            (parseFloat(bill.balance) + parseFloat(bill.of_amount_balance)) : 
                            (parseFloat(bill.total) + parseFloat(bill.of_amount));

                        var $billOverAllAmount = parseFloat(bill.total) + parseFloat(bill.of_amount);

                        // console.log($billOverAllAmount);

                        var $totalAmountPaid = $billOverAllAmount - $billOverAllAmountToPay;

                        // (bill.paid) ? 
                        //     (parseFloat(bill.total) - parseFloat(bill.balance)) + parseFloat(bill.of_amount_balance)
                        //     : 0;

                        // Operational Fund
                        // $totalAmountPaid += (bill.paid) ? ($billOverAllAmount - (parseFloat(bill.balance) + parseFloat(bill.of_amount_balance))) : 0;

                        $html += "<tr><td> <div class='i-checks'> <input type='checkbox' class='bill "+taxClass+"' value='"+bill.id+"' data-amount='"+$billOverAllAmountToPay+"'> </div> </td>";
                        $html += "<td ><strong>" + bill.bill_number + "</strong></td>";
                        $html += "<td ><strong>" + bill.bill_month + "</strong></td>";
                        $html += "<td class='text-right'><strong>" + ((bill.percentage_tax)? bill.percentage_tax + '%': '-') + "</strong></td>";
                        $html += "<td class='text-right'><strong>" + ReplaceNumberWithCommas(parseFloat(bill.total).toFixed(2)) + "</strong></td>";
                        $html += "<td class='text-right'><strong>" + ReplaceNumberWithCommas(parseFloat(bill.of_amount).toFixed(2)) + "</strong></td>";
                        
                        $html += "<td class='text-right'><strong>" + ReplaceNumberWithCommas($totalAmountPaid.toFixed(2)) + "</strong></td>";
                        $html += "<td class='text-right'><strong>" + ReplaceNumberWithCommas($billOverAllAmountToPay.toFixed(2)) + "</strong></td></tr>";

                        $regularClientPaymentTableInfo.append($html);

                        $html="";
                    });


                    $('.i-checks').iCheck({
                        checkboxClass: 'icheckbox_square-green'
                    });

                });

            });

            var addRequiredAttr = function (walkIn, rc){
                $walkInAmountPaid.attr('required', walkIn);
                $walkInOrNo.attr('required', walkIn);
                $walkInAccountId.attr('required', walkIn);
                $walkInPaymentDate.attr('required', walkIn);

                // Regular Client
                $rcAccountId.attr('required', rc);
                $rcAmountPaid.attr('required', rc);
                $rcOrNo.attr('required', rc);
                $rcPaymentDate.attr('required', rc);
            };

            var hideAll = function () {
                addRequiredAttr(false, false);
                $walkInPaymentinfo.hide();
                $regularClientPaymentTable.hide();
                $trustFundInfo.hide();
            };

            $walkInPaymentForm.on('submit', function(event) {
                var feeCategory = $feeCategories.val();
                $isNotValid = false;

                if (parseFloat($walkInAmountPaid.val()) < parseFloat($amountToPay)) {
                    swal({
                        title: 'ERROR!',
                        text: 'Payment must be higher or equal to the amount due.',
                        type: 'danger',
                        showCancelButton: true,
                        // confirmButtonColor: '#DD6B55',
                        // confirmButtonText: 'Yes, delete it!',
                        // cancelButtonText: 'No, cancel pls!'
                    });
                    // alert("");
                    $isNotValid = true;
                }

                // switch(feeCategory) {
                //     case 'walk-in':
                        
                //         break;
                //     case 'trust-fund':
                //         // proceed
                //         break;
                    
                //     default:
                //         alert('Invalid Fee Category!');
                // }

                if ($isNotValid) event.preventDefault();
            });


            $regularPaymentForm.on('submit', function(event) {
                var $isNotValid = false;

                if (parseFloat($rcAmountPaid.val()) < parseFloat($amountToPay)) {
                    swal({
                        title: 'Warning!',
                        text: 'Payment is lower than the total due. Bills with remianing balance will reflect on the next billing',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Proceed',
                        cancelButtonText: 'Cancel'
                    },
                    function (isConfirm) {
                        if (!isConfirm) {
                            $isNotValid = true;
                        }

                        if ($isNotValid) event.preventDefault();
                    });
                }

            });

            // on Tab Select
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var type = $(e.target).data("type") // activated tab
                console.log(type, 'tab type');
                switch(type) {
                    case 'Regular':
                        addRequiredAttr(false, true);
                        break;

                    case 'Walk-In':
                        // {
                        //     ajax: {
                        //         url: '{!! route("clients-list-select2") !!}?client_type=w',
                        //         dataType: 'json'
                        //         // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                        //     },
                        //     placeholder: 'Search Regular Client',
                        //     minimumInputLength: 2
                        // }
                        $walkInClientSelect.select2().on('select2:select', function (e) {
                            var client = e.params.data;
                            
                            $.get("{!! route('client-payment-opt') !!}?client_id=" + client.id + "&fee_category=walk-in", function(data, status){
                                if (!data.info)  {
                                    swal({
                                        title: 'No Charge Slip!',
                                        text: 'No Charge Slip for this client.',
                                        type: 'info',
                                    });

                                    return;
                                }
                                var $html = "";
                                $amountToPay = data.info.total_charges;
                                addRequiredAttr(true, false);
                                $walkInClient.val(client.id);
                                $walkInFeeTableBody.html(''); // clear firs the table
                                $walkInPaymentinfo.show();
                                $walkInOrNo.focus();
                                $("#walk-in-id").remove();
                                $("#walk-in-amount-due").remove();
                                $walkInPaymentinfo.append('<input name="walk_in_charge_slip_id" type="hidden" id="walk-in-id" value="' + data.info.id + '">');
                                $walkInPaymentinfo.append('<input name="amount_due" type="hidden" id="walk-in-amount-due" value="' + $amountToPay + '">');
                                
                                $html = "<tr><td colspan='2'><strong> <a title='View Charge Slip' href='javascript:void(0)' id='view-charge-slip' data-id='" + data.info.id + "'> <i class='fa fa-eye'></i>" + data.info.charge_slip_no + "</strong> (<i>" + data.info.t_date + "</i>)</td></tr>";
                                $.each(data.fees, function(index, feeDetail) {
                                    $html += "<tr><td>" + feeDetail.fee.display_name + "</td>";
                                    $html += "<td class='text-right'>" + feeDetail.amount + "</td></tr>";

                                    $walkInFeeTableBody.append($html);

                                    $html = "";
                                });
                                
                                $html = "<tr><td class='text-right'><strong>TOTAL:</strong></td>";
                                $html += "<td class='text-right'><strong>" + data.info.format_total_charges + "</strong></td></tr>";

                                $walkInFeeTableBody.append($html);
                            });
                        });
                        break;
                    
                    default:
                        alert('Invalid Fee Category!');
                }
            });

            $(document).on('click', '#view-charge-slip', function (e) {              
                $('#view-pdf').modal('show').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(".modal-content").height());
                });
                var url = "{{ url('/') }}"
                $("#pdf-embed").attr('src', url + "/walk-in/charge-slip/" + $(this).data('id') + "/print");
            });

            $(document).on('focus', '.select2', function (e) {  
              if (e.originalEvent) {
                $(this).siblings('select').select2('open');    
              } 
            });
        });
    </script>
@endsection
