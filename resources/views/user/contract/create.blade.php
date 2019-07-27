@extends('layouts.master')

@section('title', ucfirst($action).' Contract')


@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>{{ ($action === 'create') ? 'Define' : 'Edit' }} Contract</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>{{ ($action === 'create') ? 'Define' : 'Edit' }} Contract</strong>
                </li>
            </ol>
        </div>
        @if(auth()->user()->can('add-contract'))
        <div class="col-lg-4">
            <div class="title-action">
                <button type="button" id="save-contract-btn" data-action="{!! ($data->status == 'ongoing') ? 'edit' : 'add' !!}" class="btn btn-primary">{!! ($data->status == 'ongoing') ? 'Update Contract' : 'Save Contract' !!}</button>
            </div>
        </div>
        @endif
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">

            <div class="col-sm-3">

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
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Type of Contract:</label>
                                            <input type="hidden" value="{{ $data->client_id }}" name="client_id">
                                            @switch($action)
                                                @case('create')
                                                {!! Form::select('contract_type', array('special' => 'Special Retainer', 'general' => 'General Retainer'), $data->contract->contract_type, array('data-value' => ($data->contract->contract_type != null)? $data->contract->contract_type : '', 'class' => 'form-control contract-type', 'placeholder' => 'Select type')) !!}
                                                @break

                                                @case('edit')
                                                {!! Form::select('contract_type', array('special' => 'Special Retainer', 'general' => 'General Retainer'), $data->contract->contract_type, array('data-value' => ($data->contract->contract_type != null)? $data->contract->contract_type : '', 'class' => 'form-control contract-type', 'placeholder' => 'Select type', 'disabled')) !!}
                                                @break
                                            @endswitch
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group contract-amount">
                                            <label>Contract Amount:</label>
                                            <input type="text" name="contract_amount" value="{{ number_format($data->contract->contract_amount, 2, '.', ',') }}" class="form-control numonly contract-amount-input separator-dec">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Contract Date:</label>
                                            <div class="input-group m-b date">
                                                {!! Form::text('contract_date', ($data->contract->contract_date)? \Carbon\Carbon::parse($data->contract->contract_date)->format('m/d/Y'):\Carbon\Carbon::now()->format('m/d/Y'), array('class' => 'form-control')) !!}
                                                <span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="col-sm-6">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label>Start Date:</label>--}}
                                            {{--<div class="input-group date">--}}
                                                {{--{!! Form::text('start_date', ($data->contract->start_date)? \Carbon\Carbon::parse($data->contract->start_date)->format('m/d/Y'):\Carbon\Carbon::now()->format('m/d/Y'), array('class' => 'form-control')) !!}--}}
                                                {{--<span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </div>

                                <div class="form-group">
                                    <label>Other Condition:</label>
                                    {!! Form::textarea('other_conditions', $data->contract->other_conditions, array('class' => 'form-control resize-vertical other-conditions')) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <div class="col-sm-9" id="">
                <div id="retainer-box"></div>
            </div>

        </div>

    </div>

    <div class="modal inmodal" id="modal" data-action="" data-type="" data-id="0" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-save-btn">Save</button>
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
    {!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    {!! Html::script('js/plugins/chosen/chosen.jquery.js') !!}
    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}

    {!! Html::script('js/jquery.masknumber.js') !!}
    <script>
        $(document).ready(function() {
            $('[name=contract_amount]').maskNumber({decimal: '.', thousands: ','});
            var modal = $('#modal');
            var retainerBox = $('#retainer-box');
            var contractInfo = $('#contract-info');
            var transID = '{!! $data->id !!}';
            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();
            $('.input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            }).on('hide',function(){
                updateContract();
            });

            contractType();

            modal.on('shown.bs.modal', function() {
                $('.counsel-select, .co-counsel-select').chosen();
                $('.counsel-select, .co-counsel-select').trigger('chosen:updated');
                console.log('chosen updated shown');
            });

            $(document).on('click', '.modal-open', function () {
                var type = $(this).data('type');
                var action = $(this).data('action');
                var id = $(this).data('id');
                switch (type) {
                    case 'case':
                        switch (action){
                            case 'add':
                                modal.find('.modal-dialog').removeClass('modal-sm').addClass('modal-lg').end()
                                    .find('.modal-title').text('Case Management & Def.').end()
                                    .find('.modal-body').empty().append(caseForms);
                                loadCounsel('case');
                                modal.data('type',type);
                                modal.data('action',action);
                                modal.find('#modal-save-btn').text('Save');
                                $('.input-group.date').datepicker({
                                    todayBtn: "linked",
                                    keyboardNavigation: false,
                                    forceParse: false,
                                    calendarWeeks: true,
                                    autoclose: true
                                });
                                delay(function(){
                                    modal.modal({backdrop: 'static', keyboard: false});
                                }, 1000 );
                                break;
                            case 'edit':
                                modal.find('.modal-dialog').removeClass('modal-sm').addClass('modal-lg').end()
                                    .find('.modal-title').text('Case Management & Def.').end()
                                    .find('.modal-body').empty().append(caseForms);
                                loadCounsel('case');
                                modal.data('type',type);
                                modal.data('action',action);
                                modal.data('id',id);
                                modal.find('#modal-save-btn').text('Update');
                                $('.input-group.date').datepicker({
                                    todayBtn: "linked",
                                    keyboardNavigation: false,
                                    forceParse: false,
                                    calendarWeeks: true,
                                    autoclose: true
                                });

                                $.get('{!! route('edit-case') !!}',{
                                    id: id
                                },function(data){
                                    console.log(data);
                                    if(data.length > 0){
                                        var caseDate = (data[0].date === null) ? 'For Filing' : moment(data[0].date).format("MM/DD/YYYY");
                                        modal.find('input[name="title"]').val(data[0].title);
                                        modal.find('textarea[name="venue"]').val(data[0].venue);
//                                        modal.find('input[name="date"]').val(moment(data[0].date).format("MM/DD/YYYY"));
                                        modal.find('input[name="date"]').val(caseDate);
                                        modal.find('input[name="number"]').val(data[0].number);
                                        modal.find('select[name="class"]').find('option[value="'+ data[0].class +'"]').attr('selected', 'selected');
                                        modal.find('select[name="counsel_id"]').find('option[value="'+ data[0].counsel_id +'"]').attr('selected', 'selected');
                                        for(var a = 0; a < data[0].counsel_list.length; a++){
                                            if(data[0].counsel_list[a].lead === 0){
                                                console.log('co-counsel id: '+ data[0].counsel_list[a].id);
                                                $('.co-counsel-select').find('option[value="'+ data[0].counsel_list[a].counsel_id +'"]').attr('selected', 'selected');
                                            }
                                        }
                                        if(data[1].length > 0){
                                            swal({
                                                title: "Are you sure?",
                                                text: "This Case has Billing already!",
                                                type: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#DD6B55",
                                                confirmButtonText: "Yes, edit pls!",
                                                cancelButtonText: "No, cancel pls!",
                                                closeOnConfirm: false,
                                                closeOnCancel: false },
                                            function (isConfirm) {
                                                if (isConfirm) {
                                                    swal.close();
                                                    modal.modal({backdrop: 'static', keyboard: false});
                                                } else {
                                                    swal("Cancelled", "Your Case record is safe :)", "error");
                                                }
                                            });
                                        }else{
                                            modal.modal({backdrop: 'static', keyboard: false});
                                        }
                                    }
                                });

                                break;
                            case 'delete':
                                $.get('{!! route('delete-case') !!}',{
                                    id: id,
                                    action: 'check'
                                },function(data){
                                    console.log(data);
                                    if(data.length > 1){
                                        var errorCount = 0;
                                        var errorMsg = new Array();
                                        if(data[1].length > 0){
                                            errorCount += 1;
                                            errorMsg.push('Service Report');
                                        }
                                        if(data[2].length > 0){
                                            errorCount += 1;
                                            errorMsg.push('Special Billing');
                                        }
                                        console.log('errorCount: '+ errorCount);
                                        if(errorCount > 0){
                                            swal({
                                                title: "Can't Delete!",
                                                text: "Case has existing "+ errorMsg.join(', '),
                                                type: "error"
                                            });
                                        }
                                        if(errorCount < 1){
                                            swal({
                                                title: "Are you sure?",
                                                text: "Your will not be able to recover this Case!",
                                                type: "warning",
                                                showCancelButton: true,
                                                confirmButtonColor: "#DD6B55",
                                                confirmButtonText: "Yes, delete it!",
                                                cancelButtonText: "No, cancel plx!",
                                                closeOnConfirm: false,
                                                closeOnCancel: false },
                                            function (isConfirm) {
                                                if (isConfirm) {
                                                    $.get('{!! route('delete-case') !!}',{
                                                        id: id,
                                                        action: 'delete'
                                                    },function(data){
                                                        if(data === 'deleted'){
                                                            contractType();
                                                            swal("Deleted!", "Case Deleted", "success");
                                                        }
                                                    });
                                                } else {
                                                    swal("Cancelled", "Your Case record is safe :)", "error");
                                                }
                                            });
                                        }
                                    }
                                });
                                break;
                        }

                        break;
                    case 'fee':
                        modal.find('.modal-dialog').removeClass('modal-lg').addClass('modal-sm').end()
                            .find('.modal-title').text('Fee Details').end()
                            .find('.modal-body').empty().append(feeForm);
                            getFees();
                            if(contractInfo.find('select[name="contract_type"]').val() == 'special'){
                                caseCounsel(id);
                                modal.find('.modal-body').append(specialBilling);
                                $('.i-checks').iCheck({
                                    checkboxClass: 'icheckbox_square-green'
                                });
                            }else{
                                modal.find('.modal-body').prepend('' +
                                    '<div class="form-group">' +
                                        '<label>Counsel:</label>' +
                                        '<select name="co-counsel-select" class="form-control required"></select>' +
                                    '</div>' +
                                '');
                                loadCounsel('fee');
                            }
                        modal.data('type',type);
                        modal.data('id',id);
                        modal.find('#modal-save-btn').text('Save');
                        modal.modal({backdrop: 'static', keyboard: false});
                        break;
                    case 'duplicate':
                        modal.find('.modal-dialog').removeClass('modal-lg').addClass('modal-sm').end()
                            .find('.modal-title').text('Duplicate Fee').end()
                            .find('.modal-body').empty().append(caseForm);
                            getCase();
                        modal.data('type',type);
                        modal.data('id',id);
                        modal.find('#modal-save-btn').text('Save');
                        modal.modal({backdrop: 'static', keyboard: false});
                        break;
                }
            });

            $(document).on('change','.contract-type',function(){
                var oldVal;
                switch($(this).data('value')){
                    case 'general':
                        oldVal = 'General Retainer';
                        break;
                    default:
                        oldVal = 'Special Retainer';
                }
                var newVal = $('.contract-type').val();
                $.get('{!! route('check-transaction') !!}',{
                    id: transID
                },function(data){
                    if(data > 0){
                        swal({
                            title: 'Are you sure?',
                            text: 'Your will not be able to recover ' + oldVal + ' Contract Fee Details',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, cancel pls!'
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $('.contract-type').data('value', newVal);
                                updateContract();
                            } else {
                                $('.contract-type').val(oldVal);
                            }
                            contractType();
                        });
                    }else{
                        contractType();
                        updateContract();
                    }
                });
            });

            $(document).on('change', '.other-conditions, .contract-amount-input', function () {
                updateContract();
//                delay(function(){
//                    updateContract();
//                }, 1500 );
            });

            $(document).on('change', '#fee-select', function () {
                var options;
                switch(contractInfo.find('select[name="contract_type"]').val()){
                    case 'general':
                        modal.find('.modal-body').find('.form-group').slice(2).remove();
                        options = '' +
                            '<option value="">Select type</option>' +
                            '<option value="time">Time</option>' +
                        '';
                        break;
                    default:
                        modal.find('.modal-body').find('.form-group').slice(3).remove();
                        options = '' +
                            '<option value="">Select type</option>' +
                            '<option value="amount">Amount Only</option>' +
                            '<option value="document">Document</option>' +
                            '<option value="time">Time</option>' +
                            '<option value="installment">Installment</option>' +
                            '<option value="percentage">Percentage</option>' +
                        '';
                }
                if ($(this).val() != '') {
                    modal.find('.modal-body').append('' +
                        '<div class="form-group">' +
                            '<label>Charge Type</label>' +
                            '<select name="charge_type" class="form-control charge_type">'+ options +'</select>' +
                        '</div>' +
                    '');
                }
            });

            $(document).on('change','.charge_type',function(){
                var sliced = (contractInfo.find('select[name="contract_type"]').val() == 'special') ? 4 : 3;
                modal.find('.modal-body').find('.form-group').slice(sliced).remove();
                switch ($(this).val()) {
                    case 'amount':
                        modal.find('.modal-body').append(amountType);
                        break;
                    case 'document':
                        modal.find('.modal-body').append(documentType);
                        break;
                    case 'time':
                        modal.find('.modal-body').append(amountType).end()
                            .find('.modal-body').append(timeType);
                        break;
                    case 'installment':
                        modal.find('.modal-body').append(amountType).end()
                            .find('.modal-body').append(installmentType);
                        break;
                    case 'percentage':
                        modal.find('.modal-body').append(amountType).end()
                            .find('.modal-body').append(percentageType);
                        break;
                }
                modal.find('.modal-body').find('.form-group:nth-child(3)').find('input').focus();
                $('.separator-dec').maskNumber({decimal: '.', thousands: ','});
                $('.separator-int').maskNumber({integer: true});
            });

            $(document).on('click','#modal-save-btn',function(){
                var type = modal.data('type');
                var action = modal.data('action');
                var id = modal.data('id');
                var contract = $('#contract-info').find('select[name="contract_type"]').val();
                console.log(id);
                switch (type){
                    case 'fee':

                        var free_page = modal.find('input[name="free_page"]').val()||'0';
                        var excess_rate = modal.find('input[name="excess_rate"]').val()||'0';
                        var cap_value = modal.find('input[name="cap_value"]').val()||'0';
                        var minutes = modal.find('input[name="minutes"]').val()||'0';
                        var installment = modal.find('input[name="installment"]').val()||'0';
                        var percentage = modal.find('input[name="percentage"]').val()||'0';
                        var amount = modal.find('input[name="amount"]').val()||'0';

                        $.post('{!! route('store-fee') !!}',{
                            _token: '{!! csrf_token() !!}',
                            transaction_id: transID,
                            case_id: modal.data('id'),
                            fee_id: modal.find('select[name="fee-select"]').val(),
                            charge_type: modal.find('select[name="charge_type"]').val(),
                            free_page: parseInt(free_page.replace(/,/g, '')),
                            excess_rate: parseFloat(excess_rate.replace(/,/g, '')),
                            cap_value: parseFloat(cap_value.replace(/,/g, '')),
                            minutes: parseInt(minutes.replace(/,/g, '')),
                            installment: parseFloat(installment.replace(/,/g, '')),
                            percentage: parseInt(percentage.replace(/,/g, '')),
                            amount: parseFloat(amount.replace(/,/g, '')),
                            counsel_id: modal.find('select[name="co-counsel-select"]').val()||null,
                            special_billing: modal.find('input[name="special-billing"]').is(':checked') ? 1 : 0
                        },function(data){
                            switch(contract){
                                case 'special':
                                    loadDetails(data.cases.id);
                                    break;
                                default:
                                    loadDetails();
                            }
                            modal.modal('toggle');
                        });
                        break;
                    case 'case':
                        console.log(action);
                        console.log(id);
                        $.post('{!! route('store-contract-case') !!}',{
                            _token: '{!! csrf_token() !!}',
                            transaction_id: transID,
                            case_id: id,
                            action: action,
                            title: modal.find('input[name="title"]').val(),
                            venue: modal.find('textarea[name="venue"]').val(),
                            date: modal.find('input[name="date"]').val(),
                            number: modal.find('input[name="number"]').val(),
                            case_class: modal.find('select[name="class"]').val(),
                            counsel_id: modal.find('select[name="counsel_id"]').val(),
                            co_counsel: modal.find('select[name="co_counsel"]').chosen().val(),
                            id: transID
                        },function(data){
                            loadDetails(data);
                            modal.modal('toggle');
                        });
                        break;
                    case 'duplicate':
                        //alert($('#case-select').find(":selected").val());
                        console.log(action);
                        console.log(id);
                        $.get('{!! route('store-duplicate-fee') !!}',{
                            _token: '{!! csrf_token() !!}',
                            case_id: id,
                            id: transID,
                            dup_case_id: $('#case-select').find(":selected").val()
                        },function(data){
                            switch(contract){
                                default:
                                    loadDetails(id);
                            }
                            modal.modal('toggle');
                        });
                        break;
                }
            });

            $(document).on('click','.fee-delete-btn',function(){
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: 'Your will not be able to recover this Fee Detail',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel pls!'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $.get('{!! route('delete-fee') !!}',{
                            id: id
                        },function(data){
                            console.log('delete-fee: '+ data);
                            if( (data[2] !== 0) && (data[2].billing_id !== null)){
                                swal("Cancelled", "This Special billing already billed :)", "error");
                            }
                            else if(data[0] > 0){
                                swal("Cancelled", "This Fee has Service Reports Already :)", "error");
                            }
                            else{
                                console.log('loadDetails: '+ data[1]);
                                loadDetails(data[1]);
                            }
                        });
                    }
                });
            });

            $(document).on('click','#save-contract-btn',function(){
                console.log(transID);
                console.log($(this).data('action'));
                $.get('{!! route('contract-store') !!}',{
                    id: transID,
                    action: $(this).data('action')
                },function(data){
                    console.log(data);
                    if(data[0] > 0){
                        switch (data[1].contract_type){
                            case 'billing':
                                swal({
                                    title: 'Contract has been saved!',
                                    type: 'success',
                                    confirmButtonColor: '#DD6B55',
                                    confirmButtonText: 'Contract List',
                                }, function () {
                                    window.location.replace('{!! route('contract.index') !!}');
                                });
                                break;
                            default:
                                swal({
                                    title: 'Contract save!',
                                    text: 'Proceed to Service Report Page?',
                                    type: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#DD6B55',
                                    confirmButtonText: 'Yes, Proceed!',
                                    cancelButtonText: 'No, show list!'
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        @if(auth()->user()->can('browse-service-report'))
                                            window.location.replace('{!! route('service-report.index') !!}');
                                        @else
                                            toastr.error('Error','You don\'t have access on Service report!');
                                            window.location.replace('{!! route('contract.index') !!}');
                                        @endif

                                    } else {
                                        window.location.replace('{!! route('contract.index') !!}');
                                    }
                                });
                        }
                    }else{
                        toastr.error('Error!','Add Fee');
                    }
                });
            });

            function updateContract() {
                var contract_amount = contractInfo.find('input[name="contract_amount"]').val()||'0';

                $.post('{!! route('update-contract', array('id'=>$data->contract->id)) !!}', {
                    _token: '{!! csrf_token() !!}',
                    contract_type: contractInfo.find('select[name="contract_type"]').val(),
                    contract_amount: parseFloat(contract_amount.replace(/,/g, '')),
                    contract_date: contractInfo.find('input[name="contract_date"]').val(),
//                    start_date: contractInfo.find('input[name="start_date"]').val(),
                    other_conditions: contractInfo.find('textarea[name="other_conditions"]').val()
                });
            }

            function contractType() {
                var type = $('#contract-info .contract-type').val();
                var contractAmount = $('.contract-amount');
                switch (type) {
                    case 'special':
                        console.log('special');
                        contractAmount.show();
                        retainerBox.empty().append('' +
                            '<div class="ibox float-e-margins">' +
                                '<div class="ibox-title bg-success">' +
                                    '<h5>Special Retainer Contract Fee Details</h5>' +
                                    '<div class="ibox-tools">' +
                                        @if(auth()->user()->can('add-case-contract'))
                                        '<button type="button" data-type="case" data-action="add" class="btn btn-xs btn-primary modal-open"><i class="fa fa-plus"></i> Create Case </button>' +
                                        @endif
                                    '</div>' +
                                '</div>' +
                                '<div class="tabs-container">' +
                                    '<ul class="nav nav-tabs"></ul>' +
                                    '<div class="tab-content"></div>' +
                                '</div>' +
                            '</div>' +
                            '');
                        loadDetails();
                        break;
                    case 'general':
                        console.log('general');
                        contractAmount.hide();
                        retainerBox.empty().append('' +
                            '<div class="ibox float-e-margins">' +
                                '<div class="ibox-title bg-success">' +
                                    '<h5>General Retainer Contract Fee Details</h5>' +
                                    '<div class="ibox-tools">' +
                                        @if(auth()->user()->can('add-fee-contract'))
                                        '<button type="button" data-type="fee" data-contract="general" class="btn btn-xs btn-primary modal-open"><i class="fa fa-plus"></i> Add Fee</button>' +
                                        @endif
                                    '</div>' +
                                '</div>' +
                                '<div class="ibox-content">' +
                                    '<table class="table table-striped">' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th>Fee Name</th>' +
                                    '<th>Details</th>' +
                                    '<th class="text-center" style="width: 30px;"><i class="fa fa-cogs"></i></th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody></tbody>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '');
                        loadDetails();
                        break;
                    case 'billing':
                        retainerBox.empty().append('' +
                            '<div class="ibox float-e-margins">' +
                                '<div class="ibox-title bg-success">' +
                                    '<h5>Special Billing Contract Fee Details</h5>' +
                                    '<div class="ibox-tools">' +
                                        @if(auth()->user()->can('add-fee-contract'))
                                        '<button type="button" data-type="fee" data-contract="general" class="btn btn-xs btn-primary modal-open"><i class="fa fa-plus"></i> Add Fee</button>' +
                                        @endif
                                    '</div>' +
                                '</div>' +
                                '<div class="ibox-content">' +
                                    '<table class="table table-striped">' +
                                    '<thead>' +
                                    '<tr>' +
                                    '<th>Fee Name</th>' +
                                    '<th>Details</th>' +
                                    '<th class="text-center" style="width: 30px;"><i class="fa fa-cogs"></i></th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody></tbody>' +
                                    '</table>' +
                                '</div>' +
                            '</div>' +
                        '');
                        loadDetails();
                        break;
                    default:
                        retainerBox.empty();
                }

                $('.separator-dec').maskNumber({decimal: '.', thousands: ','});
            }

            function getFees() {
                $.get('{!! route('fees') !!}', {
                    type: $('#contract-info').find('select[name="contract_type"]').val()
                }, function (data) {
                    console.log(data);
                    if (data.length != 0) {
                        modal.find('#fee-select').empty().append('<option value="'+ null +'">Select Fee</option>');
                        for (var a = 0; a < data.length; a++) {
                            modal.find('#fee-select').append('<option value="' + data[a].id + '">' + data[a].display_name + '</option>');
                        }
                        modal.find('#fee-select').chosen().trigger("chosen:updated");
                    }
                });
            }

            function getCase() {
                $.get('{!! route('get-case-name') !!}', {
                    type: $('#contract-info').find('input[name="client_id"]').val()
                    
                }, function (data) {
                    console.log(data);
                    if (data.length != 0) {
                        modal.find('#case-select').empty().append('<option value="'+ null +'">Select Case to Duplicate Fee</option>');
                        for (var a = 0; a < data.length; a++) {
                            modal.find('#case-select').append('<option name="dup_case_id" value="' + data[a].id + '">' + data[a].title + '</option>');
                        }
                         modal.find('#case-select').chosen().trigger("chosen:updated");
                         
                    }
                });
            }
            function loadCounsel(type) {
                $.get('{!! route('load-counsel') !!}',function (data) {
                    console.log('counsel loaded');
                    if (data.length != 0) {
                        switch (type){
                            case 'case':
                                modal.find('select[name="counsel_id"]').empty();
                                modal.find('select[name="co_counsel"]').empty();
                                for (var a = 0; a < data.length; a++) {
                                    modal.find('select[name="counsel_id"]').append('<option value="' + data[a].id + '">' + data[a].profile.firstname + ' ' + data[a].profile.lastname + '</option>');
                                    if(data[a].id != 1){
                                        modal.find('select[name="co_counsel"]').append('<option value="' + data[a].id + '">' + data[a].profile.firstname + ' ' + data[a].profile.lastname + '</option>');
                                    }
                                }
//                                modal.find('select[name="counsel_id"]').chosen().trigger("chosen:updated");
//                                modal.find('select[name="co_counsel"]').chosen().trigger("chosen:updated");
                                break;
                            case 'fee':
                                modal.find('select[name="co-counsel-select"]').empty();
                                for (var b = 0; b < data.length; b++) {
                                    modal.find('select[name="co-counsel-select"]').append('<option value="' + data[b].id + '">' + data[b].profile.firstname + ' ' + data[b].profile.lastname + '</option>');
                                }
                                modal.find('select[name="co-counsel-select"]').chosen().trigger("chosen:updated");
                                break;
                        }

                    }
                });
            }

            function caseCounsel(id){
                $.get('{!! route('case-counsels') !!}',{
                    case_id: id
                },function(data){
                    var counselSelect = new Array();
                    if(data.counsel_list.length > 0){

                        for(var a = 0; a < data.counsel_list.length; a++){
                            switch(data.counsel_list[a].lead){
                                case 1:
                                    counselSelect.push('<option value="'+ data.counsel_list[a].counsel_id +'" selected>[ LC ]: '+ data.counsel_list[a].info.profile.full_name +'</option>');
                                    break;
                                default:
                                    counselSelect.push('<option value="'+ data.counsel_list[a].counsel_id +'">[ CC ]: '+ data.counsel_list[a].info.profile.full_name +'</option>');
                            }
                        }

                        modal.find('.modal-body').prepend('' +
                            '<div class="form-group">' +
                            '<label>Counsel List</label>' +
                            '<select name="co-counsel-select" id="co-counsel-select" class="form-control">' +
                                '<option value="">None</option>' +
                                ''+ counselSelect +
                            '</select>' +
                            '</div>' +
                        '');


                    }
                });
            }

            function loadDetails(case_id){
                var contract = $('#contract-info').find('select[name="contract_type"]').val();
                $.get('{!! route('contract-fee') !!}',{
                    transaction_id: transID,
                    type: contract
                },function(data){
                    console.log(data);
                    if(data.length != 0){
                        switch(contract){
                            case 'special':
                                var nav = $('.tabs-container > .nav');
                                var content = $('.tabs-container > .tab-content');
                                $('.tabs-container > .nav, .tabs-container > .tab-content').empty();
                                for(var a = 0; a < data.length; a++){
                                    var active;
                                    if (case_id === undefined){
                                        active = (a === 0) ? 'active' : '';
                                    }else{
                                        active = (data[a].id == case_id) ? 'active' : '';
                                    }
                                    nav.append('<li class="'+ active +' case-'+ data[a].id +'" data-id="'+ data[a].id +'" data-title="'+ data[a].title +'"><a data-toggle="tab" href="#tab-'+ data[a].id +'">'+ (data[a].title ? data[a].title : 'No Title') +'</a></li>');
                                    var counselListRow = '';
                                    if(data[a].counsel_list.length > 0){
                                        var caseCounsel = new Array();
                                        var counselType;
                                        for(var b = 0; b < data[a].counsel_list.length; b++){
                                            switch(data[a].counsel_list[b].lead){
                                                case 1:
                                                    counselType = '<small class="co-counsel-count">Lead Counsel</small>';
                                                    break;
                                                default:
                                                    counselType = '<small class="co-counsel-count">Co-Counsel</small>';
                                            }

                                            caseCounsel.push('' +
                                                '<div class="col-sm-6">' +
                                                    '<div class="data-info">' +
                                                        '<h4>'+ data[a].counsel_list[b].info.profile.full_name +'</h4>' +
                                                        ''+ counselType +
                                                    '</div>' +
                                                '</div>' +
                                            '');
                                        }
                                        counselListRow = '<div class="row">' +
                                            '<div class="hr-line-dashed"></div>' +
                                            '<h3 class="text-center">Case Counsel/s</h3>' +
                                            '' + caseCounsel.join('') +
                                            '</div>';
                                    }

                                    var caseDate = (data[a].date === null) ? 'For Filing' : moment(data[a].date).format('ll');
                                    content.append('' +
                                        '<div id="tab-'+ data[a].id +'" data-id="'+ data[a].id +'" class="tab-pane '+ active +'">' +
                                            '<div class="panel-body">' +
                                                '<div class="row">' +
                                                    '<div class="col-sm-5">' +
                                                        '<div class="panel panel-default">' +
                                                            '<div class="panel-heading"> ' +
                                                                'Case Information ' +
                                                                '<div class="ibox-tools pull-right">' +
                                                                    @if(auth()->user()->can('edit-case-contract'))
                                                                    '<button type="button" data-type="case" data-action="edit" class="btn btn-xs btn-warning modal-open" data-id="'+ data[a].id +'">Edit</button>&nbsp;' +
                                                                    @endif
                                                                    @if(auth()->user()->can('delete-case-contract'))
                                                                    '<button type="button" data-type="case" data-action="delete" class="btn btn-xs btn-danger modal-open" data-id="'+ data[a].id +'">delete</button>' +
                                                                    @endif
                                                                '</div>' +
                                                            '</div>' +
                                                            '<div class="panel-body">' +
                                                                '<div class="data-info">' +
                                                                    '<h3>'+ (data[a].title ? data[a].title : 'None') +'</h3>' +
                                                                    '<small>Case Title</small>' +
                                                                '</div>' +
                                                                '<div class="data-info">' +
                                                                    '<h4>'+ (data[a].venue ? data[a].venue : 'None') +'</h4>' +
                                                                    '<small>Venue</small>' +
                                                                '</div>' +
                                                                '<div class="row">' +
                                                                    '<div class="col-sm-6">' +
                                                                        '<div class="data-info">' +
                                                                            '<h4>'+ (data[a].number ? data[a].number : 'None') +'</h4>' +
                                                                            '<small>Case No.</small>' +
                                                                        '</div>' +
                                                                    '</div>' +
                                                                    '<div class="col-sm-6">' +
                                                                        '<div class="data-info">' +
//                                                                            '<h4>'+ moment(data[a].date).format('ll') +'</h4>' +
                                                                            '<h4>'+ caseDate +'</h4>' +
                                                                            '<small>Case Date</small>' +
                                                                        '</div>' +
                                                                    '</div>' +
                                                                '</div>' +
                                                                '<div class="row">' +
                                                                    '<div class="col-sm-6">' +
                                                                        '<div class="data-info">' +
                                                                            '<h4>'+ (data[a].class ? data[a].class : 'None') +'</h4>' +
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
                                                                '' + counselListRow +
                                                            '</div>' + // panel-body
                                                        '</div>' +
                                                    '</div>' + // col-sm-5
                                                    '<div class="col-sm-7">' +
                                                        '<div class="panel panel-default">' +
                                                            '<div class="panel-heading">' +
                                                                'Special Fees' +
                                                                '<div class="ibox-tools pull-right">' +
                                                                    @if(true)
                                                                    '<button type="button" class="btn btn-xs btn-warning modal-open" data-id="'+ data[a].id +'" data-type="duplicate">Duplicate Fee</button>&nbsp;' +
                                                                    @endif
                                                                    @if(auth()->user()->can('add-fee-contract'))
                                                                    '<button type="button" class="btn btn-xs btn-success modal-open" data-id="'+ data[a].id +'" data-type="fee">Add Fee</button>' +
                                                                    @endif
                                                                '</div>' +
                                                            '</div>' +
                                                            '<div class="panel-body">' +
                                                                '<table class="table table-striped">' +
                                                                    '<thead>' +
                                                                        '<tr>' +
                                                                        '<th>Fee Name</th>' +
                                                                        '<th>Details</th>' +
                                                                        '<th class="text-center" style="width: 30px;"><i class="fa fa-cogs"></i></th>' +
                                                                        '</tr>' +
                                                                    '</thead>' +
                                                                    '<tbody class="fees-table"></tbody>' +
                                                                '</table>' +
                                                            '</div>' +
                                                        '</div>' +
                                                    '</div>' + // col-sm-7
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                    '');
                                    var case_fee = $('#tab-'+ data[a].id).find('table:first-child > tbody');
                                    case_fee.empty();
                                    for(var c = 0; c < data[a].fees.length; c++){
                                        var desc = new Array();
                                        var type = '';
                                        switch (data[a].fees[c].charge_type){
                                            case 'amount':
                                                type = 'Amount Only';
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].amount).format('0,0.00') +'</h4>' +
                                                    '<small>Amount</small>' +
                                                    '</div>');
                                                break;
                                            case 'document':
                                                type = 'Document';
                                                desc.push('' +
                                                    '<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].free_page).format('0,0') +'</h4>' +
                                                    '<small>No. of free Pages</small>' +
                                                    '</div>' +
                                                    '');
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].amount).format('0,0.00') +'</h4>' +
                                                    '<small>Amount Charge First</small>' +
                                                    '</div>');
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].excess_rate).format('0,0.00') +'</h4>' +
                                                    '<small>Rate in Excess</small>' +
                                                    '</div>');
                                                if(data[a].fees[c].cap_value > 0){
                                                    desc.push('<div class="data-info">' +
                                                        '<h4>'+ numeral(data[a].fees[c].cap_value).format('0,0.00') +'</h4>' +
                                                        '<small>With Cap / Cieling</small>' +
                                                        '</div>');
                                                }
                                                break;
                                            case 'time':
                                                type = 'Time';
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].amount).format('0,0.00') +'</h4>' +
                                                    '<small>Amount</small>' +
                                                    '</div>');
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].minutes).format('0,0') +'</h4>' +
                                                    '<small>Consumable / Minute</small>' +
                                                    '</div>');
                                                break;
                                            case 'installment':
                                                type = 'Installment';
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].amount).format('0,0.00') +'</h4>' +
                                                    '<small>Amount</small>' +
                                                    '</div>');
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].installment).format('0,0.00') +'</h4>' +
                                                    '<small>Installment</small>' +
                                                    '</div>');
                                                break;
                                            case 'percentage':
                                                type = 'Percentage';
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].amount).format('0,0.00') +'</h4>' +
                                                    '<small>Amount</small>' +
                                                    '</div>');
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data[a].fees[c].percentage).format('0,0') +'</h4>' +
                                                    '<small>Percentage</small>' +
                                                    '</div>');
                                                break;
                                        }

                                        var feeDetailCounsel = (data[a].fees[c].counsel_id === null) ? '' : '<h5>'+ data[a].fees[c].counsel.profile.full_name +'</h5>';
                                        var specialBill = (data[a].fees[c].special_billing === 0) ? '' : '<h5>Special Billing</h5>';
                                        case_fee.append('' +
                                            '<tr class="fee-row">' +
                                            '<td>' +
                                            ''+ specialBill +
                                            ''+ feeDetailCounsel +
                                            '<p>'+ data[a].fees[c].fee.display_name +'</p>' +
                                            '<p><small>'+ type +'</small></p>' +
                                            '</td>' +

                                            '<td class="text-left">'+ desc.join('') +'</td>' +
                                            //                                    '<td class="text-left">'+ numeral().format('0,0.00') +'</td>' +
                                            '<td>' +
                                            '<div class="btn-group text-right">' +
                                            @if(auth()->user()->can('delete-fee-contract'))
                                            '<button type="button" data-id="'+ data[a].fees[c].id +'" class="fee-delete-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> </button>' +
                                            @endif
                                            '</div>' +
                                            '</td>' +
                                            '</tr>' +
                                            '');
                                    }
                                }
                                break;
                            default:
                                if(data.fees.length > 0){
                                    $('.modal-open').hide();
                                }else{
                                    $('.modal-open').show();
                                }
                                retainerBox.find('tbody').empty();
                                for(var b = 0; b < data.fees.length; b++){
                                    var desc = new Array();
                                    var type = '';
                                    switch (data.fees[b].charge_type){
                                        case 'amount':
                                            type = 'Amount Only';
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].amount).format('0,0.00') +'</h4>' +
                                                '<small>Amount</small>' +
                                                '</div>');
                                            break;
                                        case 'document':
                                            type = 'Document';
                                            desc.push('' +
                                                '<div class="data-info">' +
                                                '<h4>'+ data.fees[b].free_page +'</h4>' +
                                                '<small>No. of free Pages</small>' +
                                                '</div>' +
                                                '');
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].amount).format('0,0.00') +'</h4>' +
                                                '<small>Amount Charge First</small>' +
                                                '</div>');
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].excess_rate).format('0,0.00') +'</h4>' +
                                                '<small>Rate in Excess</small>' +
                                                '</div>');
                                            if(data.fees[b].cap_value > 0){
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data.fees[b].cap_value).format('0,0.00') +'</h4>' +
                                                    '<small>With Cap / Cieling</small>' +
                                                    '</div>');
                                            }
                                            break;
                                        case 'time':
                                            type = 'Time';
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].amount).format('0,0.00') +'</h4>' +
                                                '<small>Amount</small>' +
                                                '</div>');
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ data.fees[b].minutes +'</h4>' +
                                                '<small>Consumable / Minute</small>' +
                                                '</div>');
                                            break;
                                        case 'installment':
                                            type = 'Installment';
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].amount).format('0,0.00') +'</h4>' +
                                                '<small>Amount</small>' +
                                                '</div>');
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].installment).format('0,0.00') +'</h4>' +
                                                '<small>Installment</small>' +
                                                '</div>');
                                            break;
                                        case 'percentage':
                                            type = 'Percentage';
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ numeral(data.fees[b].amount).format('0,0.00') +'</h4>' +
                                                '<small>Amount</small>' +
                                                '</div>');
                                            desc.push('<div class="data-info">' +
                                                '<h4>'+ data.fees[b].percentage +'%</h4>' +
                                                '<small>Percentage</small>' +
                                                '</div>');
                                            if(contract == 'billing'){
                                                desc.push('<div class="data-info">' +
                                                    '<h4>'+ numeral(data.fees[b].total).format('0,0.00') +'</h4>' +
                                                    '<small>Amount Collectible</small>' +
                                                    '</div>');
                                            }
                                            break;
                                    }

                                    retainerBox.find('tbody').append('' +
                                        '<tr>' +
                                        '<td>' +
                                        '<p>'+ data.fees[b].fee.display_name +'</p>' +
                                        '<p><small>'+ type +'</small></p>' +
                                        '</td>' +
                                        '<td class="text-left">'+ desc.join('') +'</td>' +
                                        '<td>' +
                                        '<div class="btn-group text-right">' +
                                        '<button type="button" data-id="'+ data.fees[b].id +'" class="fee-delete-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> </button>' +
                                        '</div>' +
                                        '</td>' +
                                        '</tr>' +
                                        '');
                                }
                        }
                    }
                });
            }

            var feeForm = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Fee list</label>' +
                    '<select name="fee-select" id="fee-select" class="form-control">' +
                    '<option value=""></option>' +
                    '</select>' +
                    '</div>' +
                    '';
                return data;
            };

            var caseForm = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Case List</label>' +
                    '<select name="case-select" id="case-select" class="form-control">' +
                    '<option value=""></option>' +
                    '</select>' +
                    '</div>' +
                    '';
                return data;
            };

            var caseForms = function () {
{{--                var now = '{!! \Carbon\Carbon::now()->format("m/d/Y") !!}';--}}
//                var now = contractInfo.find('input[name="start_date"]').val();
                var data = '<div class="row">' +
                    '<div class="col-md-6">' +
                        '<div class="form-group">' +
                            '<label>Case Title:</label>' +
                            '<input type="text" name="title" class="form-control required">' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label>Case Classification:</label>' +
                            '<select name="class" class="form-control required">' +
                                '<option value="">Select Status</option>' +
                                '<option value="Administrative">Administrative</option>' +
                                '<option value="Cadastral Case">Cadastral Case</option>' +
                                '<option value="Criminal">Criminal</option>' +
                                '<option value="Civil">Civil</option>' +
                                '<option value="Collection Retainer">Collection Retainer</option>' +
                                '<option value="General Retainer">General Retainer</option>' +
                                '<option value="Labor">Labor</option>' +
                                '<option value="Special Civil Action">Special Civil Action</option>' +
                                '<option value="Special Project">Special Project</option>' +
                                '<option value="Others">Others</option>' +
                            '</select>' +
                        '</div>' +
                        '<div class="row">' +
                            '<div class="col-sm-6">' +
                                '<div class="form-group">' +
                                    '<label>Case No:</label>' +
                                    '<input type="text" name="number" class="form-control required">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-sm-6">' +
                                '<div class="form-group">' +
                                    '<label>Case Date:</label>' +
                                    '<div class="input-group m-b date">' +
//                                        '<input type="text" name="date" value="'+ now +'" class="form-control required">' +
                                        '<input type="text" name="date" value="For Filing" class="form-control required" readonly>' +
                                        '<span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="form-group">' +
                            '<label>Venue:</label>' +
                            '<textarea name="venue" id="" class="form-control resize-vertical required"></textarea>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label>Case Counsel:</label>' +
                            '<select name="counsel_id" class="form-control counsel-select required"></select>' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label>Co-Counsel:</label>' +
                            '<select name="co_counsel" class="form-control co-counsel-select" data-placeholder="Select Co-Counsel..." multiple tabindex="4"></select>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '';
                return data;
            };

            var amountType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Amount</label>' +
                    '<input type="text" name="amount" class="form-control numonly separator-dec">' +
                    '</div>' +
                    '';
                return data;
            };

            var documentType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>No. of free Pages</label>' +
                    '<input type="text" name="free_page" class="form-control numonly required separator-int">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Amount Charge First</label>' +
                    '<input type="text" name="amount" class="form-control numonly required separator-dec">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Rate in Excess</label>' +
                    '<input type="text" name="excess_rate" class="form-control numonly separator-dec">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>With Cap / Cieling</label>' +
                    '<input type="text" name="cap_value" class="form-control numonly separator-dec">' +
                    '</div>' +
                    '';
                return data;
            };

            var timeType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Consumable / Minute</label>' +
                    '<input type="text" name="minutes" class="form-control numonly separator-int">' +
                    '</div>' +
                    '';
                return data;
            };

            var installmentType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Installment</label>' +
                    '<input type="text" name="installment" class="form-control numonly separator-dec">' +
                    '</div>' +
                    '';
                return data;
            };

            var percentageType = function () {
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Percentage</label>' +
                    '<input type="text" name="percentage" class="form-control numonly">' +
                    '</div>' +
                    '';
                return data;
            };

            var specialBilling = function () {
                var data = '' +
                    '<div class="form-group m-t-md">' +
                        '<div class="i-checks">' +
                            '<label class="text-success"> ' +
                            '<input type="checkbox" value="" name="special-billing" id="special-billing"> ' +
                            '<i></i> Special Billing </label>' +
                        '</div>' +
                    '</div>' +
                    '';
                return data;
            };

        });
    </script>
@endsection