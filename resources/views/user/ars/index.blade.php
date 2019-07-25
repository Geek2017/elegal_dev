@extends('layouts.master')

@section('title', 'Activity Report Sheets')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Activity Report Sheets</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Activity Report Sheet</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('ars.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i> New Ars.</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                       
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            @if(Session::has('message'))
                                <br/>
                                <div class="col-md-12">
                                  <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <span class="fa fa-ok"></span><em> {!! session('message') !!}</em>
                                  </div>
                                </div>
                            @endif

                            <div class="col-sm-12">
                                

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="users-table">
                                        <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Case/Project Name</th>
                                            <th>Docket #/Venue</th>
                                            <th>Reporter</th>
                                            <th>GR. Title</th>
                                            <th>Time</th>
                                            <th>Duration</th>
                                            <th>Sr. #</th>
                                            <!-- <th>Billing Instruction</th> -->
                                            <!-- <th>Billing Entry</th> -->
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/dataTables.min.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/dataTables.min.js') !!}
    <script>
        $(document).ready(function(){
            var table = $('#users-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                ajax: '{!! route('ars-list') !!}',
                columnDefs: [
                    { className: "text-right", "targets": [ 3 ] }
                ],
                columns: [
                    { 
                        data: 'client', 
                        name: 'client',
                        searchable: false,
                        sortable: false,
                        "render": function (data, type, row, meta) {
                            return data.profile.full_name;
                        }

                    },
                    { data: 'case_project_name', name: 'case_project_name' },
                    { data: 'docket_no_venue', name: 'docket_no_venue' },
                    { data: 'reporter', name: 'reporter' },
                    { data: 'gr_title', name: 'gr_title' },
                    {
                        data: null,
                        searchable: false,
                        "render": function (data, type, row, meta) {
                            if (row.time_start && row.time_finnish) {
                                return row.time_start + ' - ' + row.time_finnish;
                            }

                            return '';
                        }
                    },
                    { 
                        data: 'duration', 
                        name: 'duration'
                    },
                    { data: 'sr_no', name: 'sr_no' },
                    // { data: 'time_start', name: 'time_start' },
                    // { data: 'time_finnish', name: 'time_finnish' },
                    // { data: 'billing_instruction', name: 'billing_instruction' },
                    // { data: 'billing_entry', name: 'billing_entry' },
                    { data: 'action', name: 'action' }
                ]
            });


            $(document).on('click', "a.delete-ars", function(){
                var data = $(this).data();
                
                var r = confirm("Delete Activity Report Sheet?");
                if (r == true) {
                    // txt = "You pressed OK!";
                    window.location.href = data.url;
                }

            });
        });
    </script>
@endsection