@extends('layouts.master')

@section('title', 'Walk-In Charge Slip | Create')

@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/select2/select2.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}
<style type="text/css">
    #txt-total-charge {
        font-size: 50px;
        font-weight: bold;
    }
    #txt-total-expense {
        font-size: 30px;
        font-weight: bold;
    }
</style>
@endsection


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Walk-In Charge Slip</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('walk-in.charge-slip.index') !!}">Walk-In Charges Slip</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('walk-in.charge-slip.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i> Create Charge Slip</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Activity Report Sheet Information</h2>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('route'=>array('walk-in.charge-slip.post'), 'class' => 'form-horizontal'))}}
                        
                        @if(Session::has('message'))
                            <br/>
                            <div class="col-md-12">
                              <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <span class="fa fa-ok"></span><em> {!! session('message') !!}</em>
                              </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Client:</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select2" id="client-select" name="client_id" required autofocus></select>
                                        @if ($errors->has('client_id'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('client_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Address:</label>
                                    <div class="col-lg-9">
                                        <textarea class="form-control" rows="3" name="address" placeholder="Client address here . . ." required>{{ old('address') }}</textarea>
                                        @if ($errors->has('address'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" >Sr. No:</label>
                                    <div class="col-lg-5">
                                        <input name="sr_no" type="text" class="form-control" value="{{ old('sr_no') }}" required>
                                        <!-- <select class="form-control" id="sr-no-select" name="sr_no" required></select> -->
                                        @if ($errors->has('sr_no'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('sr_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Date:</label>
                                    <div class="col-lg-5">
                                        <input name="transaction_date" type="date" class="form-control" value="{{ old('transaction_date') }}" required>
                                        @if ($errors->has('transaction_date'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('transaction_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" title="Service Specification">Svc. Spec.:</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="service-specification" name="service_specification" value="{{ old('service_specification') }}" required>
                                        @if ($errors->has('service_specification'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('service_specification') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Details:</label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" rows="3" name="details" placeholder="Service Details here . . ." required>{{ old('details') }}</textarea>
                                        @if ($errors->has('details'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('details') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chargable Expense -->
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>Chargable Expenses  </h5>
                                    </div>
                                    <div class="ibox-content">
                                        <div id="ars-toolbar" style="padding: 5px;">
                                            <div class="col-lg-5" style="padding-top:10px;">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label">Fee:</label>
                                                    <div class="col-lg-9">
                                                        <select class="form-control select2" id="fee-select" name="fee"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5" style="padding-top:10px;">
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Amount:</label>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control text-right" id="fee-amount" name="Amount">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2" style="padding-top:6px;">
                                                <button type="button" id="add-fee" class="btn btn-sm btn-success pull-right" style="margin: 5px;">Add</button>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="fee-table-body">
                                                <tr class="no-table-data">
                                                    <td colspan="2" class="text-center">ADD CHARGABLE EXPENSES</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="row">
                                            <div class="col-lg-8 col-lg-offset-4">
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon bg-muted">Total Expenses:</span>
                                                    <p class="text-center" id="txt-total-expense">0</p>
                                                    <input name="total_expenses" type="hidden" class="form-control text-right" id="total-expense" 
                                                        value="0" required>
                                                    @if ($errors->has('total_expenses'))
                                                        <span class="help-block m-b-none">
                                                            <strong>{{ $errors->first('total_expenses') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <br/>
                                <br/>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Professional Fee:</label>
                                    <div class="col-lg-8">
                                        <input name="professional_fees" type="number" class="form-control text-right" id="professional-fee" required>
                                        @if ($errors->has('professional_fees'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('professional_fees') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Total Charge:</span>
                                    <p class="text-center" id="txt-total-charge">0</p>
                                    <input name="total_charges" type="hidden" class="form-control text-right" id="total-charge" value="0" required>
                                    @if ($errors->has('total_charges'))
                                        <span class="help-block m-b-none">
                                            <strong>{{ $errors->first('total_charges') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                {{Form::submit('Save', array('class'=>'btn btn-lg btn-success pull-right'))}}
                            </div>
                        </div>
                        
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Photo Uploader Form --}}
    {{-- {!! Form::open(array('route'=>'upload-image','files'=>'true','id'=>'image_uploader','class'=>'sr-only')) !!}
    {!! Form::file('photo',array('id'=>'photo_file_input')) !!}
    {!! Form::close() !!} --}}

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('js/jquery.inputmask.bundle.js') !!}
    {!! Html::script('js/computeTime.js') !!}

    {!! Html::script('js/jquery.form.min.js') !!}
    {!! Html::script('js/image-uploader.js') !!}
    <script>
        $(document).ready(function(){
            var $clientSelect = $('#client-select'),
                $serviceSpec = $('#service-specification'),
                $feeSelect = $('#fee-select'),
                $feeAmount = $("#fee-amount"),
                $addFee = $("#add-fee"),
                $feeTableBody = $("#fee-table-body"),
                $table = $("#table-bordered"),
                $totalCharge = $("#total-charge"),
                $totalExpense = $("#total-expense"),
                $txtTotalExpense = $("#txt-total-expense"),
                $professionalFee = $("#professional-fee"),
                $txtTotalCharge = $("#txt-total-charge"),
                $totalChargeAmount = 0,
                $totalAmountExpense = 0;

            $feeSelect.select2({
              ajax: {
                url: '{!! route("general.fees") !!}',
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
              },
              placeholder: 'Search Service Specification',
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              minimumInputLength: 2
            });

            $clientSelect.select2({
              ajax: {
                url: '{!! route("clients-list-select2") !!}?user_type="w"',
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
              },
              placeholder: 'Search Client',
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              minimumInputLength: 2
            });

            $professionalFee.on('keyup', function() {
                var thisAmount = $(this).val();

                if (thisAmount == '') {
                    thisAmount = 0;
                }

                $totalCharge.val(parseFloat(thisAmount) + $totalAmountExpense);
                $txtTotalCharge.text(parseFloat(thisAmount) + $totalAmountExpense);
            });

            $addFee.on('click', function() {
                var id = $feeSelect.val(), text = $('#fee-select :selected').text(), amount = parseFloat($feeAmount.val());
                if (text == '' || amount == '') {
                    alert('Please select the fee and the corresponding ammount!');
                    return;
                }
                
                $feeTableBody.show();
                $(".no-table-data").hide();
                $totalChargeAmount += amount;
                $totalAmountExpense += amount;
                $totalExpense.val($totalChargeAmount);
                $txtTotalExpense.text($totalChargeAmount);

                var thisProfessionalFee = $professionalFee.val();
                if (thisProfessionalFee == ''){
                    thisProfessionalFee = 0;
                }
                $totalCharge.val(parseFloat(thisProfessionalFee) + $totalAmountExpense);
                $txtTotalCharge.text(parseFloat(thisProfessionalFee) + $totalAmountExpense);

                var $html = "<tr><td><input name='chargable_expense_text[]' type='text' class='form-control' value='" + text + "' required>";
                    $html += "<input name='chargable_expense_id[]' type='hidden' class='form-control' value='" + id + "'></td>";
                    $html += "<td><input name='chargable_expense_amount[]' type='number' class='form-control text-right' value='" + amount + "' required> </td></tr>";

                $feeTableBody.append($html);
            });

            $(document).on('focus', '.select2', function (e) {
              if (e.originalEvent) {
                $(this).siblings('select').select2('open');    
              } 
            });            

            // $(document).on('click', ".delete-description", function(){
            //     var data = $(this).data();
            //     $('#'+data.target).remove();
            // });
        });
    </script>
@endsection
