@extends('layouts.master')

@section('title', 'Case Tracker | Manage Case')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Case</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('case-tracker.index') !!}">Case Tracker</a>
                </li>
                <li class="active">
                    <strong>Update Case</strong>
                </li>
            </ol>
        </div>
    </div>

    <!-- animated fadeInRight -->
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title bg-success">
                        <h3>Case Information</h3>
                    </div>
                    <div class="ibox-content">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Case #:</label>
                                        <div class="col-lg-9">
                                            <input name="contract_no" type="text" class="form-control" value="{{ $case->number }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Case Title:</label>
                                        <div class="col-lg-9">
                                            <input name="contract_no" type="text" class="form-control" value="{{ $case->title }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Contract #:</label>
                                        <div class="col-lg-9">
                                            <input name="contract_no" type="text" class="form-control" value="{{ $case->transaction->contract->contract_number }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Client:</label>
                                        <div class="col-lg-9">
                                            <input name="client_id" type="text" class="form-control" value="{{ $case->transaction->client->profile->full_name }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Venue:</label>
                                        <div class="col-lg-9">
                                            <textarea class="form-control" rows="3" readonly>{{$case->venue}}</textarea>
                                            <!-- <input name="contract_no" type="text" class="form-control" value="{{ $case->venue }}" disabled> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Counsel:</label>
                                        <div class="col-lg-9">
                                            <input name="contract_no" type="text" class="form-control" value="{{ $case->counsel->profile->full_name }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Class:</label>
                                        <div class="col-lg-9">
                                            <input name="contract_no" type="text" class="form-control" value="{{ $case->class }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Date:</label>
                                        <div class="col-lg-9">
                                            <input name="client_id" type="date" class="form-control" value="{{ ($case->date) ? $case->date->format('Y-m-d') : '' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div id="case-toolbar">
                                        <div>
                                            <button type="button" id="add-new-activity" class="btn btn-sm btn-primary pull-right" style="margin: 5px;">Add Activity</button>
                                        </div>
                                    </div>
                                    <div class="table-bordered">
                                        <table id="case-table" class="table table-striped table-bordered table-hover" data-toolbar="case-toolbar">
                                            <thead>
                                              <tr>
                                                <th>Date</th>
                                                <th>Activities</th>
                                                <th>Action To be Taken</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th></th>
                                              </tr>
                                            </thead>
                                            <tbody id="case-table-body">
                                                @if (!$case->caseTracker)
                                                    <tr id="case-table-no-data">
                                                        <td colspan="6" class="text-center">
                                                            No Activities yet.
                                                        </td>
                                                    </tr>
                                                @endif
                                                @foreach ($case->caseTracker as $index => $caseT)
                                                    <tr id='case-tr-{{ $caseT->id}}'>
                                                        <td> {{ $caseT->transaction_date->format('m/d/Y')}} </td>
                                                        <td> <p>{{$caseT->activities}}</p> </td>
                                                        <td> <p>{{$caseT->action_to_take}}</p> </td>
                                                        <td> {{ $caseT->due_date->format('m/d/Y')}} </td>
                                                        <td> <p>{{$caseT->status_text}}</p> </td>
                                                        
                                                        <td class="text-center">
                                                            <button type='button' class='btn btn-xs btn-warning edit-case-activities' data-target="case-tr-{{ $caseT->id }}" data-case="{{$caseT}}" title='Edit'>
                                                                <i class='fa fa-edit'></i>
                                                            </button>
                                                            <button type='button' class='btn btn-xs btn-danger delete-case-activities' data-target="case-tr-{{ $caseT->id }}" data-id="{{$caseT->id}}" title='Delete'>
                                                                <i class='fa fa-times'></i>
                                                            </button>
                                                        </td>
                                                    </tr>    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div id="case-activity-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form class="form-horizontal" id="cas-activity-form">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Case Activity information</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-lg-4" style="padding-left: 0px;">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Date:</span>
                                        <input name="transaction_date" type="date" class="form-control date-picker" id="transaction-date" value="{{$now}}"  required>
                                    </div>
                                </div>
                                <div class="col-lg-2"></div>
                                <div class="col-lg-4" >
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Due Date:</span>
                                        <input name="due_date" type="date" class="form-control date-picker" id="due-date"  value="{{$now}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b"><span class="input-group-addon bg-muted">Activities:</span>
                                    <textarea rows="2" class="form-control" name="activities" id="activities" placeholder="Activities .." required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group m-b"><span class="input-group-addon bg-muted">Action to be taken:</span>
                                    <textarea rows="2" class="form-control" name="action_to_take" id="action-to-take" placeholder="List the actions to take .." required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-6" style="padding-left: 0px;">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Status:</span>
                                        <select class="form-control select2" id="case-tracker-status" name="status" required>
                                            <option>Select Action Status</option>
                                            <option value="P" selected>Pending</option>
                                            <option value="D">Done</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="btn-save-activity">Save</button>
                </div>
                </div>
            </form>
      </div>
    </div>

@endsection

@section('styles')
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
    {!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
    <script>
        $(document).ready(function(){
            var $btnAddActivity = $("#add-new-activity"),
                $caseTableBody = $("#case-table-body"),
                $caseTableNoData = $("#case-table-no-data"),
                $toEditData = {},
                $startIndexActivity = 1;

            $btnAddActivity.on('click', function(){
                $('#case-activity-modal').modal('show');

                $("#transaction-date").val("{{$now}}");
                $("#due-date").val("{{$now}}"); 
            });


            function convertDate(date) {
                var yyyy = date.getFullYear().toString();
                var mm = (date.getMonth()+1).toString();
                var dd  = date.getDate().toString();

                var mmChars = mm.split('');
                var ddChars = dd.split('');

                return yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) + '-' + (ddChars[1]?dd:"0"+ddChars[0]);
            }

            function clearModalInput() {
                $("#transaction-date").val('');
                $("#due-date").val('');
                $("#activities").val('');
                $("#action-to-take").val('');
                $("#case-tracker-status").val('');
            }

            $(document).on('click', '.edit-case-activities', function(){
                $('#case-activity-modal').modal('show');

                $toEditData = $(this).data('case');

                console.log($toEditData, 'to edit');

                var transactionDateRaw = new Date($toEditData.transaction_date);
                var transactionDate = convertDate(transactionDateRaw);
                var dueDateRaw = new Date($toEditData.due_date);
                var dueDate = convertDate(dueDateRaw);

                $("#transaction-date").val(transactionDate);
                $("#due-date").val(dueDate);
                $("#activities").val($toEditData.activities);
                $("#action-to-take").val($toEditData.action_to_take);
                $("#case-tracker-status").val($toEditData.status);

            });

            $(document).on('click', ".delete-case-activities", function(){
                var data = $(this).data();

                swal({
                    title: 'Case Tracker!',
                    text: 'Delete Case Tracker Information?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        // delete
                        $.get('/case-tracker/'+data.id+'/delete', function (res) {
                            swal({
                                title: 'Case Tracker!',
                                text: 'Case Tracker successfully deleted?',
                                type: 'success',
                            });
                            $('#'+data.target).remove();     
                        });
                    }
                });
            });

            $(document).on('submit', "#cas-activity-form", function(e){
                e.preventDefault();
                var $data = {
                    'case_management_id' : {!! $case->id !!},
                    'transaction_date'   : $("#transaction-date").val(),
                    'due_date'           : $("#due-date").val(),
                    'activities'         : $("#activities").val(),
                    'action_to_take'     : $("#action-to-take").val(),
                    'status'             : $("#case-tracker-status").val(),
                    '_token'             : '{!! csrf_token() !!}',
                };

                if ($toEditData.id) {
                    $data = $.extend($toEditData, $data);
                    $.post('/case-tracker/'+$data.id+'/update', $data, function (res) {
                        swal({
                            title: 'Case Tracker',
                            text: 'Case Tracker information successfully updated',
                            type: 'success',
                        });
                        $("#case-tr-" + res.id + " td:nth-child(1)").html(res.formatted_transaction_date);
                        $("#case-tr-" + res.id + " td:nth-child(2)").html('<p>' + res.activities + '</p>');
                        $("#case-tr-" + res.id + " td:nth-child(3)").html('<p>' + res.action_to_take + '</p>');
                        $("#case-tr-" + res.id + " td:nth-child(4)").html(res.formatted_due_date);
                        $("#case-tr-" + res.id + " td:nth-child(5)").html(res.status_text);
                        
                        clearModalInput();
                        $('#case-activity-modal').modal('hide');
                    });
                } else {
                    $.post('{!! route("case-tracker.store") !!}', $data, function (res) {
                        $caseTableNoData.hide();

                        var $html = "<tr id='case-tr-"+ res.id +"'>";
                            $html += "<td>" + res.formatted_transaction_date+ "</td>";
                            $html += "<td><p>" + res.activities+ "</p></td>";
                            $html += "<td>" + res.action_to_take+ "</td>";
                            $html += "<td><p>" + res.formatted_due_date+ "</p></td>";
                            $html += "<td><p>" + res.status_text + "</p></td>";
                            $html += "<td class='text-center'>";
                            $html +=    "<button type='button' class='btn btn-xs btn-warning edit-case-activities' data-target='case-tr-" + res.id + "' data-case='"+ JSON.stringify(res) +"' title='Edit'>";
                            $html +=        "<i class='fa fa-edit'></i>";
                            $html +=    "</button>&nbsp;";
                            $html +=    "<button type='button' class='btn btn-xs btn-danger delete-case-activities' data-target='case-tr-" + res.id + "' data-id='"+res.id+"' title='Delete'>";
                            $html +=        "<i class='fa fa-times'></i>";
                            $html +=    "</button>";
                            $html += "</td>";                        
                            $html += "</tr>";

                        swal({
                            title: 'Case Tracker',
                            text: 'Case Tracker information successfully saved',
                            type: 'success',
                        });

                        $caseTableBody.prepend($html);
                        $startIndexActivity ++;

                        clearModalInput();
                        $('#case-activity-modal').modal('hide');
                    });
                }
            });
        });
    </script>
@endsection