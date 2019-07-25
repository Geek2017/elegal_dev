@extends('layouts.master')

@section('title', 'Log Activity')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Log Activity</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Log Activity</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="button" id="reload-logs" class="btn btn-primary">Refresh</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <h1>Log Activity Lists</h1>
                        <table id="logs-table" class="table table-stripped dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>User Name</th>
                                <th>Subject</th>
                                <th>URL</th>
                                <th>IP</th>
                                <th width="300px">Timestamp</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('DataTables/dataTables.css') !!}
@endsection

@section('scripts')
    {!! Html::script('DataTables/dataTables.js') !!}
    <script>
        $(document).ready(function(){
            var logs = $('#logs-table').DataTable( {
                processing: true,
                serverside: true,
                aaSorting: [],
                ajax: 'http://'+window.location.host+'/dt-get-logs',
                columnDefs: [
                    { width: '20', 'targets': [ 0 ] },
                    { className: "text-right", "targets": [ 5 ] },
                ],
                columns: [
                    {data: 'number', name: 'number'},
                    {data: 'user', mRender: function(data, type, row){
                        return '<strong>'+ data +'</strong>';
                    }},
                    {data: 'description', mRender: function(data, type, row){
                        switch (row.action) {
                            case 'Browse':
                                return '<span class="label label-primary">'+ row.action +' '+row.model+'</span>';
                                break;
                            case 'Read':
                                return '<span class="label label-info">'+ row.action +' '+row.model+' '+row.subject+'</span>';
                                break;
                            case 'Edit':
                                return '<span class="label label-warning">'+ row.action +' '+row.model+' '+row.subject+'</span>';
                                break;
                            case 'Add':
                                return '<span class="label label-success">'+ row.action +' '+row.model+' '+row.subject+'</span>';
                                break;
                            case 'Delete':
                                return '<span class="label label-danger">'+ row.action +' '+row.model+' '+row.subject+'</span>';
                                break;
                            default:
                                return '<span class="label">'+ row.action +'</span>';
                        }
                    }},
                    {data: 'desc_url', mRender: function(data, type, row){
                        return '<span class="text-success">'+ data +'</span>';
                    }},
                    {data: 'desc_ip', mRender: function(data, type, row){
                        return '<span class="text-warning">'+ data +'</span>';
                    }},
                    {data: 'timestamp', mRender: function(data, type, row){
                        return '<span class="text-success">'+ data +'</span>';
                    }},
                ]
            });

            $(document).on('click','#reload-logs',function(){
                logs.ajax.reload();
            });
        });
    </script>
@endsection