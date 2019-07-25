@extends('layouts.master')

@section('title', 'Edit Billing')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Edit Billing</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Edit Billing</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" id="update-billing" class="btn btn-primary"><i class="fa fa-edit"></i> Update Billing</button>
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
                                <input type="text" name="month-select" class="form-control month-select" value="All">
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
                    <div class="ibox">
                        <div class="ibox-content billing-mockup" id="billing-box"></div>
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
            var billSpecial;
            var billGeneral;
            var billExcess;
            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();

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
                setter.find('h3').remove();
                setter.find('.hr-line-dashed').remove();
                $('#create-billing').prop('disabled', true);
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
                    getRetainers();
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

            $(document).on('ifChecked', '.sb_no', function(){
                getSpecialBilling();
            });

            $(document).on('change', '#client-select', function(){
                billBox.empty();
                setter.find('table').remove();
                setter.find('h3').remove();
                setter.find('.hr-line-dashed').remove();
                setter.find('.form-group').slice(2).remove();
                $('#create-billing').prop("disabled", true);
                console.log('month: '+ $('.month-select').val());
                console.log('client: '+ setter.find('#client-input').data('id'));
                $.get('{!! route('get-billable-contract') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('#client-input').data('id')
                },function(data){
                    console.log(data);
//                    console.log(data[0].length);
//                    console.log(data[1].length);
//                    console.log(data[2].length);
//                    console.log(data[7].length);
//                    console.log(data[4]);

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-4 col-sm-push-8">' +
                            '<div class="professional-services"><h2>Professional Services</h2>' +
                            '<table>' +
                            '<tbody>' +
                            '<tr><td>Date</td><td class="bg-muted">'+ moment().format('MMM D, YYYY') +'</td></tr>' +
                            '<tr><td>Invoice</td><td>'+ data[5] +'</td></tr>' +
                            '<tr><td>Customer ID</td><td>[ '+ data[3].count +' ]</td></tr>' +
                            '<tr><td>Billing Period</td><td>'+ data[6] +'</td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    var bussName = (data[3].business.name !== null) ? '<p>'+ data[3].business.name +'</p>' : '';
                    var bussTel = (data[3].business.telephone.description !== null) ? '<p>'+ data[3].business.telephone.description +'</p>' : '';
                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-4">' +
                            '<div class="bill-to">' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading text-center"><strong>Bill to:</strong></div>' +
                            '<div class="p-xs">' +
                            '<p><strong>'+ data[3].profile.full_name +'</strong></p>' +
                            '' + bussName +
                            '<p>'+ data[3].business.address.description +'</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="panel panel-default summary" id="billing-summary">' +
                            '<div class="panel-heading text-center"><strong>Billing Summary</strong></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-6">' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading"><strong>Notes</strong></div>' +
                            '<div class="p-xs billing-note note-retainer">' +
                            ''+ data[4][0].description +
                            '</div>' +
                            '<div class="p-xs billing-note note-special">' +
                            ''+ data[4][3].description +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-sm-6">' +
                            '<div class="total-footer"></div>' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading"><strong>Make all checks payable to</strong></div>' +
                            '<div class="p-xs">' +
                            ''+ data[4][2].description +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            ''+ data[4][1].description +
                            '</div>' +
                            '</div>' +
                            '');

                    var billSummary = $('#billing-summary');
                    if( (data[0].length > 0) && ( (data[1].length > 0) || (data[2].length > 0) ) ){
                        setter.append(billingType);
                        setter.append(percentageTax);
                    }
                    else if( (data[0].length > 0) && ( (data[1].length < 1) || (data[2].length < 1) ) ){
                        setter.append(percentageTax);
                        billBox.find('.note-special').show();
                        billSummary.find('.panel-body').remove().end().append(specialBillingFee);
                        getSB();
                    }
                    else{
                        setter.append(percentageTax);
                        billBox.find('.note-retainer').show();
                        billSummary.find('.panel-body').remove().end().append(professionalFee);
                        billSummary.append(chargeableFee);
                        getSR();
                        getRetainers();
                    }


                });
            });

            $(document).on('keyup','#percentage-tax', function(){
                delay(function(){
                    switch(setter.find('.sb_no:checked').val()){
                        case undefined:
                            getRetainers();
                            break;
                        default:
                            getSpecialBilling();
                    }
                }, 500 );
            });

            $(document).on('change','#billing-type',function(){
                var billSummary = $('#billing-summary');
                billSpecial = 0;
                billGeneral = 0;
                billExcess = 0;
                $('#create-billing').prop("disabled", true);
                setter.find('table').remove();
                setter.find('h3').remove();
                billBox.find('.billing-note').hide();
                switch($(this).val()) {
                    case 'special-billing':
                        billBox.find('.note-special').show();
                        billSummary.find('.panel-body').remove().end().append(specialBillingFee);
                        getSB();
                        break;
                    case 'retainer-contract':
                        billBox.find('.note-retainer').show();
                        billSummary.find('.panel-body').remove().end().append(professionalFee);
                        billSummary.append(chargeableFee);
                        getSR();
                        getRetainers();
                        break;
                }
            });

            $(document).on('click', '#update-billing', function(){
                var ids = $('.sr_no:checked').map(function(){
                    return parseInt($(this).val());
                }).toArray();

//                console.log(ids);
//                return false;

                var billType;
                switch(setter.find('.sb_no:checked').val()){
                    case undefined:
                        billType = 'retainer-contract';
                        break;
                    default:
                        billType = 'special-billing';
                }

//                console.log('billSpecial : '+ billSpecial);
//                console.log('billGeneral : '+ billGeneral);
//                console.log('billExcess : '+ billExcess);
//                console.log('client_id : '+ setter.find('#client-input').data('id'));
//                console.log('billing_type : '+ billType);
//                console.log('ids : '+ ids);
//                console.log('case_id : '+ setter.find('.sb_no:checked').val());
//                console.log('percentage_tax : '+ setter.find('input[name="percentage-tax"]').val()||0);
//                return false;

                $.post('{!! route('update-billing') !!}', {
                    _token: '{!! csrf_token() !!}',
                    client_id: setter.find('#client-input').data('id'),
                    billing_id: '{!! $billing->id !!}',
                    billing_type: billType,
                    ids: ids,
                    fee_detail_id: setter.find('.sb_no:checked').val()||null,
                    percentage_tax: setter.find('input[name="percentage-tax"]').val()||0,
                    special: billSpecial,
                    general: billGeneral,
                    excess: billExcess,
                    month: $('.month-select').val(),
                    content: $('#billing-content').html()
                }, function(data){
                    console.log(data);
                    if(data){
//                        window.open('http://'+window.location.host+'/billings/print?client_id='+ data.client_id);
//                        window.open('http://'+window.location.host+'/billings/'+ data.id +'/pdf');
                        window.open('http://'+window.location.host+'/billings/pdf/regenerate?client_id='+ data.client_id);
                        delay(function(){
                            window.location.replace('http://'+window.location.host+'/billing/'+ data.id);
                        }, 500 );
                    }
                });

            });

            function clientInfo(){
                billBox.empty();
                setter.find('table').remove();
                setter.find('h3').remove();
                setter.find('.hr-line-dashed').remove();
                setter.find('.form-group').slice(2).remove();
                $('#create-billing').prop("disabled", true);
                console.log('month: '+ $('.month-select').val());
                console.log('client: '+ setter.find('#client-input').data('id'));
//                return false;
                $.get('{!! route('get-billable-contract') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('#client-input').data('id')
                },function(data){
                    console.log(data);
                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-4 col-sm-push-8">' +
                            '<div class="professional-services"><h2>Professional Services</h2>' +
                            '<table>' +
                            '<tbody>' +
                            '<tr><td>Date</td><td class="bg-muted">'+ moment().format('MMM D, YYYY') +'</td></tr>' +
                            '<tr><td>Invoice</td><td>'+ data[5] +'</td></tr>' +
                            '<tr><td>Customer ID</td><td>[ '+ data[3].count +' ]</td></tr>' +
                            '<tr><td>Billing Period</td><td>'+ data[6] +'</td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    var bussName = (data[3].business.name !== null) ? '<p>'+ data[3].business.name +'</p>' : '';
                    var bussTel = (data[3].business.telephone.description !== null) ? '<p>'+ data[3].business.telephone.description +'</p>' : '';
                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-4">' +
                            '<div class="bill-to">' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading text-center"><strong>Bill to:</strong></div>' +
                            '<div class="p-xs">' +
                            '<p><strong>'+ data[3].profile.full_name +'</strong></p>' +
                            '' + bussName +
                            '<p>'+ data[3].business.address.description +'</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="panel panel-default summary" id="billing-summary">' +
                            '<div class="panel-heading text-center"><strong>Billing Summary</strong></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-6">' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading"><strong>Notes</strong></div>' +
                            '<div class="p-xs billing-note note-retainer">' +
                            ''+ data[4][0].description +
                            '</div>' +
                            '<div class="p-xs billing-note note-special">' +
                            ''+ data[4][3].description +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-sm-6">' +
                            '<div class="total-footer"></div>' +
                            '<div class="panel panel-default summary">' +
                            '<div class="panel-heading"><strong>Make all checks payable to</strong></div>' +
                            '<div class="p-xs">' +
                            ''+ data[4][2].description +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                        '');

                    billBox.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            ''+ data[4][1].description +
                            '</div>' +
                            '</div>' +
                            '');

                    var billSummary = $('#billing-summary');
                    setter.append(percentageTax);
                    billBox.find('.note-retainer').show();
                    billSummary.find('.panel-body').remove().end().append(professionalFee);
                    billSummary.append(chargeableFee);
                    getSR();
                    getRetainers();


                });
            }

            function getMonth(){
                setter.append('' +
                    '<div class="form-group">' +
                    '<label>Client</label>' +
                    '<input type="text" id="client-input" class="form-control" data-id="{!! $billing->client->id !!}" value="{!! $billing->client->profile->full_name !!}" readonly>' +
                    '</div>' +
                '');
                clientInfo();

            }

            function getSR(){
                var month = moment();
                $.get('{!! route('service-report-list-edit') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('#client-input').data('id')
                },function(data){
//                    console.log(data);
                    if(data.length > 0){
                        setter.append('<h3>Service Report List</h3>' +
                                '<table class="table table-striped">' +
                                '<thead>' +
                                '<tr>' +
                                '<th style="width: 38px; vertical-align: middle">' +
                                '<div class="i-checks">' +
                                '<input type="checkbox" value="" class="check-all">' +
                                '</div>' +
                                '</th>' +
                                '<th style="vertical-align: middle">Select all</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody></tbody>' +
                                '</table>' +
                                '');
                        for(var a = 0; a < data.length; a++){
                            var srChecked = (data[a].billing_id !== null) ? 'checked':'';
                            setter.find('tbody').append('' +
                                    '<tr>' +
                                    '<td>' +
                                    '<div class="i-checks">' +
                                    '<input type="checkbox" name="sr_no" class="sr_no" value="'+ data[a].id +'" '+ srChecked +' >' +
                                    '</div>' +
                                    '</td>' +
                                    '<td><strong>'+ data[a].sr_number +'</strong> <small class="text-success">[ '+ moment(data[a].date).from(month, true) +' ]</small> <br><small class="text-info">'+ data[a].fee_detail.fee.display_name +'</small></td>' +
                                    '</tr>' +
                                    '');
                        }
                        $('.i-checks').iCheck({
                            checkboxClass: 'icheckbox_square-green'
                        });

                        delay(function(){
                            getRetainers();
                        }, 500 );
                    }
                });
            }

            function getSB(){
                $.get('{!! route('special-billing-list') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('#client-input').data('id')
                },function(data){
                    console.log(data);
//                    return false;
                    if(data.length > 0){
                        setter.append('<h3>Special Billing List</h3>' +
                                '<table class="table table-striped">' +
                                '<tbody></tbody>' +
                                '</table>' +
                                '');

                        for(var a = 0; a < data.length; a++){
                            setter.find('tbody').append('' +
                                    '<tr>' +
                                    '<td>' +
                                    '<div class="i-checks">' +
                                    '<input type="radio" name="sb_no" class="sb_no" value="'+ data[a].id +'">' +
                                    '</div>' +
                                    '</td>' +
                                    '<td>' +
                                    '<strong>'+ data[a].cases.title +'</strong>' +
                                    '<br><small class="text-info">'+ data[a].fee.display_name +'</small>'+
                                    '</td>' +
                                    '</tr>' +
                                    '');
                        }

                        $('.i-checks').iCheck({
                            radioClass: 'iradio_square-green'
                        });
                    }
                });
            }

            function getRetainers(){
                var billSummary = $('#billing-summary').find('.professional-fee');
                var ceSummary = billBox.find('.chargeable-fee');
                billBox.find('.hr-line-dashed').remove();
                billBox.find('.detail').remove();
                billSummary.empty();
                ceSummary.empty();
                var ids = new Array();
                $('.sr_no:checked').each(function(){
                    ids.push(parseInt($(this).val()));
                });
                console.log('ids: '+ ids);
                $.get('{!! route('get-retainers-edit') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('#client-input').data('id'),
                    billing_id: '{!! $billing->id !!}',
                    ids: ids
                },function(data){
                    console.log(data);
//                    return false;

                    var percentageTax = setter.find('input[name="percentage-tax"]').val()||0;
                    var pfTax = '';
                    var unpaidBalance = 0;
                    var specialRetainer = 0;
                    var generalRetainer = 0;
                    var excessGeneralRetainer = 0;
                    var minutesGeneralRetainer = 0;
                    var grMultiplier = 0;
                    var totalMinutes = 0;
                    var excessMinutes = 0;
                    var grFeeName = '';
                    var currentCharges = 0;
                    var subTotal = 0;
                    var professionalFees = 0;
                    var trustFund = parseFloat(data[2]);
                    var remainTrustFund = parseFloat(data[2]);
                    var reimbursement = 0;
                    var chargeables = 0;
                    var grandTotal = 0;
                    var balanceItems = new Array();
                    var operationalFunds = new Array();
                    var balanceOpeFunds = 0;
                    var chargeableBalance = 0;

                    if(data[0] !== null){
                        grFeeName = data[0].fee_detail.fee.display_name;
                        minutesGeneralRetainer += parseInt(data[0].fee_detail.minutes);
                        generalRetainer += parseFloat(data[0].fee_detail.total);
                        grMultiplier += generalRetainer / minutesGeneralRetainer;
                        currentCharges += generalRetainer;
                        professionalFees += generalRetainer;
                        subTotal += currentCharges;

                        if(data[0].service_reports.length > 0){
                            billBox.append(serviceReportDetail);
                            var grServiceReports = new Array();
                            var grSrMinutes = 0;
                            var grTotalCe = 0;
                            for(var a = 0; a < data[0].service_reports.length; a++){

                                if(jQuery.inArray(data[0].service_reports[a].id, ids) !== -1){
                                    var grChargeableExpense = new Array();
                                    var grDescription;
                                    totalMinutes += parseInt(data[0].service_reports[a].minutes);
                                    grSrMinutes += parseInt(data[0].service_reports[a].minutes);
                                    grDescription = (data[0].service_reports[a].description !== null) ? '( '+ data[0].service_reports[a].description +' )' : '';

                                    for(var b = 0; b < data[0].service_reports[a].chargeables.length; b++){
                                        grTotalCe += parseFloat(data[0].service_reports[a].chargeables[b].total);
                                        chargeables += parseFloat(data[0].service_reports[a].chargeables[b].total);
                                        grChargeableExpense.push('' +
                                            '<table>' +
                                            '<tbody>' +
                                            '<tr>' +
                                            '<td>'+ data[0].service_reports[a].chargeables[b].fee.display_name +': '+ data[0].service_reports[a].chargeables[b].description +'</small>' +
                                            '<td>'+ numeral(data[0].service_reports[a].chargeables[b].total).format('0,0.00') +'</td>' +
                                            '</tr>' +
                                            '</tbody>' +
                                            '</table>' +
                                        '');
                                    }

                                    grServiceReports.push('' +
                                            '<div class="row row-eq-height">' +
                                            '<div class="col-sm-6">' +
                                            '<table>' +
                                            '<tbody>' +
                                            '<tr>' +
                                            '<td>'+ moment(data[0].service_reports[a].date).format('MM/DD/YYYY') +'</td>' +
                                            '<td>'+ data[0].service_reports[a].fee_detail.fee.display_name +'</td>' +
                                            '<td>'+ data[0].service_reports[a].minutes +' min/s</td>' +
                                            '</tr>' +
                                            '<tr>' +
                                            '<td>'+ data[0].service_reports[a].sr_number +'</td>' +
                                            '<td colspan="2">'+ data[0].service_reports[a].fee_description +' '+ grDescription +' '+ data[0].service_reports[a].minutes +' min/s</td>' +
                                            '</tr>' +
                                            '</tbody>' +
                                            '</table>' +
                                            '</div>' +
                                            '<div class="col-sm-6">' +
                                            '' + grChargeableExpense.join('') +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="hr-line-dashed"></div>' +
                                            '');


                                } // sr id in array

                            } // GR Sr loop

                            if(grServiceReports.length > 0){
                                billBox.find('.detail').append('' +
                                        '<div class="panel-body detail-item">' +
                                        '<div class="row row-eq-height">' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th colspan="3">General Retainer ( '+ data[0].contract_number +' )</th>' +
                                        '</tr>' +
                                        '</thead>' +
                                        '</table>' +
                                        '</div>' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th colspan="2">Chargeable Expenses</th>' +
                                        '</tr>' +
                                        '</thead>' +
                                        '</table>' +
                                        '</div>' +
                                        '</div>' +
                                        '' + grServiceReports.join('') +
                                        '<div class="row row-eq-height">' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<tfoot>' +
                                        '<tr>' +
                                        '<td>Total Mins</td>' +
                                        '<td>'+ grSrMinutes +' min/s</td>' +
                                        '</tr>' +
                                        '</tfoot>' +
                                        '</table>' +
                                        '</div>' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<tfoot>' +
                                        '<tr>' +
                                        '<td>Total Chargeable Expenses</td>' +
                                        '<td>'+ numeral(grTotalCe).format('0,0.00') +'</td>' +
                                        '</tr>' +
                                        '</tfoot>' +
                                        '</table>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="hr-line-solid"></div>' +
                                        '');
                            }
                        }

                    }

                    if(data[1] !== null){
                        if( (data[0] !== null) && (data[0].service_reports.length < 1) ){
                            billBox.append(serviceReportDetail);
                        }
                        else if (data[0] === null){
                            billBox.append(serviceReportDetail);
                        }

                        for(var c = 0; c < data[1].length; c++){
                            var srTotalFees = 0;
                            var srTotalCE = 0;
                            var srServiceReports = new Array();

                            for(var d = 0; d < data[1][c].service_reports.length; d++){
                                if(jQuery.inArray(data[1][c].service_reports[d].id, ids) !== -1){
                                    specialRetainer += parseFloat(data[1][c].service_reports[d].total);
                                    currentCharges += parseFloat(data[1][c].service_reports[d].total);
                                    professionalFees += parseFloat(data[1][c].service_reports[d].total);
                                    subTotal += parseFloat(data[1][c].service_reports[d].total);
                                    var srChargeableExpense = new Array();
                                    var srFeeDesc;
                                    var srDescription;
                                    var srFeeVal = '';
                                    srTotalFees += parseFloat(data[1][c].service_reports[d].total);
                                    srFeeDesc = (data[1][c].service_reports[d].fee_description !== null) ? data[1][c].service_reports[d].fee_description : '';
                                    srDescription = (data[1][c].service_reports[d].description !== null) ? '( '+ data[1][c].service_reports[d].description +' )' : '';

                                    switch (data[1][c].service_reports[d].fee_detail.charge_type){
                                        case 'amount':
                                            break;
                                        case 'document':
                                            srFeeVal = data[1][c].service_reports[d].page_count +' page/s';
                                            break;
                                        case 'installment':
                                            break;
                                        case 'percentage':
                                            break;
                                        case 'time':
                                            srFeeVal = data[1][c].service_reports[d].minutes +' min/s';
                                            break;
                                    }

                                    for(var e = 0; e < data[1][c].service_reports[d].chargeables.length; e++){
                                        srTotalCE += parseFloat(data[1][c].service_reports[d].chargeables[e].total);
                                        chargeables += parseFloat(data[1][c].service_reports[d].chargeables[e].total);
                                        srChargeableExpense.push('' +
                                                '<table>' +
                                                '<tbody>' +
                                                '<tr>' +
                                                '<td>'+ data[1][c].service_reports[d].chargeables[e].fee.display_name +': '+ data[1][c].service_reports[d].chargeables[e].description +'</small>' +
                                                '<td>'+ numeral(data[1][c].service_reports[d].chargeables[e].total).format('0,0.00') +'</td>' +
                                                '</tr>' +
                                                '</tbody>' +
                                                '</table>' +
                                                '');
                                    }

                                    srServiceReports.push('' +
                                            '<div class="row row-eq-height">' +
                                            '<div class="col-sm-6">' +
                                            '<table>' +
                                            '<tbody>' +
                                            '<tr>' +
                                            '<td>'+ moment(data[1][c].service_reports[d].date).format('MM/DD/YYYY') +'</td>' +
                                            '<td>'+ data[1][c].service_reports[d].fee_detail.fee.display_name +'</td>' +
                                            '<td>'+ numeral(data[1][c].service_reports[d].total).format('0,0.00') +'</td>' +
                                            '</tr>' +
                                            '<tr>' +
                                            '<td>'+ data[1][c].service_reports[d].sr_number +'</td>' +
                                            '<td colspan="2">'+ srFeeDesc +' '+ srDescription +' '+ srFeeVal +'</td>' +
                                            '</tr>' +
                                            '</tbody>' +
                                            '</table>' +
                                            '</div>' +
                                            '<div class="col-sm-6">' +
                                            '' + srChargeableExpense.join('') +
                                            '</div>' +
                                            '</div>' +
                                            '<div class="hr-line-dashed"></div>' +
                                            '');
                                }
                            } // service reports loop

                            if(srServiceReports.length > 0){
                                billBox.find('.detail').append('' +
                                        '<div class="panel-body detail-item">' +
                                        '<div class="row row-eq-height">' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th colspan="3">'+ data[1][c].title +' ( '+ data[1][c].number +' )</th>' +
                                        '</tr>' +
                                        '</thead>' +
                                        '</table>' +
                                        '</div>' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th colspan="2">Chargeable Expenses</th>' +
                                        '</tr>' +
                                        '</thead>' +
                                        '</table>' +
                                        '</div>' +
                                        '</div>' +
                                        '' + srServiceReports.join('') +
                                        '<div class="row row-eq-height">' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<tfoot>' +
                                        '<tr>' +
                                        '<td>Total Fees</td>' +
                                        '<td>'+ numeral(srTotalFees).format('0,0.00') +'</td>' +
                                        '</tr>' +
                                        '</tfoot>' +
                                        '</table>' +
                                        '</div>' +
                                        '<div class="col-sm-6">' +
                                        '<table>' +
                                        '<tfoot>' +
                                        '<tr>' +
                                        '<td>Total Chargeable Expenses</td>' +
                                        '<td>'+ numeral(srTotalCE).format('0,0.00') +'</td>' +
                                        '</tr>' +
                                        '</tfoot>' +
                                        '</table>' +
                                        '</div>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div class="hr-line-solid"></div>' +
                                        '');
                            }
                        }

                    }

                    if(totalMinutes > minutesGeneralRetainer){
                        excessMinutes = totalMinutes - minutesGeneralRetainer;
                        excessGeneralRetainer += excessMinutes * grMultiplier;
                        currentCharges += excessGeneralRetainer;
                        professionalFees += excessGeneralRetainer;
                        subTotal += excessGeneralRetainer;
                    }

                    billBox.find('.detail').append('' +
                            '<div class="panel-body detail-footer">' +
                            '<div class="row">' +
                            '<div class="col-sm-6">' +
                            '<table>' +
                            '<thead>' +
                            '<tr><th>PF (Special Retainers)</th><td>'+ numeral(professionalFees).format('0,0.00') +'</td></tr>' +
                            '</thead>' +
                            '<thead>' +
                            '<tr><th colspan="2">PF (Excess General Retainer)</th></tr>' +
                            '</thead>' +
                            '<tbody>' +
                            '<tr>' +
                            '<td style="padding-left: 30px;">'+ grFeeName +'</td>' +
                            '<td>'+ excessMinutes +' min/s</td>' +
                            '</tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');


                    // previous balance loop
                    if(data[3].length > 0){
                        for(var e = 0; e < data[3].length; e++){
                            switch(data[3][e].paid){
                                case 1:
                                    var amountPaid = parseFloat(data[3][e].total) - parseFloat(data[3][e].balance);
                                    balanceItems.push('' +
                                            '<tr>' +
                                            '<td>Bill #'+ data[3][e].bill_number +'</td>' +
                                            '<td class="text-right">'+ numeral(data[3][e].total).format('0,0.00') +'</td>' +
                                            '<td class="text-right">'+ numeral(amountPaid).format('0,0.00') +'</td>' +
                                            '<td class="text-right">'+ numeral(data[3][e].balance).format('0,0.00') +'</td></tr>' +
                                            '');
                                    unpaidBalance += parseFloat(data[3][e].balance);
                                    break;
                                default:
                                    balanceItems.push('' +
                                            '<tr>' +
                                            '<td>Bill #'+ data[3][e].bill_number +'</td>' +
                                            '<td class="text-right">'+ numeral(data[3][e].total).format('0,0.00') +'</td>' +
                                            '<td class="text-right">'+ numeral(0).format('0,0.00') +'</td>' +
                                            '<td class="text-right">'+ numeral(data[3][e].total).format('0,0.00') +'</td></tr>' +
                                            '');
                                    unpaidBalance += parseFloat(data[3][e].total);
                            }

                            if(data[3][e].operational_fund !== null){
//                                    operationalFunds.push('<tr><td></td><td colspan="2">Bill #'+ data[3][e].bill_number +'</td><td>'+ numeral(data[3][e].operational_fund.amount).format('0,0.00') +'</td></tr>');
                                operationalFunds.push('' +
                                        '<tr>' +
                                        '<td>Bill #'+ data[3][e].bill_number +'</td>' +
                                        '<td class="text-right">'+ numeral(data[3][e].operational_fund.amount).format('0,0.00') +'</td>' +
                                        '<td class="text-right">'+ numeral(data[3][e].operational_fund.total_amount_paid).format('0,0.00') +'</td>' +
                                        '<td class="text-right">'+ numeral(data[3][e].operational_fund.balance).format('0,0.00') +'</td></tr>' +
                                        '');
                                balanceOpeFunds += parseFloat(data[3][e].operational_fund.balance);
                            }
                        }
                    }else{
                        balanceItems.push('<tr><td colspan="4"> -- </td></tr>');
                        operationalFunds.push('<tr><td colspan="4"> -- </td></tr>');
                    }

                    subTotal += unpaidBalance;
                    chargeableBalance += balanceOpeFunds;
                    // clients balance section
                    billSummary.append('' +
                            '<h5><i>Items Subject to Tax [ PROFESSIONAL FEES ]</i></h5>' +
                            '');

                    if(unpaidBalance > 0){
                        billSummary.append('' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="title"><h5>Unsettled Bill <span class="pull-right">'+ numeral(unpaidBalance).format('0,0.00') +'</span></h5></div>' +
                                '<div class="table-responsive">' +
                                '<table class="table">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Bill No.</th>' +
                                '<th class="text-right">Total</th>' +
                                '<th class="text-right">Amount Paid</th>' +
                                '<th class="text-right">Latest Balance</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                ''+ balanceItems.join('') +
                                '</tbody>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '');
                    }

                    // client current charges
                    billSummary.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Current Charges <span class="pull-right">'+ numeral(currentCharges).format('0,0.00') +'</span></h5></div>' +
                            '<div class="table-responsive">' +
                            '<table class="table-summary">' +
                            '<tbody>' +
                            '<tr><td colspan="3">Special Retainers</td><td>'+ numeral(specialRetainer).format('0,0.00') +'</td></tr>' +
                            '<tr><td colspan="3">Fixed General Retainer</td><td>'+ numeral(generalRetainer).format('0,0.00') +'</td></tr>' +
                            '<tr class="bill-bottom"><td colspan="3">Excess General Retainers</td><td>'+ numeral(excessGeneralRetainer).format('0,0.00') +'</td></tr>' +
                            '</tbody>' +
                            '<tbody>' +
                            '<tr><td></td><td colspan="2"><strong>Total Current Charges</strong></td><td><strong>'+ numeral(currentCharges).format('0,0.00') +'</strong></td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    // client subtotal
                    billSummary.append('<div class="row"><div class="col-sm-12"><div class="title"><h5>Subtotal <span class="pull-right">'+ numeral(subTotal).format('0,0.00') +'</span></h5></div></div></div>');

                    chargeableBalance += chargeables;
                    var currentChargesInfo = new Array();
                    var balanceOpeFundsDisplay = '';
                    if(balanceOpeFunds > 0){

                        balanceOpeFundsDisplay = '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="title"><h5>Unsettled Operational Fund <span class="pull-right">'+ numeral(balanceOpeFunds).format('0,0.00') +'</span></h5></div>' +
                                '<div class="table-responsive">' +
                                '<table class="table">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Bill No.</th>' +
                                '<th class="text-right">Total</th>' +
                                '<th class="text-right">Amount Paid</th>' +
                                '<th class="text-right">Latest Balance</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>' +
                                ''+ operationalFunds.join('') +
                                '</tbody>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                    }

                    remainTrustFund -= chargeables;
                    if(remainTrustFund < 0){
                        reimbursement = Math.abs(remainTrustFund);
                        currentChargesInfo.push('<tr><td></td><td colspan="2"><strong>Total Amount For Reimbursement</strong></td><td><strong>( '+ numeral(reimbursement).format('0,0.00') +' )</strong></td></tr>');
                    } else if(remainTrustFund >= 0){

                        if( (balanceOpeFunds > 0) && (remainTrustFund > 0) ){
                            remainTrustFund -= balanceOpeFunds;
                            currentChargesInfo.push('<tr><td></td><td colspan="2">Operational Funds</td><td>'+ numeral(balanceOpeFunds).format('0,0.00') +'</td></tr>');
                        }

                        if(remainTrustFund >= 0){
                            currentChargesInfo.push('<tr><td></td><td colspan="2"><strong>Remaining Trust Fund</strong></td><td><strong>'+ numeral(remainTrustFund).format('0,0.00') +'</strong></td></tr>');
                        }else{
                            currentChargesInfo.push('<tr><td></td><td colspan="2"><strong>Remaining Trust Fund</strong></td><td><strong>'+ numeral(0).format('0,0.00') +'</strong></td></tr>');
                        }
                    }

                    // chargeable expenses
                    ceSummary.append('' +
                            '<h5><i>Items Not Subject to Tax [ CHARGEABLE EXPENSES ]</i></h5>' +
                            ''+ balanceOpeFundsDisplay +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Current Charges <span class="pull-right">'+ numeral(chargeables).format('0,0.00') +'</span></h5></div>' +
                            '<div class="table-responsive">' +
                            '<table class="table-summary">' +
                            '<tbody>' +
                            '<tr><td colspan="2"><strong>Current Trust Fund Balance</strong></td><td></td><td>'+ numeral(trustFund).format('0,0.00') +'</td></tr>' +
                            '<tr><td><strong>Less: </strong></td><td colspan="2">Chargeable Expenses</td><td>'+ numeral(chargeables).format('0,0.00') +'</td></tr>' +
                            ''+ currentChargesInfo.join('') +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');
                    ceSummary.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Subtotal <span class="pull-right">'+ numeral(chargeableBalance).format('0,0.00') +'</span></h5></div>' +
                            '<br>' +
                            '<p><strong>Please Immediately reimburse defecit amount of your Trust Fund Account</strong></p>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billSpecial = specialRetainer;
                    billGeneral = generalRetainer;
                    billExcess = excessGeneralRetainer;

                    grandTotal += specialRetainer;
                    grandTotal += generalRetainer;
                    grandTotal += excessGeneralRetainer;
                    grandTotal += reimbursement;
                    if(percentageTax > 0){
                        var divider = (100 - parseFloat(percentageTax)) / 100;
                        var multiplier = parseFloat(percentageTax) / 100;
                        pfTax = (specialRetainer / divider) * multiplier;
                        grandTotal += pfTax;
                        pfTax = '<tr><td>Percentage Tax on PF</td><td><span>&#8369</span> '+ numeral(pfTax).format('0,0.00') +'</td></tr>';
                    }

                    billBox.find('.total-footer').empty().append('' +
                            '<table class="table">' +
                            '<tbody>' +
                            '<tr><td>Professional Fees</td><td><span>&#8369</span> '+ numeral(professionalFees).format('0,0.00') +'</td></tr>' +
                            ''+ pfTax +
                            '<tr><td>Chargeables</td><td><span>&#8369</span> '+ numeral(reimbursement).format('0,0.00') +'</td></tr>' +
                            '<tr><td>Total Amount Due</td><td><span>&#8369</span> '+ numeral(grandTotal).format('0,0.00') +'</td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '');

                    if(grandTotal > 0){
                        $('#create-billing').prop("disabled", false);
                    }
                });
            }

            function getSpecialBilling(){
                var billSummary = $('#billing-summary').find('.professional-fee');
                billBox.find('.detail').remove();
                $.get('{!! route('get-special-billing') !!}',{
                    month: $('.month-select').val(),
                    client_id: setter.find('#client-input').data('id'),
                    fee_detail_id: setter.find('.sb_no:checked').val()
                },function(data){
                    console.log(data);
                    var percentageTax = setter.find('input[name="percentage-tax"]').val()||0;
                    var pfTax = '';
                    var unpaidBalance = 0;
                    var specialRetainer = 0;
                    var currentCharges = 0;
                    var professionalFees = 0;
                    var subTotal = 0;
                    var grandTotal = 0;
                    var balanceItems = new Array();
                    var operationalFunds = new Array();
                    var balanceOpeFunds = 0;

                    // previous balance loop
                    if(data[1].length > 0){
                        for(var b = 0; b < data[1].length; b++){
                            switch(data[1][b].paid){
                                case 1:
                                    balanceItems.push('' +
                                            '<tr><td></td><td colspan="2">Bill #'+ data[1][b].bill_number +'</td><td>'+ numeral(data[1][b].balance).format('0,0.00') +'</td></tr>' +
                                            '');
                                    unpaidBalance += parseFloat(data[1][b].balance);
                                    break;
                                default:
                                    balanceItems.push('' +
                                            '<tr><td></td><td colspan="2">Bill #'+ data[1][b].bill_number +'</td><td>'+ numeral(data[1][b].total).format('0,0.00') +'</td></tr>' +
                                            '');
                                    unpaidBalance += parseFloat(data[1][b].total);
                            }

                            if(data[1][b].operational_fund !== null){
                                operationalFunds.push('<tr><td></td><td colspan="2">Bill #'+ data[1][b].bill_number +'</td><td>'+ numeral(data[1][b].operational_fund.amount).format('0,0.00') +'</td></tr>');
                                balanceOpeFunds += parseFloat(data[1][b].operational_fund.amount);
                            }
                        }
                    }else{
                        balanceItems.push('<tr><td></td><td colspan="2"> -- </td><td>0.00</td></tr>');
                        operationalFunds.push('<tr><td></td><td colspan="2"> -- </td><td>0.00</td></tr>');
                    }

                    // clients balance section
                    billSummary.empty().append('<h3><i>Special Billing</i></h3>');

                    var info;
                    specialRetainer += parseFloat(data[0].total);
                    currentCharges += parseFloat(data[0].total);
                    professionalFees += parseFloat(data[0].total);
                    subTotal += parseFloat(data[0].total);
                    switch(data[0].charge_type){
                        case 'amount':
                            info = ('' +
                            '<tr><td colspan="3">'+ data[0].fee.display_name +'</td><td>'+ numeral(data[0].total).format('0,0.00') +'</td></tr>' +
                            '');
                            break;
                        case 'percentage':
                            info = ('' +
                            '<tr>' +
                            '<td colspan="3">'+ data[0].fee.display_name +': <strong>'+ data[0].percentage +'%</strong> of [ ' + numeral(data[0].amount).format('0,0.00') + ' ]</td>' +
                            '<td>'+ numeral(data[0].total).format('0,0.00') +'</td>' +
                            '</tr>' +
                            '');
                            break;
                    }

                    // client current charges
                    var caseNumber = (data[0].cases.number === null) ? '' : '[ '+ data[0].cases.number +' ]';
                    billSummary.append('' +
                            '<div class="row">' +
                            '<div class="col-sm-12">' +
                            '<div class="title"><h5>Current Charges <span class="pull-right">'+ numeral(currentCharges).format('0,0.00') +'</span></h5></div>' +
                            '<div class="sub-title"><h4>'+ data[0].cases.title +' '+ caseNumber +'</h4> <small>[ <i>Case Information</i> ]</small></div>' +
                            '<div class="table-responsive">' +
                            '<table class="table-summary">' +
                            '<tbody>' +
                            '' + info +
                            '</tbody>' +
                            '<tbody>' +
                            '<tr class="bill-top"><td></td><td colspan="2"><strong>Total Current Charges</strong></td><td><strong>'+ numeral(currentCharges).format('0,0.00') +'</strong></td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '');

                    billSpecial = specialRetainer;
                    grandTotal += specialRetainer;
                    if(percentageTax > 0){
                        var divider = (100 - parseFloat(percentageTax)) / 100;
                        var multiplier = parseFloat(percentageTax) / 100;
                        pfTax = (specialRetainer / divider) * multiplier;
                        grandTotal += pfTax;
                        pfTax = '<tr><td>Percentage Tax on PF</td><td><span>&#8369</span> '+ numeral(pfTax).format('0,0.00') +'</td></tr>';
                    }

                    billBox.find('.total-footer').empty().append('' +
                            '<table class="table">' +
                            '<tbody>' +
                            '<tr><td>Professional Fees</td><td><span>&#8369</span> '+ numeral(professionalFees).format('0,0.00') +'</td></tr>' +
                            ''+ pfTax +
                            '<tr><td>Total Amount Due</td><td><span>&#8369</span> '+ numeral(grandTotal).format('0,0.00') +'</td></tr>' +
                            '</tbody>' +
                            '</table>' +
                            '');
                    if(grandTotal > 0){
                        $('#create-billing').prop("disabled", false);
                    }
                });
            }

            var billingType = function () {
                var data = '' +
                        '<div class="form-group">' +
                        '<label>Billing Type</label>' +
                        '<select name="" id="billing-type" class="form-control">' +
                        '<option value="">Select Type</option>' +
                        '<option value="special-billing">Special Billing</option>' +
                        '<option value="retainer-contract">Retainer Contract</option>' +
                        '</select>' +
                        '</div>' +
                        '';
                return data;
            };

            var percentageTax = function () {
                var data = '' +
                        '<div class="form-group">' +
                        '<label>Percentage Tax</label>' +
                        '<div class="input-group m-b">' +
                        '<input type="text" value="{!! $billing->percentage_tax !!}" name="percentage-tax" id="percentage-tax" class="form-control numonly">' +
                        '<span class="input-group-addon">%</span>' +
                        '</div>' +
                        '</div><div class="hr-line-dashed"></div>' +
                        '';
                return data;
            };

            var specialBillingFee = function () {
                var data = '' +
                        '<div class="panel-body professional-fee"></div>' + // panel-body professional-fee
                        '';
                return data;
            };

            var professionalFee = function () {
                var data = '<div class="panel-body professional-fee"></div>';
                return data;
            };

            var chargeableFee = function () {
                var data = '<div class="panel-body chargeable-fee"></div>';
                return data;
            };

            var serviceReportDetail = function () {
                var data = '<div class="hr-line-dashed p-xl"></div>' +
                        '<div class="panel panel-default detail">' +
                        '<div class="panel-heading text-center"><strong>Detail of Service Report</strong></div>' +
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
                        '</div>';
                return data;
            };

        });
    </script>
@endsection