@extends('layouts.master')

@section('title', 'Activity Report Sheet | Create')

@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/select2/select2.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}
{!! Html::style('css/plugins/clockpicker/clockpicker.css') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
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
                    <a href="{!! route('ars.index') !!}">ars List</a>
                </li>
                <li class="active">
                    <strong>Create ars</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <!-- animated fadeInRight -->
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Activity Report Sheet Information</h2>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('route'=>array('ars.store'), 'class' => 'form-horizontal'))}}
                        
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
                                    <label class="col-lg-2 control-label">Client:</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" id="client-select" name="client_id" required></select>
                                        @if ($errors->has('client_id'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('client_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- id="case-no-div" -->
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Case No.:</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" id="case-select" name="case_management_id" >
                                            <option selected="">-SELECT CASE-</option>
                                        </select>
                                        <small style="color: red"> <strong>Note:</strong> Select a client before selecting Case No.</small>
                                        @if ($errors->has('case_management_id'))
                                            <span class="help-block m-b-none">
                                                <strong>{{ $errors->first('case_management_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Date:</label>
                                        <div class="col-lg-10">
                                            <input name="ars_date" type="date" class="form-control" value="{{ $currentDate }}" required>
                                            @if ($errors->has('ars_date'))
                                                <span class="help-block m-b-none">
                                                    <strong>{{ $errors->first('ars_date') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">No:</label>
                                        <div class="col-lg-10">
                                            <input name="ars_no" type="text" class="form-control text-right" value="{{ $prefixArsNo.'-'.$arsNo->ars_no_max }}" disabled required>
                                            <input name="ars_no_hidden" type="hidden" class="form-control text-right" value="{{ $arsNo->ars_no_max }}">
                                            @if ($errors->has('ars_no'))
                                                <span class="help-block m-b-none">
                                                    <strong>{{ $errors->first('ars_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    

                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Case/Project Name</span>
                                        <input name="case_project_name" id="case-title" type="text" class="form-control" value="{{ old('case_project_name') }}" required>
                                    </div>
                                    @if ($errors->has('case_project_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('case_project_name') }}</strong>
                                        </span>
                                    @endif
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Case #/Venue</span>
                                        <input name="docket_no_venue" id="case-number" type="text" class="form-control" value="{{ old('docket_no_venue') }}" required>
                                    </div>
                                    @if ($errors->has('docket_no_venue'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('docket_no_venue') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Reporter</span>
                                        <input name="reporter" id="counsel-name" type="text" class="form-control" value="{{ old('reporter') }}">
                                    </div>
                                    @if ($errors->has('reporter'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('reporter') }}</strong>
                                        </span>
                                    @endif

                                    <!-- <div class="input-group m-b"><span class="input-group-addon bg-muted">Reporter</span>
                                        <input name="reporter" type="text" class="form-control" value="{{ old('reporter') }}" required>
                                    </div> -->
                                    <!-- <div class="input-group m-b"><span class="input-group-addon bg-muted">Gr Title</span>
                                        <input name="gr_title" type="text" class="form-control" value="{{ old('gr_title') }}" required>
                                    </div>
                                    @if ($errors->has('gr_title'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('gr_title') }}</strong>
                                        </span>
                                    @endif -->
                                    <!-- This should be a select showing the list of client -->
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12" style="margin-left: 1.5%">
                                <h3>Activity Description</h3>  

                                <small>(Describe in the outline format activity performed with sufficient details as to persons, date, location, subject matter, and purpose)</small>  
                            </div>
                        </div>

                        <!-- Activity Description -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div id="ars-toolbar">
                                        <div>
                                            <button type="button" id="add-new-ars-desc" class="btn btn-sm btn-primary pull-right" style="margin: 5px;">Add New +</button>
                                        </div>
                                    </div>
                                    <div class="table-bordered">
                                        <table id="ars-desc" class="table table-striped table-bordered table-hover" data-toolbar="ars-toolbar">
                                        <thead>
                                          <tr>
                                            <th>#</th>
                                            <th>Description</th>
                                            <th></th>
                                          </tr>
                                        </thead>
                                        <tbody id="ars-desc-body">
                                            <tr>
                                                <td>1</td>
                                                <td><textarea rows="2" name='descriptions[]' type='text' class='form-control' id="activity-first-description" required></textarea></td>
                                                <!-- <td><button class='btn btn-xs btn-danger' onclick='delete(1)' title='Delete'><i class='fa fa-times'></i></button></td> -->
                                            </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br/>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-3">
                                    <div class="input-group time-picker" id="time-picker-1" data-autoclose="true">
                                        <span class="input-group-addon bg-muted">Time Start</span>
                                        <input name="time_start" type="text" class="form-control time-mask" id="time-start" value="{{ old('time_start') }}">
                                        <span class="input-group-addon">
                                            <span class="fa fa-clock-o"></span>
                                        </span>
                                    </div>
                                    
                                    @if ($errors->has('time_start'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('time_start') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group time-picker" id="time-picker-2" data-autoclose="true">
                                        <span class="input-group-addon bg-muted">Time Finish</span>
                                        <input name="time_finnish" type="text" class="form-control time-mask" id="time-finish" value="{{ old('time_finnish') }}" data-mask="99:99">
                                        <span class="input-group-addon">
                                            <span class="fa fa-clock-o"></span>
                                        </span>
                                    </div>                                    
                                    
                                    @if ($errors->has('time_finnish'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('time_finnish') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Duration</span>
                                        <input name="duration" type="text" class="form-control" id="duration" value="{{ old('duration') }}">
                                    </div>
                                    @if ($errors->has('duration'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('duration') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Sr. No.:</span>
                                        <input type="text" name="sr_no" id="sr-no"  class="form-control" required>
                                        <input type="hidden" name="sr_id" id="sr-id" required>
                                    </div>
                                    @if ($errors->has('sr_no'))
                                        <span class="help-block m-b-none">
                                            <strong>{{ $errors->first('sr_no') }}</strong>
                                        </span>
                                    @endif
                                    <!-- <div class="form-group">
                                        <label class="col-lg-3" title="Service Report No." style="padding: 7px 7px;">Sr. No.:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="sr_no" id="sr-no" required>
                                            <input type="hidden" name="sr_id" id="sr-id" required>
                                            <select class="form-control" id="sr-id-select" name="sr_id" required></select>
                                            
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <div class="col-lg-12" style="margin-left: 1.5%">
                                <h3>Billing Instruction <small>to be accomplish by Supervisor Partner only</small></h3>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-2">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" id="chk-non-billable" name="billing_instruction_type" class="billing-ins-type" value="Non-Billable" />
                                        <label for="chk-non-billable">
                                            Non-Billable
                                        </label>
                                    </div>

                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" id="chk-billable" name="billing_instruction_type" class="billing-ins-type" value="Billable" />
                                        <label for="chk-billable">
                                            Billable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Explanation</label>
                                    <textarea rows="2" name='billing_instruction' class='form-control'></textarea>
                                    @if ($errors->has('billing_instruction'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('billing_instruction') }}</strong>
                                        </span>
                                    @endif                                    
                                </div>
                                <div class="col-lg-6">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" id="chk-appearance" name="billing_instruction_type" class="billing-ins-type" value="Appearance" />
                                        <label for="chk-appearance">
                                            Appearance
                                        </label>
                                    </div>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" id="chk-time-trate" name="billing_instruction_type" class="billing-ins-type" value="Time-Trate" />
                                        <label for="chk-time-trate">
                                            Time-Rate
                                        </label>
                                        <span>
                                            Appearance/Documentation/Study/
                                             <br>
                                             Meeting/Teleconference   
                                        </span>
                                        <span>
                                            Billable
                                            <input id="billable-time" name="billable_time" type="text" value="{{ old('billable_time') }}" disabled>
                                            mins
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Billing Entry</span>
                                        <textarea rows="2" name='billing_entry' class='form-control'></textarea>
                                    </div>
                                    @if ($errors->has('billing_entry'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('billing_entry') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-6">
                                    <div id="ars-outcome-toolbar">
                                        <div>
                                            <h2 class="text-center">Outcome/Output</h2>
                                            <button type="button" id="add-new-outcome" class="btn btn-sm btn-primary pull-right" style="margin: 5px;">Add New +</button>
                                        </div>
                                    </div>
                                    <div class="table-bordered">
                                        <table id="ars-outcome" class="table table-striped table-bordered table-hover" data-toolbar="ars-outcome-toolbar">
                                        <thead>
                                          <tr>
                                            <th>#</th>
                                            <th>Description</th>
                                            <th></th>
                                          </tr>
                                        </thead>
                                        <tbody id="ars-outcome-body">
                                            <tr>
                                                <td>1</td>
                                                <td><textarea rows="2" name='outcomes[]' type='text' class='form-control' required></textarea></td>
                                                <td></td>
                                                <!-- <td><button class='btn btn-xs btn-danger' onclick='delete(1)' title='Delete'><i class='fa fa-times'></i></button></td> -->
                                            </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div id="ars-act-toolbar">
                                        <div>
                                            <h2 class="text-center">Future Activity/Date</h2>
                                            <button type="button" id="add-new-act-date" class="btn btn-sm btn-primary pull-right" style="margin: 5px;">Add New +</button>
                                        </div>
                                    </div>
                                    <div class="table-bordered">
                                        <table id="ars-act-date" class="table table-striped table-bordered table-hover" data-toolbar="ars-act-toolbar">
                                        <thead>
                                          <tr>
                                            <th>#</th>
                                            <th>Description</th>
                                            <th></th>
                                          </tr>
                                        </thead>
                                        <tbody id="ars-act-date-body">
                                            <tr>
                                                <td>1</td>
                                                <td><textarea rows="2" name='feacture_activities[]' type='text' class='form-control' required></textarea></td>
                                                <td></td>
                                                <!-- <td><button class='btn btn-xs btn-danger' onclick='delete(1)' title='Delete'><i class='fa fa-times'></i></button></td> -->
                                            </tr>
                                        </tbody>
                                      </table>
                                    </div>
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

@section('scripts')
    {!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('js/jquery.inputmask.bundle.js') !!}
    {!! Html::script('js/plugins/clockpicker/clockpicker.js') !!}
    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
    {!! Html::script('js/computeTime.js') !!}

    {!! Html::script('js/jquery.form.min.js') !!}
    {!! Html::script('js/image-uploader.js') !!}
    <script>
        $(document).ready(function(){
            var $startIndex = 2,
                $startIndexOutcome = 2,
                $startIndexActivity = 2,
                $table = $('#ars-desc'),
                $tableOutcome = $('#ars-outcome'),
                $tableOutcome = $('#ars-outcome'),
                $tableBody = $('#ars-desc-body'),
                $tableOutcomeBody = $('#ars-outcome-body'),
                $tableActBody = $('#ars-act-date-body'),
                $btnAddNewArsDec = $('#add-new-ars-desc'),
                $btnAddNewOutcome = $("#add-new-outcome"),
                $btnAddNewAct = $("#add-new-act-date"),
                $billingInsType = $(".billing-ins-type"),
                $clientSelect = $('#client-select'),
                $billableTime = $("#billable-time"),
                $timeClass = $(".time-picker"),
                $timeMask = $(".time-mask"),
                $srid = $("#sr-id"),
                $srNo = $("#sr-no"),
                $caseSelect = $("#case-select"),
                $counselName = $("#counsel-name"),
                $selectedSR = {};

            var getTime = function() {
                var $timeDiff = Converttimeformat('time-start', 'time-finish');
                var $str = '';
                var $totalHrs = $timeDiff.hrs;
                var $totalMins = ($totalHrs * 60) + $timeDiff.mins;

                // $str = ($timeDiff.hrs > 0)? $timeDiff.hrs + ' hour(s)': '';

                // $str += ($timeDiff.hrs > 0 && $timeDiff.mins > 0)? ' & ' + $timeDiff.mins + ' mins.': ( ($timeDiff.mins > 0)? $timeDiff.mins + ' mins.': '' );

                $("#duration").val($totalMins + ' mins.');
            };

            var setTimeFinishVal = function () {
                if ($('#time-finish').val() == ''){
                    var time = $('#time-start').val();
                    $('#time-finish').val(time);
                    getTime();
                    flag = true;
                }
            };

            var validationTimeFinish = function () {
                var tempDate = '01/01/2000 '; // ignores this since we only need the time

                if (flag) {
                  if (new Date (tempDate + $('#time-finish').val()) > new Date (tempDate + $('#time-start').val()) ) {
                    getTime();
                  } else {
                    var time = $('#time-start').val();
                    $('#time-finish').val(time);
                  }
                }
            };

            $timeMask.inputmask("hh:mm t", {hourFormat: 12});

            var flag = false;
            
            $('#time-picker-1').clockpicker({
                placement: 'bottom',
                align: 'right',
                autoclose: true,
                twelvehour: true,
                'default': 'now',
                afterDone: function () {  
                    setTimeFinishVal();
                }
            });

            $('#time-start').on('blur', function() {
                setTimeFinishVal();
            });

            $('#time-picker-2').clockpicker({
              placement: 'bottom',
              align: 'right',
              autoclose: true,
              twelvehour: true,
              afterDone: function () {
                validationTimeFinish();
              }
            });

            $('#time-finish').on('blur', function() {
                validationTimeFinish();
            });



            $(".time-picker").clockpicker({
                autoclose: true,
                twelvehour: true
            });

            $clientSelect.select2({
              ajax: {
                url: '{!! route("clients-list-select2") !!}',
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
              },
              placeholder: 'Search Client',
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              minimumInputLength: 2
            }).on('select2:select', function (e) {
                var data = e.params.data;
                
                if (!data.has_cases) {
                    // $("#case-no-div").hide();
                    $.get('{!! route("transactions.latest-service-reports") !!}?id='+data.id, function (transactionData) {

                        if (!transactionData.services_report.case) {
                            swal({
                                title: 'NO CASE!',
                                text: 'Selected client has no ongoing contract/s.',
                                type: 'info',
                            });
                            return;
                        }

                        if (!transactionData.services_report) {
                            swal({
                                title: 'Service Report!',
                                text: 'No service report for this client. Please add to continue',
                                type: 'info',
                            });
                            return;
                        }

                        var serviceReport = transactionData.services_report;
                        var caseTitle = (serviceReport.case) ? serviceReport.case.title : ''; 
                        var caseNoAndVenue = (serviceReport.case) ? 
                            (serviceReport.case.number + ((serviceReport.case && serviceReport.case.venue)? (' - ' + serviceReport.case.venue): '' )) :
                            ((serviceReport.case && serviceReport.case.venue)? serviceReport.case.venue: '');

                        $srid.val(serviceReport.id);
                        $srNo.val(serviceReport.sr_number);
                        $("#case-title").val(caseTitle);
                        $("#case-number").val('');

                        if (serviceReport.fee_description) {
                            $str = (serviceReport.fee_description) ? serviceReport.fee_description + ' (' + serviceReport.fee_description + ')': serviceReport.description;
                            if ($('#activity-first-description').text() == '') {
                                $('#activity-first-description').val($str);
                            } else {
                                var $html = "<tr id='ars-desc-tr-"+$startIndex+"'>";
                                    $html += "<td>" + $startIndex  +"</td>";
                                    $html += "<td><textarea  rows='2' name='descriptions[]' type='text' class='form-control' required>"+serviceReport.fee_description+"</textarea>";
                                    $html += "<td><button type='button' class='btn btn-xs btn-danger delete-description' data-target='ars-desc-tr-"+$startIndex+"''' title='Delete'><i class='fa fa-times'></i></button></td>";
                                $tableBody.append($html);
                                $startIndex ++;
                            }
                        }
                    });
                } else {
                    $.get('{!! route("ars.client-cases") !!}?id='+data.id, function (data) {
                        var results = data.results;

                        // $("#case-no-div").show();
                        $srid.val('');
                        $srNo.val('');
                        $("#case-title").val('');
                        $("#case-number").val('');
                        
                        $caseSelect.select2({'data': results})
                        .on('select2:select', function (e) {
                            var data = e.params.data;


                            // Search latest service report base from Search    
                            $.get('{!! route("case.latest.service-reports") !!}?id='+data.id, function (caseData) {
                                if (!caseData.id) {
                                    // alert('No Information found!');
                                    swal({
                                        title: 'NO Data Found!',
                                        type: 'info',
                                    });
                                    return;
                                }

                                // console.log(caseData.number, 'cs number');
                                var serviceReport = caseData.latest_service_reports[0];
                                var caseNoAndVenue = (caseData.number) ? (caseData.number + ((caseData.venue)? (' - ' + caseData.venue): '' )) : ((caseData.venue)? caseData.venue: '');

                                if (!serviceReport) {
                                    swal({
                                        title: 'NO SERVICE REPORT FOUND!',
                                        text: 'Please create a service report before creating Activity Report Sheet. Thank you!',
                                        type: 'info',
                                    });
                                    return;
                                }

                                $srid.val(serviceReport.id);
                                $srNo.val(serviceReport.sr_number);
                                $("#case-title").val(caseData.title);
                                $("#case-number").val(caseNoAndVenue);
                                $counselName.val(data.counsel_full_name);

                                if (serviceReport.fee_description || serviceReport.description) {
                                    $str = (serviceReport.fee_description) ? serviceReport.fee_description + ' (' + serviceReport.fee_description + ')': serviceReport.description;
                                    if ($('#activity-first-description').text() == '') {
                                        $('#activity-first-description').val($str);
                                    } else {
                                        var $html = "<tr id='ars-desc-tr-"+$startIndex+"'>";
                                            $html += "<td>" + $startIndex  +"</td>";
                                            $html += "<td><textarea  rows='2' name='descriptions[]' type='text' class='form-control' required>"+serviceReport.fee_description+"</textarea>";
                                            $html += "<td><button type='button' class='btn btn-xs btn-danger delete-description' data-target='ars-desc-tr-"+$startIndex+"''' title='Delete'><i class='fa fa-times'></i></button></td>";
                                        $tableBody.append($html);
                                        $startIndex ++;
                                    }
                                }
                            });
                        });
                    });
                }
            });
            
            $clientSelect.on("focus", function () { 
                $(this).parent().parent().prev().select2("open"); 
            });

            $billingInsType.change(function() {
                var checked = $(this).is(':checked');
                $billingInsType.prop('checked',false);
                if(checked) {
                    $(this).prop('checked',true);
                }

                if ($(this).val() == 'Time-Trate') {
                    $billableTime.prop('disabled', false);
                    $billableTime.prop('required', true);
                } else {
                    $billableTime.prop('disabled', true);
                    $billableTime.prop('required', false);
                }
            });

            $btnAddNewArsDec.on('click', function() {
                var $html = "<tr id='ars-desc-tr-"+$startIndex+"'>";
                    $html += "<td>" + $startIndex  +"</td>";
                    $html += "<td><textarea  rows='2' name='descriptions[]' type='text' class='form-control' required></textarea>";
                    $html += "<td><button type='button' class='btn btn-xs btn-danger delete-description' data-target='ars-desc-tr-"+$startIndex+"''' title='Delete'><i class='fa fa-times'></i></button></td>";
                $tableBody.append($html);
                $startIndex ++;
            });

            $btnAddNewOutcome.on('click', function() {
                var $html = "<tr id='ars-desc-tr-"+$startIndexOutcome+"'>";
                    $html += "<td>" + $startIndexOutcome  +"</td>";
                    $html += "<td><textarea  rows='2' name='outcomes[]' type='text' class='form-control' required></textarea>";
                    $html += "<td><button type='button' class='btn btn-xs btn-danger delete-description' data-target='ars-desc-tr-"+$startIndexOutcome+"''' title='Delete'><i class='fa fa-times'></i></button></td>";
                $tableOutcomeBody.append($html);
                $startIndexOutcome ++;
            });

            $btnAddNewAct.on('click', function() {
                var $html = "<tr id='ars-act-date-tr-"+$startIndexActivity+"'>";
                    $html += "<td>" + $startIndexActivity  +"</td>";
                    $html += "<td><textarea  rows='2' name='feacture_activities[]' type='text' class='form-control' required></textarea>";
                    $html += "<td><button type='button' class='btn btn-xs btn-danger delete-description' data-target='ars-act-date-tr-"+$startIndexActivity+"''' title='Delete'><i class='fa fa-times'></i></button></td>";
                $tableActBody.append($html);
                $startIndexOutcome ++;
            });

            // select
            $clientSelect.select2('open');
            $caseSelect.select2();
            // $("#case-no-div").hide();

            $(document).on('focus', '.select2', function (e) {  
              if (e.originalEvent) {
                $(this).siblings('select').select2('open');    
              } 
            });

            $(document).on('click', ".delete-description", function(){
                var data = $(this).data();
                $('#'+data.target).remove();
            });
        });
    </script>
@endsection