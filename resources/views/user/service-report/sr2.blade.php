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
                <button type="submit" class="btn btn-primary">Button</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Client's with active Contracts</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label>Client list</label>
                            <select name="" class="form-control client-select">
                                <option value="">Select client</option>
                                @foreach($clients as $client)
                                    <option value="{!! $client->id !!}">{!! $client->profile->firstname !!} {!! $client->profile->lastname !!}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="contract-box"></div>
                        <div id="case-box"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8" id="sr-box">
                {{--<div class="ibox float-e-margins">--}}
                    {{--<div class="ibox-title">--}}
                        {{--<h5>Service Report's</h5>--}}
                    {{--</div>--}}
                    {{--<div class="bg-muted">--}}

                        {{--<div class="tabs-container">--}}

                            {{--<div class="tabs-left">--}}
                                {{--<ul class="nav nav-tabs">--}}
                                    {{--<li class="active"><a data-toggle="tab" href="#tab-6"> This is tab</a></li>--}}
                                    {{--<li class=""><a data-toggle="tab" href="#tab-7">This is second tab</a></li>--}}
                                {{--</ul>--}}
                                {{--<div class="tab-content ">--}}
                                    {{--<div id="tab-6" class="tab-pane active">--}}
                                        {{--<div class="panel-body">--}}
                                            {{--<div class="row">--}}
                                                {{--<div class="col-sm-6">--}}
                                                    {{--<div class="ibox float-e-margins">--}}
                                                        {{--<div class="ibox-title">--}}
                                                            {{--<h5>Blank<small>page</small></h5>--}}
                                                        {{--</div>--}}
                                                        {{--<div class="ibox-content">--}}

                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div id="tab-7" class="tab-pane">--}}
                                        {{--<div class="panel-body">--}}
                                            {{--<strong>Donec quam felis</strong>--}}

                                            {{--<p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects--}}
                                                {{--and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>--}}

                                            {{--<p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite--}}
                                                {{--sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                            {{--</div>--}}

                        {{--</div>--}}

                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" id="fee-box"></div>
        </div>
    </div>

    <div class="modal inmodal fade" id="fee-modal" data-id="0" data-action="add" data-type="" data-counsel="" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Fee</label>
                                <select name="fee" id="fee-select" class="form-control"></select>
                            </div>
                            <div class="form-group" id="fee-desc-box" style="display: none;">
                                <label>Select description</label>
                                <select name="fee-desc" id="fee-desc" class="form-control"></select>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label>Charge Type:</label>
                                <select name="charge-type" id="charge-type" class="form-control">
                                    <option value="">Select type</option>
                                    <option value="standard">Standard</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div id="charge-list"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary modal-submit" id="btn-store-fee">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="sr-modal" data-id="0" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Service Report</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date Rendered:</label>
                        <div class="input-group m-b date">
                            <input type="text" name="date" value="{!! \Carbon\Carbon::now()->format('m/d/Y') !!}" class="form-control required">
                            <span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Minute's Rendered:</label>
                        <input type="text" name="minutes" value="0" class="form-control numonly required">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary sr-btn-store">Save changes</button>
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
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    <script>
        $(document).ready(function(){
            var conBox = $('#contract-box');
            var caseBox = $('#case-box');
            var feeBox = $('#fee-box');
            var feeModal = $('#fee-modal');
            var srModal = $('#sr-modal');
            var srBox = $('#sr-box');
            $('.client-select').chosen();

            $(document).on('click','.add-fee',function(){
                var type = $(this).data('type');
                feeModal.find('.modal-title').text(type.toUpperCase() +' Fee\'s');
                feeModal.data('id',$(this).data('id'));
                feeModal.data('counsel',$(this).data('counsel'));
                feeModal.data('type',type);
                $.get('{!! route('fees') !!}',{
                    type: type
                },function(data){
//                    console.log(data);
                    if(data.length != 0){
//                        feeModal.find('.fee-list').empty();
                        feeModal.find('#fee-select').empty().append('<option value="">Select Fee</option>');
                        for(var a = 0; a < data.length; a++){
                            feeModal.find('#fee-select').append('<option value="'+ data[a].id +'">'+ data[a].display_name +'</option>');
                        }
                        feeModal.modal({backdrop: 'static', keyboard: false});
                    }
                });
            });

            $(document).on('change','#fee-select',function(){
                var box = $('#fee-desc-box');
                var select = feeModal.find('select[name="fee-desc"]');
                var type = feeModal.find('select[name="charge-type"]').closest('.form-group');
                var list = $('#charge-list');
                $.get('{!! route('get-fee-desc') !!}',{
                    id: $(this).val()
                },function(data){
                    if(data.length != 0){
//                        console.log(data);
                        select.empty();
                        select.append('<option value="">Select Description</option>');
                        for(var a = 0; a < data.length; a++){
                            var desc = (data[a].description != null) ? '[ '+ data[a].description +' ]' : '';
                            select.append('<option value="'+ data[a].id +'">'+ data[a].display_name +' '+ desc +'</option>');
                        }
                        box.show("fast", function(){
                            select.chosen();
                        });
                        type.hide();
                        list.empty();
                    }else{
                        box.hide();
                        type.show();
                    }
                });
            });

            $(document).on('change','#fee-desc',function(){
                feeModal.find('select[name="charge-type"]').closest('.form-group').show();
            });

            $(document).on('change','#charge-type',function(){
                var value = $(this).val();
                var list = $('#charge-list');
                var amount = '<div class="form-group">' +
                    '<label>Fixed Amount</label>' +
                    '<input type="text" name="amount" placeholder="0.00" class="form-control numonly">' +
                    '</div>';
                var rate = '<div class="form-group">' +
                    '<label>Rate No. 1:</label>' +
                    '<input type="text" name="rate_1" data-var="rate_1" placeholder="0.00" class="form-control numonly variable">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Rate No. 2:</label>' +
                    '<input type="text" name="rate_2" data-var="rate_2" placeholder="0.00" class="form-control numonly variable">' +
                    '</div>';
                var cons = '<div class="form-group">' +
                    '<label>Consumable | Min</label>' +
                    '<input type="text" name="consumable_time" data-var="consumable_time" placeholder="0" class="form-control numonly variable">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Excess Rate</label>' +
                    '<input type="text" name="excess_rate" data-var="excess_rate" placeholder="0.00" class="form-control numonly">' +
                    '</div>';
                var cap = '<div class="form-group">' +
                    '<label>W/ CAP or Ceiling</label>' +
                    '<input type="text" name="cap_value" data-var="cap_value" placeholder="0.00" class="form-control numonly variable">' +
                    '</div>';
                var fix = '<div class="form-group">' +
                    '<label>Fixed Rate</label>' +
                    '<input type="text" name="rate" data-var="rate" placeholder="0.00" class="form-control numonly">' +
                    '</div>';
                var docs = '<div class="form-group">' +
                    '<label>Total Pages:</label>' +
                    '<input type="text" name="free_page" data-var="free_page" placeholder="0" class="form-control numonly variable">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>First 5 Pages Amount:</label>' +
                    '<input type="text" name="charge_doc" data-var="charge_doc" placeholder="0.00" class="form-control numonly variable">' +
                    '</div>';
                var total = '<div class="hr-line-dashed"></div>' +
                    '<div class="form-group">' +
                    '<div class="input-group m-b total-modal">' +
                    '<span class="input-group-addon bg-muted">Total:</span>' +
                    '<label class="form-control text-success text-right total-amount">0.00</label>' +
                    '</div>' +
                    '</div>';
                var counsel = '<div class="form-group">' +
                    '<label>Co-Counsel:</label>' +
                    '<input type="text" name="counsel" value="'+ feeModal.data('counsel') +'" class="form-control numonly variable" readonly>' +
                    '</div>';
                switch(value){
                    case 'amount':
                        list.empty();
                        list.append(amount);
                        list.find('input[name="amount"]').focus();
                        break;
                    case 'standard':
                        list.empty();
                        list.append(rate);
                        list.append(cons);
                        list.append(counsel);
                        list.append(total);
                        list.find('input[name="rate_1"]').focus();
                        break;
                    case 'fixed':
                        list.empty();
                        list.append(rate);
                        list.append(fix);
                        list.append(counsel);
                        list.append(total);
                        list.find('input[name="rate_1"]').focus();
                        break;
                    case 'document':
                        list.empty();
                        list.append(docs);
                        list.append(cap);
                        list.append(total);
                        list.find('input[name="free_page"]').focus();
                        break;
                    default:
                        list.empty();
                }
            });

            $(document).on('keyup','.variable',function(){
                console.log($(this).val());
                var data = $(this).data('var');
                var list = $('#charge-list');
                var type = $('#charge-type').val();
                var val = parseFloat($(this).val());
                var total = 0;
                switch(type){
                    case 'standard':
                        switch(data){
                            case 'rate_1':
                                total += val * parseFloat(list.find('input[name="consumable_time"]').val()||0);
                                total += parseFloat(list.find('input[name="rate_2"]').val()||0) * parseFloat(list.find('input[name="counsel"]').val()||0);
                                break;
                            case 'rate_2':
                                total += val * parseFloat(list.find('input[name="counsel"]').val()||0);
                                total += parseFloat(list.find('input[name="rate_1"]').val()||0) * parseFloat(list.find('input[name="consumable_time"]').val()||0);
                                break;
                            case 'consumable_time':
                                total += val * parseFloat(list.find('input[name="rate_1"]').val()||0);
                                total += parseFloat(list.find('input[name="rate_2"]').val()||0) * parseFloat(list.find('input[name="counsel"]').val()||0);
                                break;
                        }
                        break;
                    case 'fixed':
                        switch(data){
                            case 'rate_1':
                                total += val;
                                total += parseFloat(list.find('input[name="rate_2"]').val()||0) * parseFloat(list.find('input[name="counsel"]').val()||0);
                                break;
                            case 'rate_2':
                                total += val * parseFloat(list.find('input[name="counsel"]').val()||0);
                                total += parseFloat(list.find('input[name="rate_1"]').val()||0);
                                break;
                        }
                        break;
                    case 'document':
                        switch(data){
                            case 'free_page':
                                if(val >= 6){
                                    total += (parseFloat(list.find('input[name="charge_doc"]').val()||0) / 5) * val;
                                }else{
                                    total += parseFloat(list.find('input[name="charge_doc"]').val()||0);
                                }
                                if((list.find('input[name="cap_value"]').val()||0 > list.find('input[name="charge_doc"]').val()||0) && (total > list.find('input[name="cap_value"]').val()||0)){
                                    total = list.find('input[name="cap_value"]').val()||0;
                                }
                                break;
                            case 'charge_doc':
                                if(list.find('input[name="free_page"]').val()||0 >= 6){
                                    total += (val / 5) * parseFloat(list.find('input[name="free_page"]').val()||0);
                                }else{
                                    total += val;
                                }
                                if((list.find('input[name="cap_value"]').val()||0 > list.find('input[name="charge_doc"]').val()||0) && (total > list.find('input[name="cap_value"]').val()||0)){
                                    total = list.find('input[name="cap_value"]').val()||0;
                                }
                                break;
                            case 'cap_value':

                                if(list.find('input[name="free_page"]').val()||0 >= 6){
                                    total += (parseFloat(list.find('input[name="charge_doc"]').val()||0) / 5) * parseFloat(list.find('input[name="free_page"]').val()||0);
                                }else{
                                    total += parseFloat(list.find('input[name="charge_doc"]').val()||0);
                                }
                                if((val > list.find('input[name="charge_doc"]').val()||0) && (total > val)){
                                    total = val;
                                }
                                break;
                        }
                        break;
                }
                list.find('.total-amount').text(numeral(total).format('0,0.00'));
            });

            $(document).on('click','#btn-store-fee',function(){
                var type = feeModal.data('type');
//                console.log(type);
//                console.log(feeModal.data('id'));
                $.post('{!! route('store-fee') !!}',{
                    _token: '{!! csrf_token() !!}',
                    transaction_id: tran_id,
                    fee_id: feeModal.find('select[name="fee"]').val(),
                    fee_desc_id: feeModal.find('select[name="fee-desc"]').val(),
                    case_id: feeModal.data('id'),
                    action: feeModal.data('action'),
                    charge_type: feeModal.find('select[name="charge-type"]').val(),
                    status: feeModal.find('select[name="status"]').val(),
                    free_page: parseInt(feeModal.find('input[name="free_page"]').val()||0),
                    charge_doc: parseFloat(feeModal.find('input[name="charge_doc"]').val()||0),
                    rate_1: parseFloat(feeModal.find('input[name="rate_1"]').val()||0),
                    rate_2: parseFloat(feeModal.find('input[name="rate_2"]').val()||0),
                    rate: parseFloat(feeModal.find('input[name="rate"]').val()||0),
                    consumable_time: parseInt(feeModal.find('input[name="consumable_time"]').val()||0),
                    excess_rate: parseFloat(feeModal.find('input[name="excess_rate"]').val()||0),
                    amount: parseFloat(feeModal.find('input[name="amount"]').val()||0),
                    cap_value: parseFloat(feeModal.find('input[name="cap_value"]').val()||0)
                },function(data){
                    if(type == 'special'){
                        getSpecialFees();
                    }
                    if(data.length != 0){
                        if(type == 'general'){
                            loadCases(feeModal.data('id'));
                        }
                    }
                    grandTotal();
                    feeModal.modal('toggle');
                });
            });






            $(document).on('change','.client-select',function(){
                conBox.empty();
                $.get('{!! route('get-client-contract') !!}', {
                    id: $(this).val()
                },function(data){
//                    console.log(data);
                    if(data.length != 0){
                        conBox.append(' <div class="form-group"><label>Contract list</label></div>');
                        for(var a = 0; a < data.length; a++){
                            conBox.find('.form-group').append('<div class="i-checks"><label> <input type="radio" value="'+ data[a].id +'" name="contract" class="contract-radio"> <i></i> '+ data[a].contract.contract_number +' </label></div>');
                        }
                    }else{
                        feeBox.empty();
                        caseBox.empty();
                    }
                })
            });

            $(document).on('click','.contract-radio',function(){
                caseBox.empty();
                console.log($(this).val());
                $.get('{!! route('get-contract-case') !!}', {
                    id: $(this).val()
                },function(data){
//                    console.log(data);
                    if(data.length != 0){
                        caseBox.append(' <div class="form-group"><label>Case list</label></div>');
                        for(var a = 0; a < data.length; a++){
                            caseBox.find('.form-group').append('<div class="i-checks"><label> <input type="radio" value="'+ data[a].id +'" name="case" class="case-radio"> <i></i> '+ data[a].title +' </label></div>');
                        }
                    }
                })
            });

            $(document).on('click','.case-radio',function(){
                loadCaseFee($(this).val());
                loadSR($(this).val());
            });

            $(document).on('click','.sr-btn',function(){
                srModal.data('id',$(this).data('id'));
                srModal.modal({backdrop: 'static', keyboard: false});
            });

            $(document).on('click','.sr-btn-store',function(){
                $.post('{!! route('store-service-report') !!}',{
                    _token: '{!! csrf_token() !!}',
                    fee_detail_id: srModal.data('id'),
                    date: srModal.find('input[name="date"]').val(),
                    minutes: srModal.find('input[name="minutes"]').val(),
                },function(data){
                    if(data[0] == 'excess'){
                        loadCaseFee(data[1]);
                    }
                    srModal.modal('toggle');
                    loadSR(data[1]);
                });
            });

            function loadCaseFee(case_id){
                console.log(case_id);

                $.get('{!! route('get-case-fee') !!}',{
                    id: case_id
                },function(data){
                    console.log(data);

                    feeBox.empty().append('' +
                        '<div class="ibox float-e-margins">' +
                        '<div class="ibox-title">' +
                        '<h5>Case Fee Details</h5>' +
                        '<div class="ibox-tools pull-right">' +
                        '<button type="button" class="btn btn-xs btn-success add-fee" data-id="'+ case_id +'" data-counsel="'+ data[1] +'" data-type="general">Add Fee</button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="ibox-content">' +
                        '<table class="table table-striped">' +
                        '<thead>' +
                        '<tr>' +
                        '<th>Name</th>' +
                        '<th class="text-right">Rate</th>' +
                        '<th class="text-right">Minutes</th>' +
                        '<th class="text-right">Total</th>' +
                        '<th class="text-right" style="width: 62px;"><i class="fa fa-cogs"></i></th>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody></tbody>' +
                        '</table>' +
                        '</div>' +
                        '</div>' +
                    '');

                    if(data[0].length != 0){
                        for(var a = 0; a < data[0].length; a++){
                            feeBox.find('tbody').append('' +
                                '<tr>' +
                                    '<td>'+ data[0][a].fee.display_name +'</td>' +
                                    '<td class="text-right">'+ numeral(data[0][a].rate_1).format('0,0.00') +'</td>' +
                                    '<td class="text-right">'+ data[0][a].consumable_time +'</td>' +
                                    '<td class="text-right">'+ numeral(data[0][a].total).format('0,0.00') +'</td>' +
                                    '<td>' +
                                        '<div class="btn-group text-right">' +
                                            '<button type="button" data-id="'+ data[0][a].id +'" data-min="'+ data[0][a].consumable_time +'" class="sr-btn btn-white btn btn-xs"><i class="fa fa-plus text-success"></i> S.R.</button>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>' +
                            '');
                        }
                    }
                });
            }

            function loadSR(case_id){
                srBox.empty();
                srBox.append('' +
                    '<div class="ibox float-e-margins">' +
                        '<div class="ibox-title">' +
                            '<h5>Service Report</h5>' +
                        '</div>' +
                        '<div class="bg-muted">' +
                            '<div class="tabs-container">' +
                                '<div class="tabs-left">' +
                                    '<ul class="nav nav-tabs"></ul>' +
                                    '<div class="tab-content"></div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '');

                $.get('{!! route('get-service-report') !!}', {
                    case_id: case_id
                },function(data){
                    console.log(data);
                    if(data.length != 0){
                        for(var a = 0; a < data.length; a++){
                            var active = (a === 0) ? 'active' : '';
                            srBox.find('.nav-tabs').append('<li class="'+ active +'"><a data-toggle="tab" href="#'+ data[a].sr_number +'"> '+ data[a].sr_number +'</a></li>');
                            srBox.find('.tab-content').append('' +
                                '<div id="'+ data[a].sr_number +'" class="tab-pane '+ active +'">' +
                                    '<div class="panel-body">' +
                                        '<div class="container">' +
                                        '<div class="row">' +
                                            '<div class="col-sm-12">' +
                                                '<div class="ibox float-e-margins">' +
                                                    '<div class="ibox-title">' +
                                                    '<h5>Information</h5>' +
                                                    '</div>' +
                                                    '<div class="ibox-content">' +
                                                        '<div class="data-info">' +
                                                            '<h4>'+ data[a].sr_number +'</h4>' +
                                                            '<small>S.R. Number</small>' +
                                                        '</div>' +
                                                        '<div class="data-info">' +
                                                            '<h4>'+ data[a].date +'</h4>' +
                                                            '<small>Date Rendered</small>' +
                                                        '</div>' +
                                                        '<div class="data-info">' +
                                                            '<h4>'+ data[a].minutes +'</h4>' +
                                                            '<small>Minutes Rendered</small>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +

//                                            '<div class="col-sm-7">' +
//                                                '<div class="ibox float-e-margins">' +
//                                                    '<div class="ibox-title">' +
//                                                        '<h5>Chargeable Expenses</h5>' +
//                                                    '</div>' +
//                                                    '<div class="ibox-content"></div>' +
//                                                '</div>' +
//                                            '</div>' +

                                        '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '');
                        }
                    }
                });

            }

            feeModal.on('hide.bs.modal', function () {
                $('#charge-list').empty();
                $('#fee-desc-box').hide();
                $('#charge-type').val('');
                $('#charge-type').closest('.form-group').hide();
            });

            $('.input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

        });
    </script>
@endsection