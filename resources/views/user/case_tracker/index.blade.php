@extends('layouts.master')

@section('title', 'Case Tracker')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Activity Report Sheets</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Case Tracker</strong>
                </li>
            </ol>
        </div>
    </div>

    <!-- animated fadeInRight -->
    <div class="wrapper wrapper-content">
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
                                            <th>Contract #</th>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Case #</th>
                                            <th>Counsel</th>
                                            <th>No. Pending Activity</th>
                                            <th>status</th>
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
                ajax: '{!! route('case_management.lists') !!}',
                columns: [
                    { data: 'client_full_name', name: 'client_full_name' },
                    { data: 'contract_no', name: 'contract_no', orderable: false},
                    { data: 'class', name: 'class' },
                    { data: 'title', name: 'title' },
                    { data: 'number', name: 'number' },
                    { data: 'counsel_full_name', name: 'counsel_full_name' },
                    { 
                        data: 'no_of_pending_case_activities', 
                        name: 'no_of_pending_case_activities', 
                        orderable: false,
                        render: function (data, type, row) {
                            if (parseInt(data) > 0) {
                                return '<span class="label label-danger" style="font-size: 12px;">' + data + ' Pending Activity(ies)</span>';
                            } else {
                                return '<span class="label label-default" style="font-size: 12px;">No Pending Activity</span>';
                            }
                        }
                    },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false }
                ],
                "order": [[ 6, "desc" ]]
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