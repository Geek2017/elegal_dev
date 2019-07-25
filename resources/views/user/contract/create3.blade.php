@extends('layouts.master')

@section('title', 'Create Contract')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Define Contract</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Define Contract</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="button" id="save-contract-btn" data-action="{!! ($data->status === 'Ongoing') ? 'edit' : 'add' !!}" class="btn btn-primary">{!! ($data->status === 'Ongoing') ? 'Update Contract' : 'Save Contract' !!}</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">

            <div class="col-sm-4">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title bg-success">
                                <h5>Client Information</h5>
                            </div>
                            <div class="ibox-content">
                                <h3 class="m-b-xs"><strong>{!! $data->client->profile->firstname !!} {!! $data->client->profile->lastname !!}</strong></h3>
                                <div class="font-bold">{!! $data->client->count !!}</div>
                                <address class="m-t-md">
                                    <strong>{!! $data->client->business->address->description !!}</strong><br>
                                    @if($data->client->business->telephone)
                                    <abbr title="Phone">P:</abbr> {!! $data->client->business->telephone->description !!}<br>
                                    @endif
                                    @if($data->client->business->mobile)
                                    <abbr title="Phone">M:</abbr> {!! $data->client->business->mobile->description !!}
                                    @endif
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="contract-info">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title bg-success">
                                <h5>Contract Information</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="form-group">
                                    <div class="input-group m-b total-box">
                                        <span class="input-group-addon bg-muted">Grand Total:</span>
                                        <label class="form-control text-success" id="contract-total">0.00</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Fixed Price:</label>
                                    <input type="text" name="fixed_price" value="0" class="form-control numonly">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Contract Date:</label>
                                            <div class="input-group m-b date">
                                                <input type="text" name="contract_date" value="{!! ($data->status === 'Ongoing') ? \Carbon\Carbon::parse($data->contract->contract_date)->format('m/d/Y') : '' !!}" class="form-control required">
                                                <span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Start Date:</label>
                                            <div class="input-group date">
                                                <input type="text" name="start_date" value="{!! ($data->status === 'Ongoing') ? \Carbon\Carbon::parse($data->contract->start_date)->format('m/d/Y') : '' !!}" class="form-control required">
                                                <span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Other Condition:</label>
                                    <textarea name="other_conditions" class="form-control resize-vertical">{!! ($data->status === 'Ongoing') ? $data->contract->other_conditions : '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title bg-success">
                                <h5>Special Fee's</h5>
                                <div class="ibox-tools">
                                    <button type="button" data-type="special" class="btn btn-xs btn-primary add-fee"><i class="fa fa-plus"></i> Add</button>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <table class="table table-striped" id="table-special-fee">
                                    <thead>
                                    <tr>
                                        <th>Fee Detail</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right" style="width: 30px;"><i class="fa fa-cogs"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <table class="table invoice-total">
                                <tbody>
                                <tr>
                                    <td><strong>TOTAL :</strong></td>
                                    <td><h2 class="text-success fees-total">00.00</h2></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-sm-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title bg-success">
                        <h5>Case Information</h5>
                        <div class="ibox-tools case-box-btn">
                            <button type="button" data-type="add" class="case-action-btn btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" data-type="edit" class="case-action-btn btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</button>
                            <button type="button" data-type="delete" class="case-action-btn btn btn-xs btn-primary"><i class="fa fa-times"></i> Delete</button>
                        </div>
                    </div>
                    <div class="bg-muted">

                        <div class="tabs-container">
                            <ul class="nav nav-tabs"></ul>
                            <div class="tab-content"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal inmodal fade" id="case-modal" data-id="0" data-action="add" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Case Management & Def.</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Case Title:</span>
                                    <input type="text" name="title" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="textarea-group">
                                    <span class="textarea-group-addon bg-muted">Venue:</span>
                                    <textarea name="venue" id="" class="form-control resize-vertical required"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Case No:</span>
                                    <input type="text" name="number" class="form-control required">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b date">
                                    <span class="input-group-addon bg-muted">Case Date:</span>
                                    <input type="text" name="date" class="form-control required">
                                    <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon bg-muted">Case Classification:</span>
                                    <div class="input-group-btn input-group-select">
                                        <select name="class" class="form-control required">
                                            <option value="">Select Status</option>
                                            <option value="Administrative">Administrative</option>
                                            <option value="Criminal">Criminal</option>
                                            <option value="Civil">Civil</option>
                                            <option value="Collection Retainer">Collection Retainer</option>
                                            <option value="General Retainer">General Retainer</option>
                                            <option value="Labor">Labor</option>
                                            <option value="Special Project">Special Project</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Lead Counsel:</span>
                                    <div class="input-group-btn input-group-select">
                                        <select name="lead-counsel" class="form-control counsel-select required"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon bg-muted">Co-Counsel:</span>
                                    <div class="input-group-btn input-group-select">
                                        <select name="select-counsel" class="form-control counsel-select"></select>
                                    </div>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" id="add-co-counsel-btn">Add</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default table-box">
                                <div class="panel-heading">
                                    <label>Co-Counsel List</label>
                                </div>
                                <table id="co-counsel-table" class="table table-stripped">
                                    <thead>
                                    <tr>
                                        <th>Counsel Code.</th>
                                        <th>Name of Counsel</th>
                                        <th>Lawyer Forte</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary modal-submit" id="btn-store-case">Save changes</button>
                </div>
            </div>
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
                                    <option value="amount">Amount Only</option>
                                    <option value="standard">Standard</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="document">Document</option>
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

@endsection


@section('styles')
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/plugins/chosen/chosen.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    {!! Html::script('js/plugins/chosen/chosen.jquery.js') !!}
    <script>
        $(document).ready(function(){
            var caseModal = $('#case-modal');
            var feeModal = $('#fee-modal');
            var tran_id = '{!! $data->id !!}';
            loadCases();
            getSpecialFees();
            grandTotal();

            $('.input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

            $(document).on('click','.add-fee',function(){
                var type = $(this).data('type');
                var chargeType = $('#charge-type');
                switch (type){
                    case 'special':
                        chargeType.empty().append('' +
                            '<option value="">Select type</option>' +
                            '<option value="amount">Amount Only</option>' +
                            '<option value="document">Document</option>' +
                        '');
                        break;
                    case 'general':
                        chargeType.empty().append('' +
                            '<option value="">Select type</option>' +
                            '<option value="amount">Amount Only</option>' +
                            '<option value="standard">Standard</option>' +
                            '<option value="fixed">Fixed</option>' +
                            '<option value="document">Document</option>' +
                            '');
                        break;
                }
                feeModal.find('.modal-title').text(type.toUpperCase() +' Fee\'s');
                feeModal.data('id',$(this).data('id'));
                feeModal.data('counsel',$(this).data('counsel'));
                feeModal.data('type',type);
                $.get('{!! route('fees') !!}',{
                    type: type
                },function(data){
                    if(data.length != 0){
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
                            var price = (data[a].default_amount > 0) ? ': '+ data[a].default_amount : '';
                            select.append('<option value="'+ data[a].id +'" data-value="'+ data[a].default_amount +'">'+ data[a].display_name +' '+ desc +' '+ price +'</option>');
                        }
                        box.show("fast", function(){
                            select.chosen();
                        });
                        type.hide();
                        list.empty();
                        select.trigger('chosen:updated');
                    }
                    if(data.length < 1){
                        box.hide();
                        type.show();
                        type.find('select').val();
                        select.empty();
                        list.empty();
                    }
                });
            });

            $(document).on('change','#fee-desc',function(){
                $('#charge-list').empty();
                feeModal.find('select[name="charge-type"]').val('');
                feeModal.find('select[name="charge-type"]').closest('.form-group').show();
            });

            $(document).on('change','#charge-type',function(){
                var value = $(this).val();
                var list = $('#charge-list');
                var amount = '<div class="form-group">' +
                    '<label>Amount</label>' +
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
                    '<input type="text" name="excess_rate" data-var="excess_rate" placeholder="0.00" class="form-control numonly" readonly>' +
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
                        if (($('#fee-desc')[0]) && (parseFloat(feeModal.find('select[name="fee-desc"]').find(':selected').data('value')) > 0)){
                            list.find('input[name="amount"]').val(feeModal.find('select[name="fee-desc"]').find(':selected').data('value'))
                        }
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
                                    total += val;
                                    total += parseFloat(list.find('input[name="rate_2"]').val()||0) * parseFloat(list.find('input[name="counsel"]').val()||0);
                                    if(parseFloat(list.find('input[name="consumable_time"]').val()||0) > 1){
                                        list.find('input[name="excess_rate"]').val(numeral(val / parseFloat(list.find('input[name="consumable_time"]').val()||0)).format('0,0.00'));
                                    }

                                    break;
                                case 'rate_2':
                                    total += val * parseFloat(list.find('input[name="counsel"]').val()||0);
                                    total += parseFloat(list.find('input[name="rate_1"]').val()||0);
                                    if((parseFloat(list.find('input[name="consumable_time"]').val()||0) > 1) && (parseFloat(list.find('input[name="rate_1"]').val()||0) > 1)){
                                        list.find('input[name="excess_rate"]').val(numeral(parseFloat(list.find('input[name="rate_1"]').val()||0) / parseFloat(list.find('input[name="consumable_time"]').val()||0)).format('0,0.00'));
                                    }
                                    break;
                                case 'consumable_time':
                                    total += parseFloat(list.find('input[name="rate_1"]').val()||0);
                                    total += parseFloat(list.find('input[name="rate_2"]').val()||0) * parseFloat(list.find('input[name="counsel"]').val()||0);
                                    if(parseFloat(list.find('input[name="rate_1"]').val()||0) > 1){
                                        list.find('input[name="excess_rate"]').val(numeral(parseFloat(list.find('input[name="rate_1"]').val()||0) / val).format('0,0.00'));
                                    }
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

            $(document).on('click','.fee-action-btn',function(){
                var id = $(this).data('id');
                var type = $(this).data('type');
                $.get('{!! route('tran-fee-action') !!}',{
                    id: id
                },function(data){
                    switch (type){
                        case 'general':
                            loadCases(data);
                            break;
                        case 'special':
                            getSpecialFees();
                            break;
                    }
                    grandTotal();
                });
            });

            $(document).on('click','.case-action-btn',function(){
                var active = $('.tabs-container > .nav > li.active');
                var type = $(this).data('type');
                switch (type) {
                    case 'edit':
                        $.get('{!! route('action-contract-case') !!}',{
                            _token: '{!! csrf_token() !!}',
                            id: active.data('id'),
                            action: type
                        },function(data){
//                            console.log(data);
                            if(data.length != 0) {
                                caseModal.data('id',data[0].id);
                                caseModal.data('action','edit');
                                caseModal.find('input[name="title"]').val(data[0].title);
                                caseModal.find('textarea[name="venue"]').val(data[0].venue);
                                caseModal.find('input[name="date"]').val(data[0].date);
                                caseModal.find('input[name="number"]').val(data[0].number);
                                caseModal.find('select[name="class"]').val(data[0].class);
                                var counsel_select = $('.counsel-select');
                                counsel_select.empty().append('<option value="">Select Counsel</option>');
                                for(var a = 0; a < data[1].length; a++){
                                    counsel_select.append('<option value="'+ data[1][a].id +'">'+ data[1][a].profile.firstname +' '+ data[1][a].profile.lastname +'</option>');
                                }
                                loadCounsel();
                                caseModal.modal({backdrop: 'static', keyboard: false});
                            }
                        });
                        break;
                    case 'delete':
                        $.get('{!! route('action-contract-case') !!}',{
                            _token: '{!! csrf_token() !!}',
                            id: active.data('id'),
                            action: type
                        });
                        if(!$('.fees-table').length){
                            count += 1;
                            toastr.error('Required!','Please add Case!');
                        }
                        loadCases();
                        break;
                    case 'add':
                        $.get('{!! route('create-case') !!}',{
                            id: tran_id
                        },function(data){
//                            console.log(data[1]);
                            caseModal.data('id',data[0].id)
                            var counsel_select = $('.counsel-select');
                            counsel_select.empty().append('<option value="">Select Counsel</option>');
                            for(var a = 0; a < data[1].length; a++){
                                counsel_select.append('<option value="'+ data[1][a].id +'">'+ data[1][a].profile.firstname +' '+ data[1][a].profile.lastname +'</option>');
                            }
                            loadCounsel();
                            caseModal.modal({backdrop: 'static', keyboard: false});
                        });
                        break;
                }
            });

            $(document).on('click','#add-co-counsel-btn',function(){
                var select = $(this).closest('.form-group').find('select');
                var lead = caseModal.find('select[name="lead-counsel"]').val();
                $.get('{!! route('add-co-counsel') !!}',{
                    id: select.val(),
                    case_id: caseModal.data('id'),
                    lead: lead
                },function(){
                    loadCounsel();
                });
            });

            $(document).on('click','.remove-co-counsel-btn',function(){
                var item = $(this);
                $.get('{!! route('remove-co-counsel') !!}',{
                    id: item.data('id')
                },function(){
                    loadCounsel();
                });
            });

            $(document).on('click','#btn-store-case',function(){
                if(caseValidator() < 1){
                    $.post('{!! route('store-contract-case') !!}',{
                        _token: '{!! csrf_token() !!}',
                        title: caseModal.find('input[name="title"]').val(),
                        venue: caseModal.find('textarea[name="venue"]').val(),
                        date: caseModal.find('input[name="date"]').val(),
                        number: caseModal.find('input[name="number"]').val(),
                        case_class: caseModal.find('select[name="class"]').val(),
                        lead: caseModal.find('select[name="lead-counsel"]').val(),
                        id: caseModal.data('id'),
                        action: caseModal.data('action')
                    },function(data){
                        loadCases(data);
                        caseModal.modal('toggle');
                    });
                }
            });

            $(document).on('click','#save-contract-btn',function(){
                if(contractValidator() < 1){
                    var contract = $('#contract-info');
                    var action = $(this).data('action');
                    $.post('{!! route('contract-store') !!}',{
                        _token: '{!! csrf_token() !!}',
                        id: tran_id,
                        action: action,
                        fixed_price: contract.find('input[name="fixed_price"]').val(),
                        contract_date: contract.find('input[name="contract_date"]').val(),
                        start_date: contract.find('input[name="start_date"]').val(),
                        other_conditions: contract.find('textarea[name="other_conditions"]').val(),
                    },function(data){
                        if(data.length != 0) {
                            toastr.success('Successful!','Contract has been saved!');
                            setTimeout(function(){
                                window.location.replace('{!! route('contract.index') !!}');
                            }, 3000);
                        }
                    });
                }
            });

            function loadCounsel(){
                $.get('{!! route('load-counsel') !!}',{
                    id: caseModal.data('id'),
                },function(data){
                    var table = $('#co-counsel-table').find('tbody').empty();
                    if(data.length > 0){
                        for(var a = 0; a < data.length; a++){
                            if(data[a].lead === 1){
                                caseModal.find('select[name="lead-counsel"]').val(data[a].counsel_id);
                                caseModal.find('select[name="select-counsel"]').find('option[value="'+ data[a].counsel_id +'"]').hide();
                            }else{
                                caseModal.find('select[name="lead-counsel"]').find('option[value="'+ data[a].counsel_id +'"]').hide();
                                caseModal.find('select[name="select-counsel"]').find('option[value="'+ data[a].counsel_id +'"]').hide();
                                table.append('' +
                                    '<tr>' +
                                    '<td>'+ data[a].info.lawyer_code +'</td>' +
                                    '<td>'+ data[a].info.profile.firstname +' '+ data[a].info.profile.lastname +'</td>' +
                                    '<td>'+ data[a].info.lawyer_type +'</td>' +
                                    '<td class="text-right"><button data-id="'+ data[0].id +'" type="button" class="remove-co-counsel-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i></button></td>' +
                                    '</tr>' +
                                    '');
                            }
                            caseModal.find('select[name="select-counsel"]').val('');
                        }
                    }
                });
            }

            function loadCases(case_id){
                var nav = $('.tabs-container > .nav');
                var content = $('.tabs-container > .tab-content');
                $.get('{!! route('create-contract-case-list') !!}',{
                    id: tran_id
                },function(data){
                    console.log(data);
                    if(data.length != 0){
                        $('.case-action-btn').each(function(){
                            if($(this).data('type') != 'add'){
                                $(this).show();
                            }
                        });
                        $('.tabs-container > .nav, .tabs-container > .tab-content').empty();
                        for(var a = 0; a < data.length; a++){
                            var co_counsel = data[a].counsel_list.length - 1;
                            var active;
                            if (case_id == null){
                                active = (a === 0) ? 'active' : '';
                            }else{
                                active = (data[a].id == case_id) ? 'active' : '';
                            }
                            nav.append('<li class="'+ active +' case-'+ data[a].id +'" data-id="'+ data[a].id +'" data-title="'+ data[a].title +'"><a data-toggle="tab" href="#tab-'+ data[a].id +'">'+ data[a].title +'</a></li>');
                            content.append('' +
                                '<div id="tab-'+ data[a].id +'" data-id="'+ data[a].id +'" class="tab-pane '+ active +'">' +
                                    '<div class="panel-body">' +
                                        '<div class="row">' +
                                            '<div class="col-sm-5">' +
                                                '<div class="panel panel-default">' +
                                                    '<div class="panel-heading"> Case Information </div>' +
                                                    '<div class="panel-body">' +
                                                        '<div class="data-info">' +
                                                            '<h3>'+ data[a].title +'</h3>' +
                                                            '<small>Case Title</small>' +
                                                        '</div>' +
                                                        '<div class="data-info">' +
                                                            '<h4>'+ data[a].venue +'</h4>' +
                                                            '<small>Venue</small>' +
                                                        '</div>' +
                                                        '<div class="row">' +
                                                            '<div class="col-sm-6">' +
                                                                '<div class="data-info">' +
                                                                    '<h4>'+ data[a].number +'</h4>' +
                                                                    '<small>Case No.</small>' +
                                                                '</div>' +
                                                            '</div>' +
                                                            '<div class="col-sm-6">' +
                                                                '<div class="data-info">' +
                                                                    '<h4>'+ data[a].date +'</h4>' +
                                                                    '<small>Case Date</small>' +
                                                                '</div>' +
                                                            '</div>' +
                                                        '</div>' +
                                                        '<div class="row">' +
                                                            '<div class="col-sm-6">' +
                                                                '<div class="data-info">' +
                                                                    '<h4>'+ data[a].class +'</h4>' +
                                                                    '<small>Case Classification</small>' +
                                                                '</div>' +
                                                            '</div>' +
                                                            '<div class="col-sm-6">' +
                                                                '<div class="data-info">' +
                                                                    '<h4>'+ data[a].status +'</h4>' +
                                                                    '<small>Case Status</small>' +
                                                                '</div>' +
                                                            '</div>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                                '<div class="panel panel-default counsel-panel">' +
                                                    '<div class="panel-heading"> Counsel List </div>' +
                                                    '<div class="panel-body"></div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-sm-7">' +
                                                '<div class="panel panel-default">' +
                                                    '<div class="panel-heading">' +
                                                        'General Fees' +
                                                        '<div class="ibox-tools pull-right">' +
                                                            '<button type="button" class="btn btn-xs btn-success add-fee" data-id="'+ data[a].id +'" data-counsel="'+ co_counsel +'" data-type="general">Add Fee</button>' +
                                                        '</div>' +
                                                    '</div>' +
                                                    '<div class="panel-body">' +
                                                        '<table class="table table-striped">' +
                                                        '<thead>' +
                                                        '<tr>' +
                                                            '<th>Name</th>' +
                                                            '<th class="text-right">Amount</th>' +
                                                            '<th class="text-center" style="width: 30px;"><i class="fa fa-cogs"></i></th>' +
                                                        '</tr>' +
                                                        '</thead>' +
                                                        '<tbody class="fees-table"></tbody>' +
                                                        '</table>' +
                                                        '<table class="table invoice-total">' +
                                                        '<tbody>' +
                                                        '<tr>' +
                                                            '<td><strong>TOTAL :</strong></td>' +
                                                            '<td><h2 class="text-success case-fee-total">00.00</h2></td>' +
                                                        '</tr>' +
                                                        '</tbody>' +
                                                        '</table>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '');
                            var counsel = $('#tab-'+ data[a].id).find('.counsel-panel > .panel-body');
                            counsel.empty();
                            for(var b = 0; b < data[a].counsel_list.length; b++){
                                var type = (data[a].counsel_list[b].lead == 1) ? 'Lead Counsel' : 'Co - Counsel';
                                counsel.append('' +
                                    '<div class="data-info">' +
                                    '<h4>'+ data[a].counsel_list[b].info.profile.firstname +' '+ data[a].counsel_list[b].info.profile.lastname +'</h4>' +
                                    '<small>'+ type +'</small>' +
                                    '</div>' +
                                '');
                            }

                            var total = 0;
                            var case_fee = $('#tab-'+ data[a].id).find('table:first-child > tbody');
                            case_fee.empty();
                            for(var c = 0; c < data[a].fees.length; c++){
                                total += parseFloat(data[a].fees[c].total);
                                case_fee.append('' +
                                    '<tr class="fee-row">' +
                                        '<td>'+ data[a].fees[c].fee.display_name +'</td>' +
                                        '<td class="text-right">'+ numeral(data[a].fees[c].total).format('0,0.00') +'</td>' +
                                        '<td>' +
                                            '<div class="btn-group text-right">' +
//                                                '<button type="button" class="fee-action-btn btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> </button>' +
                                                '<button type="button" data-id="'+ data[a].fees[c].id +'" data-type="general" class="fee-action-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> </button>' +
                                            '</div>' +
                                        '</td>' +
                                    '</tr>' +
                                '');
                            }
                            $('#tab-'+ data[a].id).find('.case-fee-total').text(numeral(total).format('0,0.00'));

                        }
                    }else{
                        $('.case-action-btn').each(function(){
                            if($(this).data('type') != 'add'){
                                $(this).hide();
                            }
                        });
                        nav.empty();
                        content.empty();
                    }
                });

            }

            function getSpecialFees(){
                var table = $('#table-special-fee').find('tbody');
                var total = 0;
                $.get('{!! route('get-trans-fee') !!}',{
                    transaction_id: tran_id,
                    type: 'special'
                },function(data){
                    if(data.length != 0){
                        table.empty();
                        for(var a = 0; a < data.length; a++){
                            table.append('' +
                                '<tr>' +
                                    '<td>'+ data[a].fee.display_name +'</td>' +
                                    '<td class="text-right">'+ numeral(data[a].amount).format('0,0.00') +'</td>' +
                                    '<td class="text-right">' +
                                        '<div class="btn-group">' +
                                            '<button type="button" data-id="'+ data[a].id +'" data-type="special" class="fee-action-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> </button>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>' +
                            '');
                            total += parseFloat(data[a].amount);
                        }
                        table.closest('div').find('h2').text(numeral(total).format('0,0.00'));
                    }
                });
            }

            function grandTotal(){
                $.get('{!! route('transaction-amount') !!}',{
                    transaction_id: tran_id
                },function(data){
                    $('#contract-total').text(numeral(data).format('0,0.00'))
                });
            }

            var caseValidator = function(){
                caseModal.find('.form-group').removeClass('has-error');
                var count = 0;
                caseModal.find('.required').each(function(){
                    if(!$(this).val()){
                        count += 1;
                        $(this).closest('.form-group').addClass('has-error');
                    }
                });
                if(count > 0){
                    toastr.error('Required!','Invalid inputs!');
                }
                if(count > 1){
                    setTimeout(function(){
                        $('.form-group').removeClass('has-error');
                    }, 6000);
                }
                return count;
            }

            var contractValidator = function(){
                var contract = $('#contract-info');
                contract.find('.form-group').removeClass('has-error');
                var count = 0;
                var inputs = 0;
                $('.fees-table').each(function(){
                    var item = $(this);
                    var fees = item.find('.fee-row').length;
                    var id = item.closest('.tab-pane').data('id');
                    var tab = $('.tabs-container').find('.case-'+ id);
                    console.log(id +': '+ fees);
                    if(fees < 1){
                        tab.addClass('has-error');
                        toastr.error('Required!','Please add Fee\'s in Case: '+ tab.data('title') +'!');
                        count += 1;
                    }

                });

                contract.find('.required').each(function(){
                    if(!$(this).val()){
                        count += 1;
                        inputs += 1;
                        $(this).closest('.form-group').addClass('has-error');
                    }
                });

                if(inputs > 0){
                    toastr.error('Required!','Invalid inputs!');
                }

                if(!$('.fees-table').length){
                    count += 1;
                    toastr.error('Required!','Please add Case!');
                }

                setTimeout(function(){
                    $('.tabs-container').find('.nav-tabs > li').removeClass('has-error');
                    $('#billing-address').removeClass('shake');
                    $('.form-group').removeClass('has-error');
                }, 6000);
                return count;
            }

            feeModal.on('shown.bs.modal', function () {
                $('#fee-select').chosen().trigger("chosen:updated");
            });

            feeModal.on('hide.bs.modal', function () {
                $('#charge-list').empty();
                $('#fee-desc-box').hide();
                $('#charge-type').val('');
                $('#charge-type').closest('.form-group').hide();
            });

//            $(document).keypress(function(e) {
//                if ($('.modal').hasClass('in') && (e.keycode == 13 || e.which == 13)) {
//                    $('.modal.in').find('.modal-submit').trigger('click');
//                }
//            });

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }

        });
    </script>
@endsection