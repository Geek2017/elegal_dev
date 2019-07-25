@extends('layouts.master')

@section('title', 'Create Billing')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create Billing</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Create Billing</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" id="create-billing" class="btn btn-primary" disabled=""><i class="fa fa-print"></i> Create Billing & PDF File</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-3">
                <div class="ibox float-e-margins setter">
                    <div class="ibox-title">
                        <h5>Client's with active Contracts</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group" id="month-input">
                            <label>Month / Year Select</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="month-select" class="form-control month-select" value="{!! \Carbon\Carbon::now()->format('F Y') !!}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9" id="billing-content">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Billing Information</h5>
                    </div>
                    <div class="billing-mockup" id="billing-box">

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/plugins/summernote/summernote.min.js') !!}
    <script>
        $(document).ready(function(){
            var setter = $('.setter').find('.ibox-content');
            var billBox = $('#billing-box');

            getMonth();
            $('.input-group.date').datepicker({
                minViewMode: 1,
                keyboardNavigation: false,
                autoclose: true,
                todayHighlight: true,
                format: 'MM yyyy'
            }).on('hide',function(){
                getMonth();
                billBox.empty();
                setter.find('.form-group').slice(2).remove();
                setter.find('table').remove();
                $('#create-billing').prop("disabled", true);
            });

            $(document).on('ifToggled', '.sr_no', function(){
                var checkbox = $(this).closest('table').find('.sr_no').length;
                var checked = $(this).closest('table').find('.sr_no:checked').length;
                if(checkbox === checked){
                    $(this).closest('table').find('.check-all').iCheck('check');
                }else{
                    $(this).closest('table').find('.check-all').iCheck('uncheck');
                }
                delay(function(){
                    billBox.empty();
                    srList();
                }, 500 );
            });

            $(document).on('ifClicked', '.check-all', function(){
                var box = $(this).closest('table').find('.sr_no');
                if($(this).is(':checked')){
                    box.iCheck('uncheck');
                }else{
                    box.iCheck('check');
                }
            });

            $(document).on('change', '#client-select', function(){
                setter.find('table').remove();
                var month = moment();
                billBox.empty();
                $('#create-billing').prop("disabled", true);

                $.get('{!! route('billing-service-report') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('select[name="client"]').val()
                },function(data){
                   // console.log(data);
                    setter.find('.form-group').slice(2).remove();
                    if(data.length > 0){
                        setter.append('' +
                            '<table class="table table-striped">' +
                                '<thead>' +
                                '<tr>' +
                                    '<th style="width: 38px; vertical-align: middle">' +
                                        '<div class="i-checks">' +
                                            '<input type="checkbox" value="" class="check-all">' +
                                        '</div>' +
                                    '</th>' +
                                    '<th style="vertical-align: middle">Select all</th>' +
                                    '<th>' +
                                        '<div class="form-group">' +
                                            '<div class="input-group m-b">' +
                                                '<span class="input-group-addon">Percentage Tax</span> ' +
                                                '<input type="text" value="0" name="percentage-tax" id="percentage-tax" class="form-control numonly">' +
                                                '<span class="input-group-addon">%</span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</th>' +
                                '</tr>' +
                                '</thead>' +
                            '<tbody></tbody>' +
                            '</table>' +
                            '');
                        for(var a = 0; a < data.length; a++){
                            setter.find('tbody').append('' +
                                '<tr>' +
                                    '<td>' +
                                        '<div class="i-checks">' +
                                        '<input type="checkbox" name="sr_no" class="sr_no" value="'+ data[a].id +'">' +
                                        '</div>' +
                                    '</td>' +
                                    '<td colspan="2"><strong>'+ data[a].sr_number +'</strong> <small class="text-success">[ '+ moment(data[a].date).from(month, true) +' ]</small> <br><small class="text-info">'+ data[a].fee_detail.fee.display_name +'</small></td>' +
                                '</tr>' +
                            '');
                        }
                        $('.i-checks').iCheck({
                            checkboxClass: 'icheckbox_square-green'
                        });
                    }
                });
            });

            $(document).on('keyup','#percentage-tax', function(){
                var ids = $('.sr_no:checked').map(function(){
                    return parseInt($(this).val());
                }).toArray();
                if(ids.length > 0){
                    delay(function(){
                        billBox.empty();
                        srList();
                    }, 500 );
                }
            });

            var billSpecial;
            var billGeneral;
            var billExcess;
            $(document).on('click', '#create-billing', function(){

                var ids = $('.sr_no:checked').map(function(){
                    return parseInt($(this).val());
                }).toArray();

                $.post('{!! route('store-billing') !!}', {
                    _token: '{!! csrf_token() !!}',
                    client_id: setter.find('select[name="client"]').val(),
                    ids: ids,
                    percentage_tax: setter.find('input[name="percentage-tax"]').val()||0,
                    special: billSpecial,
                    general: billGeneral,
                    excess: billExcess,
//                    content: $('#billing-content').summernote('code')
                    content: $('#billing-content').html()
                }, function(data){
                    console.log(data);
                    if(data){
                        window.open('http://'+window.location.host+'/billings/print?client_id='+ data.client_id);
                        delay(function(){
                            window.location.replace('http://'+window.location.host+'/billing/'+ data.id);
                        }, 500 );
                    }
                });

            });

            function getMonth(){
                $.get('{!! route('get-month') !!}',{
                    month: $('.month-select').val()
                },function(data){
                    console.log(data);
                    setter.find('.form-group').slice(1).remove();
                    if(data.length > 0){
                        setter.append('' +
                            '<div class="form-group">' +
                            '<label>Client</label>' +
                            '<select name="client" class="form-control" id="client-select"></select>' +
                            '</div>' +
                            '');
                        setter.find('select[name="client"]').append('<option value="">Select Client</option>');
                        for(var a = 0; a < data.length; a++){
                            setter.find('select[name="client"]').append('<option value="'+ data[a].id +'">'+ data[a].profile.firstname +' '+ data[a].profile.lastname +'</option>');
                        }
                    }
                });
            }

            function srList(){
                var ids = $('.sr_no:checked').map(function(){
                    return parseInt($(this).val());
                }).toArray();
                console.log(ids);
                $.get('{!! route('fetch-service-report') !!}',{
                    ids: ids,
                    client_id: setter.find('select[name="client"]').val(),
                    month: $('.month-select').val()
                },function(data){
                    console.log(data);
                    $('#create-billing').prop("disabled", false);
                    if(data[0].length > 0){
                        billBox.append('' +
                            '<div class="panel panel-default detail">' +
                                '<div class="panel-heading text-center">' +
                                    '<strong>Detail of Service Report</strong>' +
                                '</div>' +
                                '<div class="panel-body">' +
                                    '<div class="row">' +
                                        '<div class="col-sm-12">' +
                                            '<ul class="detail-head">' +
                                            '<li>' +
                                            '<ul>' +
                                            '<li>No</li>' +
                                            '<li>Service Detail</li>' +
                                            '<li>Profl Fee</li>' +
                                            '</ul>' +
                                            '</li>' +
                                            '<li>' +
                                            '<ul>' +
                                            '<li>Chargeable Expenses</li>' +
                                            '<li>Description</li>' +
                                            '<li>Amount</li>' +
                                            '</ul>' +
                                            '</li>' +
                                            '</ul>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="hr-line-solid"></div>' +
                            '</div>' +
                        '');

                        var balance = new Array();
                        var percentageTax = setter.find('input[name="percentage-tax"]').val()||0;
                        var pfTax = '';
                        // make payments visible if available
                        var paymentReceived = new Array();
                        var subPaymentReceived = 0;
                        var adjustmentBalance = 0;
                        var subBalance = 0;

                        var specialRetainer = 0;
                        var fixedGeneralRetainer = 0;
                        var GRFeeDetailID = new Array();
                        var GRFeeDetailName = new Array();
                        var excessGeneralRetainer = 0;
                        var current = 0;
                        var paralegalCharge = 0;

                        var adjustmentCharges = 0;
                        var subTotal = 0;

                        var trustFund = parseFloat(data[4]);
                        var trustFundBalance = new Array();
                        var trustFundSubBalance = 0;
                        var chargeableExpense = 0;
                        // make payments visible if available
                        var reimbursementReceived = new Array();
                        var subReimbursementReceived = 0;

                        var reimbursement = 0;
                        var grandTotal = 0;

                        var titles = new Array();

                        console.log('count billing: '+ data[2].length);
                        if(data[2].length > 0){
                            for(var bal = 0; bal < data[2].length; bal++){
                                switch(data[2][bal].paid){
                                    case 1:
                                        balance.push('' +
                                            '<tr><td></td><td colspan="2">Bill #'+ data[2][bal].bill_number +'</td><td>'+ numeral(data[2][bal].balance).format('0,0.00') +'</td></tr>' +
                                        '');
                                        subBalance += parseFloat(data[2][bal].balance);
                                        break;
                                    default:
                                        balance.push('' +
                                            '<tr><td></td><td colspan="2">Bill #'+ data[2][bal].bill_number +'</td><td>'+ numeral(data[2][bal].total).format('0,0.00') +'</td></tr>' +
                                        '');
                                        subBalance += parseFloat(data[2][bal].total);
                                }

                                if(data[2][bal].operational_fund !== null){
                                    trustFundBalance.push('<tr><td></td><td colspan="2">Bill #'+ data[2][bal].bill_number +'</td><td>'+ numeral(data[2][bal].operational_fund.amount).format('0,0.00') +'</td></tr>');
                                    trustFundSubBalance += parseFloat(data[2][bal].operational_fund.amount);
                                    subReimbursementReceived += parseFloat(data[2][bal].operational_fund.total_amount_paid);
                                    trustFundSubBalance -= parseFloat(data[2][bal].operational_fund.total_amount_paid);
                                }
                            }
                        }else{
                            balance.push('<tr><td></td><td colspan="2"> -- </td><td>'+ numeral(0).format('0,0.00') +'</td></tr>');
                            trustFundBalance.push('<tr><td></td><td colspan="2"> -- </td><td>'+ numeral(0).format('0,0.00') +'</td></tr>');
                        }

                        for(var a = 0; a < data[0].length; a++){
                            var dItem = new Array();
                            var dItemCe = new Array();
                            var total = 0;
                            var CEtotal = 0;
                            var feeDesc;
                            var feeDescription;
                            var feeVal = '';
                            switch(data[0][a].contract.contract_type){
                                case 'special':
                                    for(var b = 0; b < data[0][a].sr_special.length; b++){

                                        for(var c = 0; c < data[0][a].sr_special[b].service_reports.length; c++){
                                            if(jQuery.inArray(data[0][a].sr_special[b].service_reports[c].id, ids) != -1){
                                                total += parseFloat(data[0][a].sr_special[b].service_reports[c].total);
                                                feeDesc = (data[0][a].sr_special[b].service_reports[c].fee_description != null) ? data[0][a].sr_special[b].service_reports[c].fee_description : '';
                                                feeDescription = (data[0][a].sr_special[b].service_reports[c].description != null) ? '('+ data[0][a].sr_special[b].service_reports[c].description +')' : '-';
                                                switch (data[0][a].sr_special[b].service_reports[c].fee_detail.charge_type){
                                                    case 'amount':
                                                        break;
                                                    case 'document':
                                                        feeVal = data[0][a].sr_special[b].service_reports[c].page_count +' page/s';
                                                        break;
                                                    case 'installment':
                                                        break;
                                                    case 'percentage':
                                                        break;
                                                    case 'time':
                                                        feeVal = data[0][a].sr_special[b].service_reports[c].minutes +' min/s';
                                                        break;
                                                }

                                                dItem.push('' +
                                                    '<tbody>' +
                                                        '<tr>' +
                                                            '<td>'+ moment(data[0][a].sr_special[b].service_reports[c].date).format('ll') +'</td>' +
                                                            '<td>'+ data[0][a].sr_special[b].service_reports[c].fee_detail.fee.display_name +'</td>' +
                                                            '<td>'+ numeral(data[0][a].sr_special[b].service_reports[c].total).format('0,0.00') +'</td>' +
                                                        '</tr>' +
                                                    '<tr>' +
                                                        '<td>'+ data[0][a].sr_special[b].service_reports[c].sr_number +'</td>' +
                                                        '<td colspan="2">'+ feeDesc +' '+ feeDescription +' '+ feeVal +'</td>' +
                                                    '</tr>' +
                                                    '</tbody>' +
                                                '');
                                                for(var d = 0; d < data[0][a].sr_special[b].service_reports[c].chargeables.length; d++){
                                                    CEtotal += parseFloat(data[0][a].sr_special[b].service_reports[c].chargeables[d].total);
                                                    chargeableExpense += parseFloat(data[0][a].sr_special[b].service_reports[c].chargeables[d].total);
                                                    dItemCe.push('' +
                                                        '<tbody>' +
                                                            '<tr>' +
                                                                '<td><small>'+ data[0][a].sr_special[b].service_reports[c].sr_number +'</small>: '+ data[0][a].sr_special[b].service_reports[c].chargeables[d].fee.display_name +': '+ data[0][a].sr_special[b].service_reports[c].chargeables[d].description +'</small>' +
                                                                '<td>'+ numeral(data[0][a].sr_special[b].service_reports[c].chargeables[d].total).format('0,0.00') +'</td>' +
                                                            '</tr>' +
                                                        '</tbody>' +
                                                    '');
                                                }
                                            }
                                        }

                                        specialRetainer += parseFloat(total);
                                        console.log('current: '+ parseFloat(total));
                                        current += parseFloat(total);
                                        titles.push(data[0][a].sr_special[b].title);

                                        billBox.find('.panel').append('' +
                                            '<div class="panel-body detail-item">' +
                                                '<div class="row row-eq-height">' +
                                                    '<div class="col-sm-6">' +
                                                        '<table>' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                                '<th colspan="3">'+ data[0][a].sr_special[b].title +' ['+ data[0][a].sr_special[b].number +']</th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '' + dItem.join('') +
                                                        '</table>' +
                                                        '<table>' +
                                                        '<tfoot>' +
                                                            '<tr>' +
                                                                '<td>Total Fees</td>' +
                                                                '<td>'+ numeral(total).format('0,0.00') +'</td>' +
                                                            '</tr>' +
                                                        '</tfoot>' +
                                                        '</table>' +
                                                    '</div>' +
                                                    '<div class="col-sm-6">' +
                                                        '<table>' +
                                                        '<thead>' +
                                                            '<tr>' +
                                                                '<th colspan="2">Chargeable Expenses </th>' +
                                                            '</tr>' +
                                                        '</thead>' +
                                                        '' + dItemCe.join('') +
                                                        '</table>' +
                                                        '<table>' +
                                                        '<tfoot>' +
                                                            '<tr>' +
                                                                '<td>Total Chargeable Expenses</td>' +
                                                                '<td>'+ numeral(CEtotal).format('0,0.00') +'</td>' +
                                                            '</tr>' +
                                                        '</tfoot>' +
                                                        '</table>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="hr-line-solid"></div>' +
                                        '');
                                    }
                                    break;
                                case 'general':

                                    for(var b = 0; b < data[0][a].sr_general.length; b++){
                                        if(jQuery.inArray(data[0][a].sr_general[b].id, ids) != -1){
                                            total += parseInt(data[0][a].sr_general[b].minutes);
                                            feeDesc = (data[0][a].sr_general[b].fee_description != null) ? data[0][a].sr_general[b].fee_description : '';
                                            switch (data[0][a].sr_general[b].fee_detail.charge_type) {
                                                case 'amount':
                                                    break;
                                                case 'document':
                                                    feeVal = data[0][a].sr_general[b].page_count + ' page/s';
                                                    break;
                                                case 'installment':
                                                    break;
                                                case 'percentage':
                                                    break;
                                                case 'time':
                                                    feeVal = data[0][a].sr_general[b].minutes + ' min/s';

                                                    // insert time charge type info: nested array
                                                    var found = $.map(GRFeeDetailID, function(item) {
                                                        if (item.id == data[0][a].sr_general[b].fee_detail_id) {
                                                            item.value += parseInt(data[0][a].sr_general[b].minutes);
                                                            return item;
                                                        }
                                                    });
                                                    if (found.length < 1) {
                                                    GRFeeDetailID.push({
                                                            'id': data[0][a].sr_general[b].fee_detail_id,
                                                            'name': data[0][a].sr_general[b].fee_detail.fee.display_name +' <small>[ '+ data[0][a].sr_general[b].fee_detail.minutes +' min/s ]</small>',
                                                            'minute': data[0][a].sr_general[b].fee_detail.minutes,
                                                            'amount': data[0][a].sr_general[b].fee_detail.amount,
                                                            'value': parseInt(data[0][a].sr_general[b].minutes)
                                                        });
                                                        fixedGeneralRetainer += parseFloat(data[0][a].sr_general[b].fee_detail.amount);
                                                    }
                                                    break;
                                            }
                                            dItem.push('' +
                                                '<tbody>' +
                                                '<tr>' +
                                                    '<td>'+ moment(data[0][a].sr_general[b].date).format('ll') +'</td>' +
                                                    '<td>'+ data[0][a].sr_general[b].fee_detail.fee.display_name +'</td>' +
                                                    '<td>'+ feeVal +'</td>' +
                                                '</tr>' +
                                                '<tr>' +
                                                    '<td>'+ data[0][a].sr_general[b].sr_number +'</td>' +
                                                    '<td colspan="2">'+ feeDesc +' ('+ data[0][a].sr_general[b].description +') '+ feeVal +'</td>' +
                                                '</tr>' +
                                                '</tbody>' +
                                            '');
                                            for(var d = 0; d < data[0][a].sr_general[b].chargeables.length; d++){
                                                CEtotal += parseFloat(data[0][a].sr_general[b].chargeables[d].total);
                                                chargeableExpense += parseFloat(data[0][a].sr_general[b].chargeables[d].total);
                                                dItemCe.push('' +
                                                    '<tbody>' +
                                                        '<tr>' +
                                                            '<td><small>'+ data[0][a].sr_general[b].sr_number +'</small>: '+ data[0][a].sr_general[b].chargeables[d].fee.display_name +': '+ data[0][a].sr_general[b].chargeables[d].description +'</small>' +
                                                            '<td>'+ numeral(data[0][a].sr_general[b].chargeables[d].total).format('0,0.00') +'</td>' +
                                                        '</tr>' +
                                                    '</tbody>' +
                                                '');
                                            }

                                        }
                                    }

                                    titles.push('General Contract');

                                    billBox.find('.panel').append('' +
                                    '<div class="panel-body detail-item">' +
                                        '<div class="row row-eq-height">' +
                                            '<div class="col-sm-6">' +
                                                '<table>' +
                                                '<thead>' +
                                                    '<tr>' +
                                                        '<th colspan="3">General Retainer ('+ data[0][a].contract.contract_number +')</th>' +
                                                    '</tr>' +
                                                '</thead>' +
                                                '' + dItem.join('') +
                                                '</table>' +
                                                '<table>' +
                                                '<tfoot>' +
                                                    '<tr>' +
                                                        '<td>Total Minutes</td>' +
                                                        '<td>'+ total + ' min/s</td>' +
                                                    '</tr>' +
                                                '</tfoot>' +
                                                '</table>' +
                                            '</div>' +
                                            '<div class="col-sm-6">' +
                                                '<table>' +
                                                '<thead>' +
                                                    '<tr>' +
                                                        '<th colspan="2">Chargeable Expenses </th>' +
                                                    '</tr>' +
                                                '</thead>' +
                                                '' + dItemCe.join('') +
                                                '</table>' +
                                                '<table>' +
                                                '<tfoot>' +
                                                    '<tr>' +
                                                        '<td>Total Chargeable Expenses</td>' +
                                                        '<td>'+ numeral(CEtotal).format('0,0.00') +'</td>' +
                                                    '</tr>' +
                                                '</tfoot>' +
                                                '</table>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="hr-line-solid"></div>' +
                                    '');

                                    break;
                            }
                        }

                        for(var gr = 0; gr < GRFeeDetailID.length; gr++){
                            var value = parseInt(GRFeeDetailID[gr].value) - parseInt(GRFeeDetailID[gr].minute);
                            value = (value < 1) ? 0 : value;
                            excessGeneralRetainer += (parseFloat(GRFeeDetailID[gr].amount) / parseInt(GRFeeDetailID[gr].minute)) * value;
                            if(value > 0){
                                GRFeeDetailName.push('' +
                                    '<tr>' +
                                    '<td style="padding-left: 30px;">'+ GRFeeDetailID[gr].name +'</td>' +
                                    '<td>'+ value +' min/s</td>' +
                                    '</tr>' +
                                '');
                            }

                        }

                        console.log('current: '+ parseFloat(fixedGeneralRetainer));
                        console.log('current: '+ parseFloat(excessGeneralRetainer));
                        current += parseFloat(fixedGeneralRetainer);
                        current += parseFloat(excessGeneralRetainer);


                        billBox.find('.detail').append('' +
                            '<div class="panel-body detail-footer">' +
                            '<div class="row">' +
                                '<div class="col-sm-6">' +
                                    '<table>' +
                                    '<thead>' +
                                        '<tr>' +
                                            '<th>PF (Special Retainers)</th>' +
                                            '<td>'+ numeral(specialRetainer).format('0,0.00') +'</td>' +
                                        '</tr>' +
                                    '</thead>' +
                                    '<thead>' +
                                        '<tr>' +
                                            '<th colspan="2">PF (Excess General Retainer)</th>' +
                                        '</tr>' +
                                    '</thead>' +
                                    '<tbody>' +
                                        ''+ GRFeeDetailName +'' +
                                    '</tbody>' +
//                                    '<thead>' +
//                                        '<tr>' +
//                                            '<th>Paralegal Charges</th>' +
//                                            '<td>00,000.00</td>' +
//                                        '</tr>' +
//                                    '</thead>' +
                                    '</table>' +
//                                    '<table>' +
//                                    '<tbody>' +
//                                        '<tr>' +
//                                            '<th colspan="3">Breakdown of Payments made (Professional Fees)</th>' +
//                                        '</tr>' +
//                                        '<tr>' +
//                                            '<td>00/00/0000</td>' +
//                                            '<td>0000000</td>' +
//                                            '<td>00,000.00</td>' +
//                                        '</tr>' +
//                                    '</tbody>' +
//                                    '</table>' +
//                                    '<table>' +
//                                    '<tbody>' +
//                                        '<tr>' +
//                                            '<th colspan="3">Breakdown of Payments made (Chargeable Expenses)</th>' +
//                                        '</tr>' +
//                                        '<tr>' +
//                                            '<td>00/00/0000</td>' +
//                                            '<td>0000000</td>' +
//                                            '<td>00,000.00</td>' +
//                                        '</tr>' +
//                                    '</tbody>' +
//                                    '</table>' +
                                '</div>' +
                            '</div>' +
                            '</div>' +
                        '');

                        billBox.prepend('' +
                            '<div class="ibox">' +
                            '<div class="ibox-content">' +
                                '<div class="row">' +
                                '<div class="col-sm-4 col-sm-push-8">' +
                                '<div class="professional-services">' +
                                '<h2>Professional Services</h2>' +
                                '<table>' +
                                '<tbody>' +
                                '<tr><td>Date</td><td class="bg-muted">'+ moment().format('MMM D, YYYY') +'</td></tr>' +
                                '<tr><td>Invoice</td><td>'+ data[5] +'</td></tr>' +
                                '<tr><td>Customer ID</td><td>[ '+ data[1].count +' ]</td></tr>' +
                                '<tr><td>Billing Period</td><td>'+ data[6] +'</td></tr>' +
                                '</tbody>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="row">' +
                                '<div class="col-sm-4">' +
                                '<div class="bill-to">' +
                                '<table>' +
                                '<thead>' +
                                '<tr><th colspan="2">Bill to:</th></tr>' +
                                '</thead>' +
                                '<tbody>' +
                                '<tr><td>'+ data[1].profile.full_name +'</td></tr>' +
                                '<tr><td>Company</td></tr>' +
                                '<tr><td>'+ data[1].business.address.description +'</td></tr>' +
                                '<tr><td>'+ data[1].business.telephone.description +'</td></tr>' +
                                '</tbody>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                            '</div>' +
                            '</div>' +
                        '');

                        subTotal += subBalance;
                        subTotal += current;

                        reimbursement = trustFundSubBalance;
                        reimbursement += parseFloat(chargeableExpense);

                        reimbursement -= parseFloat(trustFund);

                        reimbursement = (reimbursement < 0) ? 0 : reimbursement;

                        grandTotal += subTotal;
                        grandTotal += reimbursement;


                        if(percentageTax > 0){
                            var divider = (100 - parseFloat(percentageTax)) / 100;
                            var multiplier = parseFloat(percentageTax) / 100;
                            pfTax = (specialRetainer / divider) * multiplier;
                            grandTotal += pfTax;
                            pfTax = '<tr><td>Percentage Tax on PF</td><td><span>&#8369</span> '+ numeral(pfTax).format('0,0.00') +'</td></tr>';
                        }

                        billSpecial = specialRetainer;
                        billGeneral = fixedGeneralRetainer;
                        billExcess = excessGeneralRetainer;

                        billBox.find('.ibox-content').append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading text-center"><strong>Billing Summary</strong></div>' +
                            '<div class="panel-body professional-fee"><h5><i>Items Subject to Tax [ PROFESSIONAL FEES ]</i></h5>' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Previous Balance <span class="pull-right">'+ numeral(subBalance).format('0,0.00') +'</span></h5></div>' +
                            '<div class="table-responsive">' +
                            '<table>' +
                            '<tbody>' +
                            '<tr><td colspan="3"><strong>Balance from previous bill</strong></td><td></td></tr>' +
                            '' + balance.join('') +
                            '</tbody>' +
                            '<tbody>' +
                            '<tr><td><strong>Less: </strong></td><td colspan="2">Adjustment</td><td>'+ numeral(balance).format('0,0.00') +'</td></tr>' +
                            '<tr><td></td><td>Payment Received</td><td></td><td>'+ numeral(subPaymentReceived).format('0,0.00') +'</td></tr>' +
                            '<tr><td></td><td colspan="2"><strong>Unpaid Balance</strong></td><td><strong>'+ numeral(subBalance).format('0,0.00') +'</strong></td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Current Charges <span class="pull-right">'+ numeral(current).format('0,0.00') +'</span></h5></div>' +
                            '<div class="table-responsive">' +
                            '<table>' +
                            '<tbody>' +
                            '<tr><td colspan="3">Special Retainers</td><td>'+ numeral(specialRetainer).format('0,0.00') +'</td></tr>' +
                            '<tr><td colspan="3">Fixed General Retainer</td><td>'+ numeral(fixedGeneralRetainer).format('0,0.00') +'</td></tr>' +
                            '<tr class="bill-bottom"><td colspan="3">Excess General Retainers</td><td>'+ numeral(excessGeneralRetainer).format('0,0.00') +'</td></tr>' +
                            '</tbody>' +
                            '<tbody>' +
                            '<tr><td></td><td colspan="2"><strong>Total Current Charges</strong></td><td><strong>'+ numeral(current).format('0,0.00') +'</strong></td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Subtotal <span class="pull-right">'+ numeral(subTotal).format('0,0.00') +'</span></h5></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="panel-body chargeable-fee"><h5><i>Items Not Subject Tax [ CHARGEABLE EXPENSES ]</i></h5>' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Current Charges <span class="pull-right">'+ numeral(reimbursement).format('0,0.00') +'</span></h5></div>' +
                            '<div class="table-responsive">' +
                            '<table>' +
                            '<tbody>' +
                            '<tr><td colspan="3">Balance from previous bill</td><td></td></tr>' +
                            '' + trustFundBalance.join('') +
                            '</tbody>' +
                            '<tbody>' +
                            '<tr class="tr-line"><td><strong>Less: </strong></td><td colspan="2">Reimbursement Received</td><td>'+ numeral(subReimbursementReceived).format('0,0.00') +'</td></tr>' +
                            '<tr><td></td><td colspan="2"><strong>Unpaid Balance</strong></td><td><strong>'+ numeral(trustFundSubBalance).format('0,0.00') +'</strong></td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '<br>' +
                            '<div class="table-responsive">' +
                            '<table>' +
                            '<tbody>' +
                            '<tr><td><strong>Add: </strong></td><td colspan="2">Current Charges</td><td>'+ numeral(chargeableExpense).format('0,0.00') +'</td></tr>' +
                            '<tr><td>Less:</td><td>Deposit</td><td></td><td>'+ numeral(trustFund).format('0,0.00') +'</td></tr>' +
                            '<tr><td></td><td colspan="2"><strong>Total Amount For Reimbursement</strong></td><td><strong>'+ numeral(reimbursement).format('0,0.00') +'</strong></td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '<br>' +
                            '<p><strong>Please Immediately reimburse defecit amount of your Trust Fund Account</strong></p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                        '');



                        billBox.find('.ibox-content').append('' +
                            '<div class="row">' +
                            '<div class="col-sm-6">' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading"><strong>Other Comments</strong></div>' +
                            '<div class="p-xs">' +
                            '<h4 style="color: rgb(103, 106, 108); margin-top: 5px;">Note:</h4><ol style="color: rgb(103, 106, 108);"><li>Bills are due and payable upon receipt.</li><li>Check payment should be payable to ATTY. PETER LEO M. RALLA.</li><li>Payment made after billing period is not included in this statement.</li><li>The Trust Fund appearing under item II, represents the deposits made to the firm to cover all chargeable expenses.</li><li>Kindly reimburse immediately the deficit amount reflected under the Trust Fund Balance and replenish your deposit account with the firm to cover future expenses. Thank you</li></ol>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-sm-6">' +
                            '<div class="total-footer">' +
                            '<table class="table">' +
                            '<tbody>' +
                            '<tr><td>Professional Fees</td><td><span>&#8369</span> '+ numeral(subTotal).format('0,0.00') +'</td></tr>' +
                            '' + pfTax +
                            '<tr><td>Chargeables</td><td><span>&#8369</span> '+ numeral(reimbursement).format('0,0.00') +'</td></tr>' +
//                            '<tr><td>Other</td><td><span>₱</span> 1,000,000.00</td></tr>' +
                            '<tr><td>Total Amount Due</td><td><span>&#8369</span> '+ numeral(grandTotal).format('0,0.00') +'</td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '<table>' +
                            '<thead>' +
                            '<tr><td>Make all checks payable to</td></tr>' +
                            '</thead>' +
                            '<tbody>' +
                            '<tr><td>Atty. Peter Leo M. Ralla</td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="text-center">' +
                            '<p>If you have any questions about this invoice, please contact.</p>' +
                            '<p>[ Name, Phone #, E-mail ]</p>' +
                            '<h3><i>Thank You For Your Business!</i></h3>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                        '');



                    }
                });
            }

            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();

        });
    </script>
@endsection