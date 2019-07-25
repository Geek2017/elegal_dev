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
                            <div class="ibox-title">
                                <h5>Contract Information</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="form-group">
                                    <label>Type of Contract:</label>
                                    {{Form::select('contract_type', array(null => 'Select Retainer','Special' => 'Special','General' => 'General'),($data->status === 'Ongoing') ? $data->contract->contract_type : null,array('class'=>'form-control required'))}}
                                </div>
                                <div class="form-group">
                                    <label>Contract Date:</label>
                                    <div class="input-group m-b date">
                                        <input type="text" name="contract_date" value="{!! ($data->status === 'Ongoing') ? \Carbon\Carbon::parse($data->contract->contract_date)->format('m/d/Y') : '' !!}" class="form-control required">
                                        <span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Start Date:</label>
                                    <div class="input-group date">
                                        <input type="text" name="start_date" value="{!! ($data->status === 'Ongoing') ? \Carbon\Carbon::parse($data->contract->start_date)->format('m/d/Y') : '' !!}" class="form-control required">
                                        <span class="input-group-addon bg-muted"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Other Condition:</label>
                                    <textarea name="other_conditions" class="form-control resize-vertical">{!! ($data->status === 'Ongoing') ? $data->contract->other_conditions : '' !!}</textarea>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="form-group">
                                    <div class="input-group m-b total-box">
                                        <span class="input-group-addon bg-muted">Grand Total:</span>
                                        <label class="form-control text-success" id="contract-total" data-total="0">0.00</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Fixed Price:</label>
                                    <input type="text" value="0" class="form-control numonly">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-sm-9">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Case Information</h5>
                        <div class="ibox-tools">
                            <button type="button" data-type="add" class="case-action-btn btn btn-xs btn-white"><i class="fa fa-plus text-primary"></i> Add</button>
                            <button type="button" data-type="edit" class="case-action-btn btn btn-xs btn-white"><i class="fa fa-pencil text-success"></i> Edit</button>
                            <button type="button" data-type="delete" class="case-action-btn btn btn-xs btn-white"><i class="fa fa-times text-danger"></i> Delete</button>
                        </div>
                    </div>
                    <div class="ibox-content">

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
                    <button type="button" class="btn btn-primary" id="btn-store-case">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="fee-modal" data-id="0" data-action="add" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Additional Fees</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h2 class="no-margins">Fee Details</h2>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group fee-list"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon bg-muted">Charge Type:</span>
                                    <div class="input-group-btn input-group-select">
                                        <select name="charge_type" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Standard">Standard</option>
                                            <option value="Special">Special</option>
                                            <option value="Installment">Installment</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Free Pages:</span>
                                    <input type="text" name="free_page" placeholder="0" class="form-control numonly">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">First 5 Pages:</span>
                                    <input type="text" name="charge_doc" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Rate No. 1:</span>
                                    <input type="text" name="rate_1" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Rate No. 2:</span>
                                    <input type="text" name="rate_2" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Fixed Amount</span>
                                    <input type="text" name="rate" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Consumable | Min</span>
                                    <input type="text" name="consumable_time" placeholder="0" class="form-control numonly">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Excess Rate</span>
                                    <input type="text" name="excess_rate" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Fixed Amount</span>
                                    <input type="text" name="amount" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">W/ CAP or Ceiling</span>
                                    <input type="text" name="cap_value" placeholder="0.00" class="form-control numonly least">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-store-fee">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="special" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <p><strong>Lorem Ipsum is simply dummy</strong> text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
                        printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged.</p>
                    <div class="form-group"><label>Sample Input</label> <input type="email" placeholder="Enter your email" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    <script>
        $(document).ready(function(){
            var caseModal = $('#case-modal');
            var feeModal = $('#fee-modal');
            var tran_id = '{!! $data->id !!}';
            loadCases();

            $('.input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
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
                                    counsel_select.append('<option value="'+ data[1][a].id +'">'+ data[1][a].fname +' '+ data[1][a].lname +'</option>');
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
                    default:
                        $.get('{!! route('create-case') !!}',{
                            id: tran_id
                        },function(data){
                            caseModal.data('id',data[0].id)
                            var counsel_select = $('.counsel-select');
                            counsel_select.empty().append('<option value="">Select Counsel</option>');
                            for(var a = 0; a < data[1].length; a++){
                                counsel_select.append('<option value="'+ data[1][a].id +'">'+ data[1][a].fname +' '+ data[1][a].lname +'</option>');
                            }
                            loadCounsel();
                            caseModal.modal({backdrop: 'static', keyboard: false});
                        });
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

            $(document).on('click','.add-fee',function(){
                $.get('{!! route('fees') !!}',function(data){
                    if(data.length != 0){
//                        console.log(data);
                        feeModal.find('.fee-list').empty();
                        for(var a = 0; a < data.length; a++){
                            var checked = (a === 0) ? 'checked' : '';
                            feeModal.find('.fee-list').append('<div class="i-checks"><label> <input type="radio" value="'+ data[a].id +'" name="fee" '+ checked +'> <i></i> '+ data[a].display_name +' </label></div>');
                        }
                        $('.i-checks').iCheck({
                            radioClass: 'iradio_square-green',
                        });
                    }
                });
                feeModal.data('id',$(this).data('id'));
                feeModal.modal({backdrop: 'static', keyboard: false});
            });

            $(document).on('click','#btn-store-fee',function(){
                if(feeValidator() < 1){
                    $.post('{!! route('case-fee-store') !!}', {
                        _token: '{!! csrf_token() !!}',
                        transaction_id: tran_id,
                        fee_id: feeModal.find('input[name="fee"]:checked').val(),
                        case_id: feeModal.data('id'),
                        action: feeModal.data('action'),
                        charge_type: feeModal.find('select[name="charge_type"]').val(),
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
                        if(data[0].length != 0){
                            loadCases(data[0].cases.id);
                        }
                        feeModal.modal('toggle');
                        toastr.success('Successful!','Fee added successfully!');
                    });
                }
            });

            $(document).on('click','.fee-action-btn',function(){
                $.get('{!! route('tran-fee-action') !!}',{
                    id: $(this).data('id'),
                    action: $(this).data('action')
                },function(data){
                    if(data.length != 0) {
                        if(data[0] == 'edit'){
                            feeModal.data('id',data[1].id);
                            feeModal.data('action','edit');
                            feeModal.find('select[name="charge_type"]').val(data[1].charge_type);
                            feeModal.find('select[name="status"]').val(data[1].status);
                            feeModal.find('input[name="free_page"]').val(data[1].free_page);
                            feeModal.find('input[name="charge_doc"]').val(data[1].charge_doc);
                            feeModal.find('input[name="rate_1"]').val(data[1].rate_1);
                            feeModal.find('input[name="rate_2"]').val(data[1].rate_2);
                            feeModal.find('input[name="rate"]').val(data[1].rate);
                            feeModal.find('input[name="consumable_time"]').val(data[1].consumable_time);
                            feeModal.find('input[name="excess_rate"]').val(data[1].excess_rate);
                            feeModal.find('input[name="amount"]').val(data[1].amount);
                            feeModal.find('input[name="cap_value"]').val(data[1].cap_value);
                            feeModal.find('.fee-list').append('<div class="i-checks"><label> <input type="radio" value="'+ data[1].fee.id +'" name="fee" checked> <i></i> '+ data[1].fee.display_name +' </label></div>');
                            $('.i-checks').iCheck({
                                radioClass: 'iradio_square-green',
                            });
                            feeModal.modal({backdrop: 'static', keyboard: false});
                        }
                        if(data[0] == 'delete'){
                            loadCases(data[1]);
                        }
                    }

                });
            });

            $(document).on('click','#save-contract-btn',function(){
                if(contractValidator() < 1){
                    var contract = $('#contract-info');
                    var action = $(this).data('action');
                    $.post('{!! route('contract-store') !!}',{
                        _token: '{!! csrf_token() !!}',
                        id: tran_id,
                        action: action,
                        contract_type: contract.find('select[name="contract_type"]').val(),
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

            caseModal.on('hidden.bs.modal', function () {
                $(this).data('id',0);
                $(this).data('action','add');
                $(this).find('textarea').val('');
                $(this).find('input').val('');
                $(this).find('select option:first-child').attr("selected", "selected");
                $(this).find('.counsel-select').empty();
            });

            feeModal.on('hidden.bs.modal', function () {
                $(this).data('id',0);
                $(this).data('action','add');
                $(this).find('input').val('');
                $(this).find('select').val('');
                $(this).find('.fee-list').empty();
            });

            fundModal.on('hidden.bs.modal', function () {
                $(this).find('input').val('');
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
                                    '<td>'+ data[a].info.fname +' '+ data[a].info.lname +'</td>' +
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
                    console.log('load: '+ case_id);
                    if(data.length != 0){
                        $('.case-action-btn').each(function(){
                            if($(this).data('type') != 'add'){
                                $(this).show();
                            }
                        });
                        $('.tabs-container > .nav, .tabs-container > .tab-content').empty();
                        var grandTotal = 0;
                        for(var a = 0; a < data.length; a++){
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
                                '<div class="col-lg-6">' +
                                '<dl class="dl-horizontal">' +
                                '<dt>Title:</dt> <dd>'+ data[a].title +'</dd>' +
                                '<dt>Venue:</dt> <dd>'+ data[a].venue +'</dd>' +
                                '<dt>Case Date:</dt> <dd>'+ data[a].date +'</dd>' +
                                '</dl>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                '<dl class="dl-horizontal">' +
                                '<dt>Case Number:</dt> <dd>'+ data[a].number +'</dd>' +
                                '<dt>Case Classification:</dt> <dd>'+ data[a].class +'</dd>' +
                                '<dt>Status:</dt> <dd>'+ data[a].status +'</dd>' +
                                '</dl>' +
                                '</div>' +
                                '</div>' +
                                '<div class="hr-line-dashed"></div>' +
                                '<div class="row">' +
                                '<div class="col-sm-12">' +
                                '<div class="pull-left">' +
                                '<h2>Fees Detail</h2>' +
                                '</div>' +
                                '<div class="pull-right">' +
                                '<button type="button" class="btn btn-xs btn-success add-fee" data-id="'+ data[a].id +'">Add Fee</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="table-responsive m-t">' +
                                '<table class="table table-striped">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Fee Name</th>' +
                                '<th>Charge Type</th>' +
                                '<th class="text-right">Free Page</th>' +
                                '<th class="text-right">Doc. Charge</th>' +
                                '<th class="text-right">Rate 01</th>' +
                                '<th class="text-right">Rate 02</th>' +
                                '<th class="text-right">Rate</th>' +
                                '<th class="text-right">Cons. Time</th>' +
                                '<th class="text-right">Excess Rate</th>' +
                                '<th class="text-right">Amount</th>' +
                                '<th class="text-right">Cap</th>' +
                                '<th class="text-right">Total</th>' +
                                '<th class="text-center" style="width: 60px;"><i class="fa fa-cogs"></i></th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody class="fees-table"></tbody>' +
                                '</table>' +
                                '</div>' +
                                '<table class="table invoice-total">' +
                                '<tbody>' +
                                '<tr>' +
                                '<td><strong>TOTAL :</strong></td>' +
                                '<td><h2 class="fees-total text-success" data-total="0">00.00</h2></td>' +
                                '</tr>' +
                                '</tbody>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '');

                            if(data[a].fees.length != 0){
                                content.find('#tab-'+ data[a].id).find('.fees-table').empty();
                                var gtotal = 0;
                                for(var b = 0; b < data[a].fees.length; b++){
                                    var total = 0;
                                    total += parseFloat(data[a].fees[b].charge_doc);
                                    total += parseFloat(data[a].fees[b].rate_1);
                                    total += parseFloat(data[a].fees[b].rate_2);
                                    total += parseFloat(data[a].fees[b].rate);
                                    total += parseFloat(data[a].fees[b].excess_rate);
                                    total += parseFloat(data[a].fees[b].amount);
                                    total += parseFloat(data[a].fees[b].cap_value);
                                    content.find('#tab-'+ data[a].id).find('.fees-table').append('' +
                                        '<tr class="fee-row">' +
                                        '<td>'+ data[a].fees[b].fee.display_name +'</td>' +
                                        '<td>'+ data[a].fees[b].charge_type +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].free_page +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].charge_doc +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].rate_1 +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].rate_2 +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].rate +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].consumable_time +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].excess_rate +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].amount +'</td>' +
                                        '<td class="text-right">'+ data[a].fees[b].cap_value +'</td>' +
                                        '<td class="text-right">'+ numeral(total).format('0,0.00') +'</td>' +
                                        '<td>' +
                                        '<div class="btn-group text-right">' +
                                        '<button data-id="'+ data[a].fees[b].id +'" data-action="edit" type="button" class="fee-action-btn btn-white btn btn-xs"><i class="fa fa-pencil text-success"></i> </button>' +
                                        '<button data-id="'+ data[a].fees[b].id +'" data-action="delete" type="button" class="fee-action-btn btn-white btn btn-xs"><i class="fa fa-times text-danger"></i> </button>' +
                                        '</div>' +
                                        '</td>' +
                                        '</tr>' +
                                        '');
                                    gtotal += total;
                                    console.log('total: '+ total);
                                }
                                content.find('#tab-'+ data[a].id).find('.fees-total').empty().text(numeral(gtotal).format('0,0.00'));
                            }else{
                                content.find('#tab-'+ data[a].id).find('.fees-table').empty().append('' +
                                    '<tr>' +
                                    '<td colspan="11" class="text-center"><h2>No record found</h2></td>' +
                                    '</tr>' +
                                    '');
                                content.find('#tab-'+ data[a].id).find('.invoice-total').hide();
                            }
                            grandTotal += gtotal;
                            console.log('gtotal: '+ gtotal);
                        }
                        $('#contract-total').empty().text(numeral(grandTotal).format('0,0.00'));
                        console.log('grandTotal: '+ grandTotal);
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

            var feeValidator = function(){
                feeModal.find('.form-group').removeClass('has-error');
                var count = 0;
                var least = 0;
                if(!feeModal.find('select[name="charge_type"]').val()){
                    count += 1;
                    feeModal.find('select[name="charge_type"]').closest('.form-group').addClass('has-error');
                    toastr.error('Required!','Invalid Charge Type!');
                }
                feeModal.find('.least').each(function(){
                    least += parseFloat($(this).val()||0);
                });
                if(least < 1){
                    count += 1;
                    feeModal.find('.least').closest('.form-group').addClass('has-error');
                    toastr.warning('Required!','Put at least one payment!');
                }
                if(count > 1){
                    setTimeout(function(){
                        $('.form-group').removeClass('has-error');
                    }, 6000);
                }
                return count;
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

//                if($('#billing-address').length){
//                    $('#billing-address').addClass('shake');
//                    count += 1;
//                    toastr.error('Required!','Update Billing address!');
//                }

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

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }

        });
    </script>
@endsection