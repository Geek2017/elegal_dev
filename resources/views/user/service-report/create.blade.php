@extends('layouts.master')

@section('title', 'Service Report')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create Service Report</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Create Service Report</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                {{--<button type="submit" class="btn btn-primary">Button</button>--}}
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-3">

                <div class="ibox float-e-margins" id="contract-box">
                    <div class="ibox-title">
                        <h5>Client's with active Contracts</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label>Client list</label>
                            <select name="" class="form-control client-select">
                                <option value="">Select client</option>
                                @foreach($clients as $client)
                                    <option value="{!! $client->client_id !!}">{!! $client->firstname !!} {!! $client->lastname !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="fee-info-box"></div>

            </div>
            <div class="col-sm-9" id="sr-box"></div>
        </div>
    </div>

    <div class="modal inmodal fade" id="modal" data-id="0" data-type="" data-srid="0" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary modal-submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/plugins/chosen/chosen.css') !!}
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    {!! Html::script('js/plugins/chosen/chosen.jquery.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}

    {!! Html::script('js/jquery.masknumber.js') !!}
    <script>
        $(document).ready(function(){
            var conBox = $('#contract-box');
            var feeInfoBox = $('#fee-info-box');
            var srBox = $('#sr-box');
            var modal = $('#modal');

            $(document).on('click','.delete-service-report',function(){
                var id = $(this).data('id');
                var feeID = conBox.find('select[name="fee-select"]').val();
                swal({
                    title: 'Are you sure?',
                    text: 'Your will not be able to recover this Service Report',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel pls!'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $.get('{!! route('delete-service-report') !!}',{
                            id: id
                        },function(data){
                            console.log(data);
                            if( (data[2] !== 0) && (data[2].billing_id !== null)){
                                swal("Cancelled", "This Service Report already billed :)", "error");
                            }
                            else if(data[0] > 0){
                                swal("Cancelled", "Delete Chargeable Expenses First :)", "error");
                            }else{
                                console.log('fee-select: '+ data[1]);
                                loadFeeSR(data[1]);
                            }
                        });
                    }
                });
            });

            $(document).on('change','.client-select',function(){
                var clientId = $(this).val();
                switch(clientId){
                    case '':
                        conBox.find('.ibox-content').find('.form-group').slice(1).remove();
                        feeInfoBox.empty();
                        srBox.empty();
                        break;
                    default:
                        conBox.find('.ibox-content').find('.form-group').slice(1).remove();
                        $.get('{!! route('get-client-contract') !!}', {
                            id: $(this).val()
                        },function(data){
                            console.log(data);
                            var contractOptions = new Array();
                            contractOptions.push('<option value="">Select Contract</option>');
                            for(var a = 0; a < data.length; a++){
                                switch(data[a].contract.contract_type) {
                                    case 'special':
                                        var contractCases = new Array();

                                        for(var b = 0; b < data[a].contract.case_details.length; b++){
                                            var regex = /\s+/gi;
                                            var wordCount = data[a].contract.case_details[b].title ? (data[a].contract.case_details[b].title.trim().replace(regex, ' ').split(' ').length > 6) ? data[a].contract.case_details[b].title.split(/\s+/).slice(0,5).join(" ")+ ' ...' : data[a].contract.case_details[b].title : 'No Case Name';

                                            console.log('wordCount: '+ wordCount);
                                            contractCases.push(wordCount);
                                        }
                                        contractOptions.push('<option value="'+ data[a].id +'" data-type="'+ data[a].contract.contract_type +'">'+ data[a].contract.contract_number +' [ '+ contractCases.join(', ') +' ]</option>');
                                        break;
                                    case 'general':
                                        contractOptions.push('<option value="'+ data[a].id +'" data-type="'+ data[a].contract.contract_type +'">'+ data[a].contract.contract_number +' [ '+ data[a].contract.contract_type.toUpperCase() +' Retainer]</option>');
                                        break;
                                }
                            }
                            conBox.find('.ibox-content').append('' +
                                '<div class="form-group">' +
                                '<label>Contract list</label>' +
                                '<select name="contract" class="form-control contract-select">' +
                                ''+ contractOptions.join('') +
                                '</select>' +
                                '</div>' +
                                '');
//                            conBox.find('.ibox-content').find('select[name="contract"]').append('<option value="">Select Contract</option>');
//                            for(var a = 0; a < data.length; a++){
//                                conBox.find('.ibox-content').find('select[name="contract"]')
//                                    .append('<option value="'+ data[a].id +'" data-type="'+ data[a].contract.contract_type +'">'+ data[a].contract.contract_number +' [ '+ data[a].contract.contract_type.toUpperCase() +' Retainer]</option>');
//                            }
                        })
                }

            });

            $(document).on('change','.contract-select',function(){
                feeInfoBox.empty();
                srBox.empty();
                conBox.find('.ibox-content').find('.form-group').slice(2).remove();
                if($(this).val() !== ''){
                    $.get('{!! route('get-contract-info') !!}',{
                        id: $(this).val()
                    },function(data){
//                        console.log(data);
                        switch(data[0]){
                            case 'special':
                                conBox.find('.ibox-content').append('' +
                                    '<div class="form-group">' +
                                    '<label>Case list</label>' +
                                    '<select name="case-select" class="form-control case-select"></select>' +
                                    '</div>' +
                                    '');
                                conBox.find('.ibox-content').find('select[name="case-select"]').append('<option value="">Select Case</option>');
                                for(var a = 0; a < data[1].length; a++){
                                    conBox.find('.ibox-content').find('select[name="case-select"]').append('<option value="'+ data[1][a].id +'">'+ (data[1][a].title ? data[1][a].title : 'No Case Name') +'</option>');

                                    conBox.find('.ibox-content').append('' +
                                        '<div class="form-group" id="case-'+ data[1][a].id +'">' +
                                        '<label>Fee list</label>' +
                                        '<select name="fee-select" class="form-control fee-select"></select>' +
                                        '</div>' +
                                        '');
                                    conBox.find('.ibox-content').find('#case-'+ data[1][a].id).find('select[name="fee-select"]').append('<option value="">Select Fee</option>');
                                    for(var b = 0; b < data[1][a].fees.length; b++){
                                        if(data[1][a].fees[b].special_billing === 0){
                                            var feeDetailCounsel = (data[1][a].fees[b].counsel_id === null) ? '' : ': '+ data[1][a].fees[b].counsel.profile.full_name;
                                            conBox.find('.ibox-content').find('#case-'+ data[1][a].id).find('select[name="fee-select"]')
                                                .append('<option value="'+ data[1][a].fees[b].id +'">'+ data[1][a].fees[b].fee.display_name +' '+ feeDetailCounsel +'</option>');
                                        }
                                    }
                                    conBox.find('.ibox-content').find('#case-'+ data[1][a].id).hide();
                                }
                                break;
                            default:
                                conBox.find('.ibox-content').append('' +
                                    '<div class="form-group">' +
                                    '<label>Fee list</label>' +
                                    '<select name="fee-select" class="form-control fee-select"></select>' +
                                    '</div>' +
                                    '');
                                conBox.find('.ibox-content').find('select[name="fee-select"]').append('<option value="">Select Fee</option>');
                                for(var a = 0; a < data[1].length; a++){
                                    conBox.find('.ibox-content').find('select[name="fee-select"]')
                                        .append('<option value="'+ data[1][a].id +'">'+ data[1][a].fee.display_name +'</option>');
                                }
                        }

                    });
                }

            });

            $(document).on('change','.fee-select',function(){
                feeInfoBox.empty();
                srBox.empty();
                var id = $(this).val();
                if(id !== ''){
                    srBox.append('' +
                        '<div class="ibox float-e-margins">' +
                        '<div class="ibox-title">' +
                        '<h5>Fee Service Report</h5>' +
                        '</div>' +
                        '<div class="ibox-content"></div>' +
                        '</div>' +
                        '');
                    console.log('fee-select: '+ id);
                    loadFeeSR(id);
                    $.get('{!! route('get-fee-info') !!}',{
                        id: id
                    },function(data){
//                        console.log(data);
                        var desc = new Array();
                        var type = '';
                        switch (data.charge_type){
                            case 'amount':
                                type = 'Amount Only';
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.amount).format('0,0.00') +'</h4>' +
                                    '<small>Amount</small>' +
                                    '</div>');
                                break;
                            case 'document':
                                type = 'Document';
                                desc.push('' +
                                    '<div class="data-info">' +
                                    '<h4>'+ data.free_page +'</h4>' +
                                    '<small>No. of free Pages</small>' +
                                    '</div>' +
                                    '');
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.amount).format('0,0.00') +'</h4>' +
                                    '<small>Amount Charge First</small>' +
                                    '</div>');
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.excess_rate).format('0,0.00') +'</h4>' +
                                    '<small>Rate in Excess</small>' +
                                    '</div>');
                                if(data.cap_value > 0){
                                    desc.push('<div class="data-info">' +
                                        '<h4>'+ numeral(data.cap_value).format('0,0.00') +'</h4>' +
                                        '<small>With Cap / Cieling</small>' +
                                        '</div>');
                                }
                                break;
                            case 'time':
                                type = 'Time';
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.amount).format('0,0.00') +'</h4>' +
                                    '<small>Amount</small>' +
                                    '</div>');
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ data.minutes +'</h4>' +
                                    '<small>Consumable / Minute</small>' +
                                    '</div>');
                                break;
                            case 'installment':
                                type = 'Installment';
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.amount).format('0,0.00') +'</h4>' +
                                    '<small>Amount</small>' +
                                    '</div>');
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.installment).format('0,0.00') +'</h4>' +
                                    '<small>Installment</small>' +
                                    '</div>');
                                break;
                            case 'percentage':
                                type = 'Percentage';
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.amount).format('0,0.00') +'</h4>' +
                                    '<small>Amount</small>' +
                                    '</div>');
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ data.percentage +'</h4>' +
                                    '<small>Percentage</small>' +
                                    '</div>');
                                break;
                        }

                        @if(!auth()->user()->can('add-service-report'))
                            toastr.error('Error','You don\'t have permission to add Service report!');
                        @endif

                        var feeDetailCounsel = (data.counsel_id === null) ? '': '<h3>'+ data.counsel.profile.full_name +'</h3>';
                        feeInfoBox.append('' +
                            '<div class="ibox float-e-margins" id="contract-box">' +
                            '<div class="ibox-title">' +
                            '<h5>Fee Details</h5>' +
                            '<div class="ibox-tools">' +
                            @if(auth()->user()->can('add-service-report'))
                            '<button type="button" data-id="'+ data.id +'" data-type="'+ data.charge_type +'" class="btn btn-xs btn-primary" id="modal-open"><i class="fa fa-plus"></i> Create Service Report</button>' +
                            @endif
                            '</div>' +
                            '</div>' +
                            '<div class="ibox-content">' +
                            ''+ feeDetailCounsel +
                            '<h3>'+ data.fee.display_name +'</h3>' +
                            '<small>charge type: '+ type +'</small>' +
                            '<div class="text-right">'+ desc.join('') +'</div>' +
                            '</div>' +
                            '</div>' +
                            '');
                    });
                }
            });

            $(document).on('change','.case-select',function(){
                feeInfoBox.empty();
                srBox.empty();
                conBox.find('.ibox-content').find('.fee-select').val('');
                conBox.find('.ibox-content').find('.form-group').slice(3).hide();
                $('#case-'+ $(this).val()).show();
            });

            $(document).on('click','#modal-open',function(){
                var id = $(this).data('id');
                var srid = $(this).data('srid');
                var modalType = $(this).data('type');
                console.log(modalType);
                switch(modalType){
                    case 'sr-edit':
                        $.get('{!! route('get-fee-sr-info') !!}',{
                            id: id,
                            srid: srid
                        },function(data){
                            console.log(data);
                            var type = data[0].charge_type;
                            var srTotal = 0;
                            modal.data('type',modalType);
                            modal.data('id',id);
                            modal.data('srid',srid);
                            modal.find('.modal-title').text('Edit Service Report');

                            switch (type){
                                case 'amount':
                                    modal.find('.modal-body').empty().append(amountType).end()
                                        .find('.modal-body').find('input[name="total"]').val(numeral(data[1].total).format('0,0.00'));
                                    displaySubTotal(data[1].total);
                                    break;
                                case 'document':
                                    modal.find('.modal-body').empty().append(pageType);
                                    modal.find('input[name="page_count"]').val(data[1].page_count);
                                    displaySubTotal(data[1].page_count);
                                    break;
                                case 'time':
                                    modal.find('.modal-body').empty().append(timeType);
                                    modal.find('input[name="minutes"]').val(data[1].minutes);
                                    displaySubTotal(data[1].minutes);
                                    break;
                                case 'installment':
                                    modal.find('.modal-body').empty().append(amountType).end()
                                            .find('.modal-body').find('input[name="total"]').val(numeral(data[1].total).format('0,0.00'));
                                    displaySubTotal(data[1].total);
                                    break;
                                case 'percentage':
                                    modal.find('.modal-body').empty().append(amountType).end()
                                            .find('.modal-body').find('input[name="total"]').val(numeral(data[1].total).format('0,0.00'));
                                    displaySubTotal(data[1].total);
                                    break;
                            }

                            modal.find('.modal-body').prepend(descInput);

                            if($('.contract-select').find(':selected').data('type') === 'general'){
                                modal.find('.modal-body').prepend(renderedChkBx);
                                $('.i-checks').iCheck({
                                    checkboxClass: 'icheckbox_square-green'
                                });
                            }

                            if(data[0].fee.description.length > 0){
                                modal.find('.modal-body').prepend(descSelect);
                                modal.find('.modal-body').find('.fee_description').append('<option value="">Select Description</option>');
                                for(var a = 0; a < data[0].fee.description.length; a++){
                                    var description = (data[0].fee.description[a].description === null)? data[0].fee.description[a].display_name : data[0].fee.description[a].display_name +': '+ data[0].fee.description[a].description;
                                    var selected = (data[1].fee_description == description) ? 'selected': '';
                                    modal.find('.modal-body').find('.fee_description')
                                        .append('<option value="'+ description +'" '+ selected +'>'+ description +'</option>');
                                }
                            }

                            modal.find('.modal-body').prepend(dateInput);
                            modal.find('input[name="date"]').val(moment(data[1].date).format('l'));

                            $('.input-group.date').datepicker({
                                todayBtn: "linked",
                                keyboardNavigation: false,
                                forceParse: false,
                                calendarWeeks: true,
                                autoclose: true
                            });

                            if(data[1].fas_number !== null){
                                modal.find('input[name="fas_number"]').val(data[1].fas_number);
                            }

                            if(data[1].description !== null){
                                console.log('description: '+ data[1].description);
                                modal.find('.description').val(data[1].description);
                            }

                            modal.modal({backdrop: 'static', keyboard: false});
                        });

                        break;
                    case 'ce':
                        modal.data('type',modalType);
                        modal.data('id',id);
                        modal.find('.modal-title').text('Create Chargeable Expense').end()
                            .find('.modal-body').empty().append(ceSelect);
                        loadCE();
                        break;
                    default:
                        $.get('{!! route('get-fee-info') !!}',{
                            id: id
                        },function(data){
                            console.log(data);
                            var type = data.charge_type;
                            var srTotal = 0;
                            modal.data('type',modalType);
                            modal.data('id',id);
                            modal.find('.modal-title').text('Create Service Report');

                            switch (type){
                                case 'amount':
                                    modal.find('.modal-body').empty().append(amountType).end()
                                        .find('.modal-body').find('input[name="total"]').val(numeral(data.amount).format('0,0.00'));
                                    for(var a = 0; a < data.service_report.length; a++){
                                        srTotal += parseFloat(data.service_report[a].total);
                                    }
                                    break;
                                case 'document':
                                    modal.find('.modal-body').empty().append(pageType);
                                    break;
                                case 'time':
                                    modal.find('.modal-body').empty().append(timeType);
                                    break;
                                case 'installment':
                                    modal.find('.modal-body').empty().append(amountType);
                                    modal.find('.modal-body').find('input[name="total"]').val(data.installment);
                                    for(var a = 0; a < data.service_report.length; a++){
                                        srTotal += parseFloat(data.service_report[a].total);
                                    }
                                    break;
                                case 'percentage':
                                    var amount = (parseFloat(data.percentage) / 100) * parseFloat(data.amount);
                                    modal.find('.modal-body').empty().append(amountType);
//                                    modal.find('.modal-body').find('input[name="total"]').val(amount);
                                    modal.find('.modal-body').find('input[name="total"]').val(numeral(amount).format('0,0.00'));
                                    break;
                            }

//                            if(srTotal >= data.amount){
//                                modal.find('.modal-body').empty().append('<h2 class="text-center">Service Report Already Created!</h2>');
//                                modal.modal({backdrop: 'static', keyboard: false});
//                                return false;
//                            }

                            modal.find('.modal-body').prepend(descInput);

                            if($('.contract-select').find(':selected').data('type') === 'general'){
                                modal.find('.modal-body').prepend(renderedChkBx);
                                $('.i-checks').iCheck({
                                    checkboxClass: 'icheckbox_square-green'
                                });
                            }

                            if(data.fee.description.length > 0){
                                modal.find('.modal-body').prepend(descSelect);
                                modal.find('.modal-body').find('.fee_description').append('<option value="">Select Description</option>');
                                for(var a = 0; a < data.fee.description.length; a++){
                                    var description = (data.fee.description[a].description === null)? '':': '+ data.fee.description[a].description;
                                    modal.find('.modal-body').find('.fee_description')
                                        .append('<option value="'+ data.fee.description[a].display_name +''+ description +'">'+ data.fee.description[a].display_name +''+ description +'</option>');
                                }
                            }

                            modal.find('.modal-body').prepend(dateInput);

                            $('.input-group.date').datepicker({
                                todayBtn: "linked",
                                keyboardNavigation: false,
                                forceParse: false,
                                calendarWeeks: true,
                                autoclose: true
                            });

                            modal.modal({backdrop: 'static', keyboard: false});
                        });
                }
            });

            $(document).on('click','.modal-submit',function(){
                var id = modal.data('id');
                var srid = modal.data('srid');
                var type = modal.data('type');

                console.log('id: '+ id);
                console.log('srid: '+ srid);
                console.log('type: '+ type);

                console.log('return false');
//                modal.modal('toggle');
//                return false;


                switch (type){
                    case 'ce':
                        var total1 = modal.find('input[name="total"]').val()||'0';
                        $.post('{!! route('store-chargeable-expense') !!}',{
                            _token: '{!! csrf_token() !!}',
                            sr_id: id,
                            fee_id: modal.find('select[name="chargeables"]').val()||0,
                            description: modal.find('.ce_description').val()||null,
                            qty: modal.find('.ce_qty').val()||null,
                            total: parseFloat(total1.replace(/,/g, ''))
                        },function(data){
                            console.log(data);
                            console.log('ce: '+ conBox.find('select[name="fee-select"]').val());
                            console.log('ce: '+ data.id);
                            loadFeeSR(data.service_report.fee_detail_id, data.sr_id);
                            modal.modal('toggle');
                        });
                        break;
                    default:
                        var pageCount = modal.find('input[name="page_count"]').val()||'0';
                        var minutes = modal.find('input[name="minutes"]').val()||'0';
                        var total = modal.find('input[name="total"]').val()||'0';

                        $.post('{!! route('store-service-report') !!}',{
                            _token: '{!! csrf_token() !!}',
                            id: id,
                            srid: srid,
                            type: type,
                            fee_description: modal.find('.fee_description').val()||null,
                            description: modal.find('.description').val()||null,
                            fas_number: modal.find('input[name="fas_number"]').val()||null,
                            date: modal.find('input[name="date"]').val()||0,
                            page_count: parseInt(pageCount.replace(/,/g, '')),
                            minutes: parseInt(minutes.replace(/,/g, '')),
                            total: parseFloat(total.replace(/,/g, '')),
                            case_id: $(".case-select").val()||null,
                            counsel_rendered: modal.find('input[name="counsel-rendered"]').is(':checked') ? 1 : 0
                        },function(data){
                            console.log(data);
                            console.log('se: '+ conBox.find('select[name="fee-select"]').val());
                            console.log('se: '+ data.id);
                            loadFeeSR(data.fee_detail_id, data.id);
                            modal.modal('toggle');
                        });
                }


            });

            $(document).on('change','.chargeables',function(){
                modal.find('.modal-body').find('.form-group').slice(1).remove();
                var id = $(this).val();
                if(id !== ''){
                    $.get('{!! route('get-fee-desc') !!}',{
                        id: id
                    },function(data){
                        if(data.length > 0){
                            modal.find('.modal-body').append(ceDescSelect);
                            modal.find('.modal-body').find('.ce_description').append('<option value="">Select Description</option>');
                            for(var a = 0; a < data.length; a++){
                                var description = (data[a].description === null)? '':': '+ data[a].description;
                                modal.find('.modal-body').find('.ce_description')
                                    .append('<option value="'+ data[a].display_name +''+ description +'">'+ data[a].display_name +''+ description +'</option>');
                            }
                            modal.find('.modal-body').append(ceQtyInput).end()
                                    .find('.modal-body').append(amountType);
                        }else{
                            modal.find('.modal-body').append(ceDescInput).end()
                                .find('.modal-body').append(ceQtyInput).end()
                                .find('.modal-body').append(amountType);
                        }
                        $('.separator-dec').maskNumber({decimal: '.', thousands: ','});
                    });
                }
            });

            $(document).on('click','.ce-delete-btn',function(){
                $.get('{!! route('delete-chargeable-expense') !!}',{
                    id: $(this).data('id')
                },function(data){
                    loadFeeSR(conBox.find('select[name="fee-select"]').val(), data);
                });
            });

            $(document).on('keyup','.variable',function(){
                console.log($('.contract-select').find(':selected').data('type'));
//                if(modal.data('type') != 'ce'){
                if( (modal.data('type') != 'ce') && ($('.contract-select').find(':selected').data('type') == 'special') ){
                    var value = $(this).val()||0;
                    var value = value.replace(/,/g, '');
                    delay(function(){
                        displaySubTotal(value);
                    }, 1000 );
                }
            });

            modal.on('shown.bs.modal', function () {
                if(modal.data('type') == 'ce'){
                    modal.find('.modal-body').find('.chargeables').chosen().trigger("chosen:updated");
                }else{
                    modal.find('.modal-body').find('select[name="fee_description"]').chosen().trigger("chosen:updated");
                }
                $('.separator-int').maskNumber({integer: true});
                $('.separator-dec').maskNumber({decimal: '.', thousands: ','});
            });

            // amount = amount only
            // installment = show amount to pay and balance and amount input
            // percentage = show amount to pay and balance and amount input

            var amountType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Amount</label>' +
                    '<input type="text" name="total" class="form-control numonly variable separator-dec">' +
                    '</div>' +
                    '';
                return data;
            };

            // documents = page count only multiply in excess rate
            var pageType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Page Count</label>' +
                    '<input type="text" name="page_count" class="form-control numonly variable separator-int">' +
                    '</div>' +
                    '';
                return data;
            };

            // consumable time = minutes only
            var timeType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Minutes Rendered</label>' +
                    '<input type="text" name="minutes" class="form-control numonly variable separator-int">' +
                    '</div>' +
                    '';
                return data;
            };

            var dateInput = function () {
                var now = '{!! \Carbon\Carbon::now()->format("m/d/Y") !!}';
                var data = '' +
                    '<div class="row">' +
                    '<div class="col-sm-7">' +
                    '<div class="form-group">' +
                    '<label>Date:</label>' +
                    '<div class="input-group m-b date">' +
                    '<input type="text" name="date" value="'+ now +'" class="form-control required">' +
                    '<span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-5">' +
                    '<div class="form-group">' +
                    '<label>FAS No</label>' +
                    '<input type="text" name="fas_number" class="form-control fas-number">' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                return data;
            };

            var descInput = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Description</label>' +
                    '<textarea type="text" name="description" class="form-control description">'+ '</textarea>' +
                    '</div>' +
                    '';
                return data;
            };

            var descSelect = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Fee Description</label>' +
                    '<select name="fee_description" class="form-control fee_description"></select>' +
                    '</div>' +
                    '';
                return data;
            };

            var ceQtyInput = function () {
                var data = '' +
                    '<div class="form-group">' +
                        '<label>Qty/Unit</label>' +
                        '<input type="text" name="ce_qty" class="form-control ce_qty">' +
                    '</div>' +
                    '';
                return data;
            };

            var ceDescInput = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Description</label>' +
                    '<input type="text" name="ce_description" class="form-control ce_description">' +
                    '</div>' +
                    '';
                return data;
            };

            var ceDescSelect = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Description</label>' +
                    '<select name="ce_description" class="form-control ce_description"></select>' +
                    '</div>' +
                    '';
                return data;
            };

            var ceSelect = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Chargeable Expense</label>' +
                    '<select name="chargeables" class="form-control chargeables"></select>' +
                    '</div>' +
                    '';
                return data;
            };

            var renderedChkBx = function () {
                var data = '' +
                    '<div class="form-group m-t-md">' +
                    '<div class="i-checks">' +
                    '<label class="text-success"> ' +
                    '<input type="checkbox" value="" name="counsel-rendered" id="counsel-rendered"> ' +
                    '<i></i> Rendered by Counsel? </label>' +
                    '</div>' +
                    '</div>' +
                    '';
                return data;
            };

            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            function loadFeeSR(fee, sr){
                srBox.find('.ibox-content').empty();
                $.get('{!! route('get-service-report') !!}',{
                    id: fee
                },function(data){
                    console.log(data);
                    if(data.length > 0){
                        srBox.find('.ibox-content').append('' +
                            '<div class="panel-body">' +
                            '<div class="panel-group" id="accordion"></div>' +
                            '</div>' +
                            '');
                        srBox.find('.ibox-content').find('#accordion');

                        for(var a = 0; a < data.length; a++){
//                            console.log('load id: '+sr);
//                            console.log('data id: '+data[a].id);
//                            console.log('loop: '+a);
                            var open;
                            if (sr === undefined){
                                open = (a === 0) ? 'in' : '';
                            }else{
                                open = (data[a].id == sr) ? 'in' : '';
                            }
                            var feeDesc = (data[a].fee_description != null) ? data[a].fee_description : '';
                            var feeDescription = (data[a].description != null) ? ': ('+ data[a].description +')' : '';
                            var addCEButton = (data[a].billing_id === null) ? '<button type="button" class="btn btn-xs btn-success" id="modal-open" data-id="'+ data[a].id +'" data-type="ce">Add CE</button>' : '';
                            var srInfo = (data[a].billing_id === null) ? '': '<span class="text-warning">[ Billed! ]</span>';
                            srBox.find('.ibox-content').find('#accordion').append('' +
                                '<div class="panel panel-default">' +
                                    '<div class="panel-heading">' +
                                        '<h5 class="panel-title">' +
                                            '<a data-toggle="collapse" data-parent="#accordion" href="#'+ data[a].sr_number +'">'+ srInfo +' '+ data[a].sr_number +': '+ data[a].fee_detail.fee.display_name.toUpperCase() +' <small>'+ feeDesc +' '+ feeDescription +'</small></a>' +
                                        '</h5>' +
                                    '</div>' +
                                '<div id="'+ data[a].sr_number +'" class="panel-collapse '+ open +' collapse">' +
                                '<div class="panel-body">' +
                                '<div class="row">' +
                                '<div class="col-lg-4">' +
                                '<div class="panel panel-default">' +
                                '<div class="panel-heading">' +
                                'Service Report Info' +
                                '</div>' +
                                '<div class="panel-body sr-panel-body"></div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-lg-8">' +
                                '<div class="panel panel-default">' +
                                '<div class="panel-heading">' +
                                'Chargeable Expense' +
                                '<div class="ibox-tools pull-right">' +
                                @if(auth()->user()->can('add-chargeable-expense-service-report'))
                                ''+ addCEButton +
                                @endif
                                '</div>' +
                                '</div>' +
                                '<div class="panel-body ce-panel-body"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '');
                            var desc = new Array();
                            desc.push('<div class="data-info">' +
                                '<h4>'+ data[a].sr_number +'</h4>' +
                                '<small>Service Report Number</small>' +
                                '</div>');
                            desc.push('<div class="data-info">' +
                                '<h4>'+ data[a].fee_detail.fee.display_name +'</h4>' +
                                '<h4>'+ feeDesc +' '+ feeDescription +'</h4>' +
                                '<small>Description</small>' +
                                '</div>');
                            switch(data[a].fee_detail.charge_type){
                                case 'document':
                                    desc.push('<div class="data-info">' +
//                                        '<h4>'+ data[a].page_count +'</h4>' +
                                        '<h4>'+ numeral(data[a].page_count).format('0,0') +'</h4>' +
                                        '<small>Page Count</small>' +
                                        '</div>');
                                    break;
                                case 'time':
                                    desc.push('<div class="data-info">' +
                                        '<h4>'+ numeral(data[a].minutes).format('0,0') +'</h4>' +
                                        '<small>Minutes</small>' +
                                        '</div>');
                                    break;
//                                default:
//                                    desc.push('<div class="data-info">' +
//                                        '<h4>'+ numeral(data[a].total).format('0,0.00') +'</h4>' +
//                                        '<small>Amount</small>' +
//                                        '</div>');
                            }
                            desc.push('<div class="data-info">' +
                                '<h4>'+ moment(data[a].date).format('ll') +'</h4>' +
                                '<small>Date</small>' +
                                '</div>');
                            desc.push('<div class="data-info">' +
                                '<h4>'+ numeral(data[a].total).format('0,0.00') +'</h4>' +
                                '<small>Total Amount</small>' +
                                '</div>');
                            console.log('billing_id: '+ data[a].billing_id);
                            console.log('chargeables: '+ data[a].chargeables.length);

                            var deleteBtn = '';
                            if( (data[a].billing_id === null) && (data[a].chargeables.length < 1) ){
                                deleteBtn = '<button type="button" class="btn-white btn btn-xs delete-service-report" data-id="'+ data[a].id +'"><i class="fa fa-times text-danger"></i> Delete</button>';
                            }

                            desc.push('' +
                                '<div class="btn-group text-right">' +
                                    '<button type="button" class="btn-white btn btn-xs" id="modal-open" data-type="sr-edit" data-srid="'+ data[a].id +'" data-id="'+ data[a].fee_detail_id +'"><i class="fa fa-pencil text-warning"></i> Edit</button>' +
                                    deleteBtn +
                                '</div>' +
                            '');

                            srBox.find('.ibox-content').find('#accordion').find('#'+ data[a].sr_number).find('.sr-panel-body').append(desc);
                            console.log(data[a].chargeables.length);

                            if(data[a].chargeables.length > 0){
                                var cogs = (data[a].billing_id === null) ? '<th class="text-center" style="width: 30px;"><i class="fa fa-cogs"></i></th>' : '';
                                srBox.find('.ibox-content').find('#accordion').find('#'+ data[a].sr_number).find('.ce-panel-body').append('' +
                                    '<table class="table table-striped">' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th>Description</th>' +
                                    '<th class="text-right">Qty</th>' +
                                    '<th class="text-right">Amount</th>' +
                                    ''+ cogs +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody></tbody>' +
                                    '</table>' +
                                    '');
                                for(var b = 0; b < data[a].chargeables.length; b++){
                                    var ceDel = (data[a].billing_id === null) ? '<button type="button" data-id="'+ data[a].chargeables[b].id +'" class="ce-delete-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> </button>' : '';
                                    srBox.find('.ibox-content').find('#accordion').find('#'+ data[a].sr_number).find('.ce-panel-body').find('tbody').append('' +
                                        '<tr>' +
                                        '<td>'+ data[a].chargeables[b].fee.display_name +': '+ data[a].chargeables[b].description +'</td>' +
                                        '<td class="text-right">'+ data[a].chargeables[b].qty +'</td>' +
                                        '<td class="text-right">'+ numeral(data[a].chargeables[b].total).format('0,0.00') +'</td>' +
                                        '<td>' +
                                        '<div class="btn-group text-right">' +
                                        @if(auth()->user()->can('delete-chargeable-expense-service-report'))
                                        ''+ ceDel +
                                        @endif
                                        '</div>' +
                                        '</td>' +
                                        '</tr>' +
                                    '');
                                }
                            }
                        }
                    } // data.length
                });
            }

            function loadCE(){
                $.get('{!! route('fees') !!}',{
                    type: 'chargeable-expense'
                },function(data){
                    modal.find('.modal-body').find('.chargeables').append('<option value="">Select C.E.</option>');
                    for(var a = 0; a < data.length; a++){
                        modal.find('.modal-body').find('.chargeables').append('<option value="'+ data[a].id +'">'+ data[a].display_name +'</option>');
                    }
                    modal.modal({backdrop: 'static', keyboard: false});
                });
            }

            function displaySubTotal(value){
                $.get('{!! route('get-fee-info') !!}',{
                    id: modal.data('id')
                },function(data){
                    console.log(data);
                    modal.find('.modal-body').find('.data-info').remove();
                    var desc = new Array();
                    var totalAmount;
                    switch(data.charge_type){
                        case 'time':

                            var perMinute = parseFloat(data.amount) / parseFloat(data.minutes);
                            totalAmount = parseFloat(value) * parseFloat(perMinute);
                            desc.push('<div class="data-info">' +
                                '<h4>'+ numeral(data.amount).format('0,0.00') +'</h4>' +
                                '<small>Amount</small>' +
                                '</div>');
                            desc.push('<div class="data-info">' +
                                '<h4>'+ data.minutes +'</h4>' +
                                '<small>Consumable / Minute</small>' +
                                '</div>');
                            desc.push('<div class="data-info">' +
                                '<h4>'+ numeral(perMinute).format('0,0.00') +'</h4>' +
                                '<small>Per / Minute</small>' +
                                '</div>');
                            desc.push('<div class="data-info">' +
                                '<h4 class="text-danger">'+ numeral(totalAmount).format('0,0.00') +'</h4>' +
                                '<small>Total Amount</small>' +
                                '</div>');
                            break;
                        case 'document':
                            if(value > data.free_page){
                                value = parseInt(value) - parseInt(data.free_page);
                                totalAmount = parseFloat(data.amount);
                                totalAmount += value * parseFloat(data.excess_rate);
                                desc.push('<div class="data-info">' +
                                    '<h4>'+ numeral(data.excess_rate).format('0,0.00') +'</h4>' +
                                    '<small>Rate in Excess / Page</small>' +
                                    '</div>');

                                if((data.cap_value > 0) && (totalAmount > data.cap_value)){
                                    desc.push('<div class="data-info">' +
                                        '<h4>'+ numeral(totalAmount).format('0,0.00') +'</h4>' +
                                        '<small>Actual Price</small>' +
                                        '</div>');
                                    totalAmount = data.cap_value;
                                    desc.push('<div class="data-info">' +
                                        '<h4>'+ numeral(data.cap_value).format('0,0.00') +'</h4>' +
                                        '<small>Cap / Cieling</small>' +
                                        '</div>');
                                }

                            }else{
                                totalAmount = parseFloat(data.amount);
                            }

                            desc.push('<div class="data-info">' +
                                '<h4 class="text-danger">'+ numeral(totalAmount).format('0,0.00') +'</h4>' +
                                '<small>Total Amount</small>' +
                                '</div>');
                            break;
                        default:

                    }
                    modal.find('.modal-body').append(desc.join(''));
                });
            }

        });
    </script>
@endsection