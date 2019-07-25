@extends('layouts.master')

@section('title', 'Walk-In Charge Slip | Create')

@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/select2/select2.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
<style type="text/css">
    #txt-total-charge,
    #txt-total-expense {
        margin: 0px;
        border: 1px solid;
    }
    #txt-total-charge {
        font-size: 30px;
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


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Create ars</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('walk-in.charge-slip.index') !!}">Walk-In Charges Slip</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Walk-in Charge Slip Information</h2>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('method' => 'patch', 'url'=>route('walk-in.charge-slip.update', ['id' => $walkInChargeSlip->id]), 'class' => 'form-horizontal', 'id' => 'walk-in-charge-slip-frm'))}}
                        
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
                                        <textarea class="form-control" rows="3" name="address" placeholder="Client address here . . ." required>{{ $walkInChargeSlip->address }}</textarea>
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
                                    <label class="col-lg-3 control-label" >CS #:</label>
                                    <div class="col-lg-5">
                                        <input name="charge_slip_no" type="text" class="form-control" value="{{ $walkInChargeSlip->charge_slip_no }}" required>
                                        @if ($errors->has('charge_slip_no'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('charge_slip_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Date:</label>
                                    <div class="col-lg-5">
                                        <input name="transaction_date" type="date" class="form-control" value="{{ $walkInChargeSlip->transaction_date->format('Y-m-d') }}" required>
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
                                    <label class="col-lg-2 control-label" title="Service Specification">Reporter:</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="reporter" name="reporter" value="{{ $walkInChargeSlip->reporter }}" required>
                                        @if ($errors->has('reporter'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('reporter') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" title="Service Specification">Svc. Spec.:</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="service-specification" name="service_specification" value="{{ $walkInChargeSlip->service_specification }}" required>
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
                                        <textarea class="form-control" rows="3" name="details" placeholder="Service Details here . . ." required>{{ $walkInChargeSlip->details }}</textarea>
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
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="fee-table-body">
                                                @foreach ($walkInChargeSlip->transactionFees as $index => $tf)
                                                    @if ($generalFeeId == $tf->fee_cat_id)
                                                    @endif
                                                    @if ($tf->fee_id != 47)
                                                        <tr id='walk-in-fee-tr-{{$index}}'>
                                                            <td>
                                                                <input name='chargable_expense_text[]' type='text' class='form-control' value='{{$tf->fee->display_name}}' required>
                                                                <input name='chargable_expense_id[]' type='hidden' class='form-control' value='{{$tf->fee->id}}'>
                                                            </td>
                                                            <td>
                                                                <input name='chargable_expense_amount[]' type='number' class='form-control text-right' value='{{$tf->amount}}' required> 
                                                            </td>
                                                            <td> 
                                                                <button type='button' class='btn btn-xs btn-danger delete-walk-in-fee' data-target='walk-in-fee-tr-{{$index}}''' title='Delete'><i class='fa fa-times'></i></button> 
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="row">
                                            <div class="col-lg-8 col-lg-offset-4">
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon bg-muted">Total Expenses:</span>
                                                    <p class="text-center" id="txt-total-expense">@money($walkInChargeSlip->total_expenses)</p>
                                                    <input name="total_expenses" type="hidden" class="form-control text-right" id="total-expense" 
                                                        value="{{$walkInChargeSlip->total_expenses}}" required>
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
                                        <input name="professional_fees" type="number" class="form-control text-right" id="professional-fee" value="{{$walkInChargeSlip->professional_fees}}" required>
                                        @if ($errors->has('professional_fees'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('professional_fees') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="input-group m-b">
                                    <span class="input-group-addon bg-muted">Total Charge:</span>
                                    <p class="text-center" id="txt-total-charge">@money($walkInChargeSlip->total_charges)</p>
                                    <input name="total_charges" type="hidden" class="form-control text-right" id="total-charge" value="{{$walkInChargeSlip->total_charges}}" required>
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
                                <button type="button" class="btn btn-lg btn-warning" id="print-pdf">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                {{Form::submit('Save', array('class'=>'btn btn-lg btn-success pull-right'))}}
                            </div>
                        </div>
                        
                        {{Form::close()}}
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
            <h4 class="modal-title">Activity Report Sheet Viewer</h4>
          </div>
          <div class="modal-body">
            <!-- <div style="min-height: 545px; overflow: scroll;" id="pdf-container"> -->
                <!-- <div id="pdfViewer" width="100%" height="100%"> -->
                    <embed id="pdf-embed" width="100%" height="100%">
                <!-- </div> -->
            <!-- </div> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('js/jquery.inputmask.bundle.js') !!}
    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
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
                $totalChargeAmount = parseFloat('@money($walkInChargeSlip->total_charges)'),
                $totalAmountExpense = parseFloat('@money($walkInChargeSlip->total_expenses)');

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
                url: '{!! route("clients-list-select2") !!}?client_type=w',
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
              },
              placeholder: 'Search Client',
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              minimumInputLength: 2
            }).on('select2:select', function (e) {
                var data = e.params.data;
                // console.log(data, 'selected data');
                
                $("#address").val(data.client_business_address);
            });

            var updateTotalCharge = function() {
                var thisAmount = $professionalFee.val();

                if (thisAmount == '') {
                    thisAmount = 0;
                }

                console.log(thisAmount, $totalAmountExpense);
                var $tempTotal = parseFloat(parseFloat(thisAmount) + ( ($totalAmountExpense) ? $totalAmountExpense : 0.00) ).toFixed(2);
                $totalCharge.val($tempTotal);
                $txtTotalCharge.text($tempTotal);
            }

            $professionalFee.on('keyup', function() {
                updateTotalCharge();
            });

            $addFee.on('click', function() {
                var id = $feeSelect.val(), text = $('#fee-select :selected').text(), amount = parseFloat($feeAmount.val());
                if (text == '' || amount == '') {
                    alert('Please select the fee and the corresponding ammount!');
                    return;
                }
                
                $feeTableBody.show();
                $totalChargeAmount += amount;
                $totalAmountExpense += amount;
                $totalExpense.val(parseFloat($totalChargeAmount).toFixed(2));
                $txtTotalExpense.text(parseFloat($totalChargeAmount).toFixed(2));

                // update total Charge
                updateTotalCharge();

                var $html = "<tr><td><input name='chargable_expense_text[]' type='text' class='form-control' value='" + text + "' required>";
                    $html += "<input name='chargable_expense_id[]' type='hidden' class='form-control' value='" + id + "'></td>";
                    $html += "<td><input name='chargable_expense_amount[]' type='number' class='form-control chargable-amount text-right' min='0'  value='" + amount + "' required> </td></tr>";

                $feeTableBody.append($html);
            });

            @if($walkInChargeSlip->client)
                $clientSelect.append('<option value="{{$walkInChargeSlip->client_id}}">{{$walkInChargeSlip->client->profile->full_name}}</option>').val('{{$walkInChargeSlip->client_id}}').trigger('change');
            @endif

            $(document).on('click', '.delete-walk-in-fee', function (e) {
                var data = $(this).data();
                var amount = $("#"+data.target+" .chargable-amount").val();

                $totalChargeAmount -= parseFloat(amount);
                $totalAmountExpense -= parseFloat(amount);

                $totalExpense.val(($totalChargeAmount) ? parseFloat($totalChargeAmount).toFixed(2) : '0.00');
                $txtTotalExpense.text(($totalChargeAmount) ? parseFloat($totalChargeAmount).toFixed(2) : '0.00');
                $("#"+data.target).remove();
                updateTotalCharge();
            }); 

            $("#walk-in-charge-slip-frm").on('submit', function(e){

                if (!$totalAmountExpense) {
                    swal({
                        title: 'NO Chargable Expenses!',
                        text: 'Add at least one (1) Chargable Expenses.',
                        type: 'warning',
                    });
                    e.preventDefault();
                }
            });

            $(document).on('focus', '.select2', function (e) {
              if (e.originalEvent) {
                $(this).siblings('select').select2('open');    
              } 
            });

            $("#print-pdf").on('click', function(){
                $('#view-pdf').modal('show').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(".modal-content").height());
                });

                $("#pdf-embed").attr('src', "{{route('walk-in.charge-slip.print', [$walkInChargeSlip->id])}}");
            });           

            $(document).on('blur', ".chargable-amount", function(){
                $totalChargeAmount = 0;
                $totalAmountExpense = 0;
                $(".chargable-amount").each(function( index ) {
                    // console.log(index + ':' + parseFloat($(this).val()));
                    $totalChargeAmount  += parseFloat($(this).val());
                    $totalAmountExpense += parseFloat($(this).val());
                });

                $totalExpense.val(parseFloat($totalChargeAmount).toFixed(2));
                $txtTotalExpense.text(parseFloat($totalChargeAmount).toFixed(2));
            });
        });
    </script>
@endsection
