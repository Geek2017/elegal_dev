@extends('layouts.master')

@section('title', 'Dashboard')

@section('styles')
    {!! Html::style('DataTables/datatables.css') !!}
    {!! Html::style('vendor/fullcalendar/fullcalendar.min.css') !!}
<style>
    .nopads{
        padding:0;
    }
    .rmlt{
        padding-left:0;
    }
    .rmrt{
        padding-right:0;
    }
    .rmbt{
        padding-bottom:0;
    }
    .rmtp{
        padding-top:0;
    }
    .rmgin{
        margin:0;
    }
    .fixwidth {
        width: 19%;
    }
</style>
@endsection

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Home</h2>
        <ol class="breadcrumb">
            <li class="active">
                <a href="/"><strong>Dashboard</strong></a>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-7">
            <div class="row">
                <!-- Left -->
                <div class="nopads col-lg-12">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <!-- <span class="label label-success pull-right">Monthly</span> -->
                                <h5>Client's</h5>
                            </div>
                            <div class="ibox-content">
                                <p class="text-center" id="clients-spinner">
                                    <i class="fa fa-spinner fa-spin" style="font-size: 32px;"></i>
                                </p>

                                <h1 class="no-margins" id="clients-count" style="display: none;">
                                    <i class="fa fa-group"></i> 
                                    <span>0</span>
                                </h1>
                                <!-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-arrow-circle-o-up"></i></div> -->
                                <!-- <small>Total Client's</small> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Case's</h5>
                            </div>
                            <div class="ibox-content">
                                <p class="text-center" id="cases-spinner">
                                    <i class="fa fa-spinner fa-spin" style="font-size: 32px;"></i>
                                </p>

                                <h1 class="no-margins" id="cases-count" style="display: none;">
                                    <i class="fa fa-briefcase"></i> 
                                    <span>0</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>C.E.</h5>
                            </div>
                            <div class="ibox-content">
                                <p class="text-center" id="chargeable-expenses-spinner">
                                    <i class="fa fa-spinner fa-spin" style="font-size: 32px;"></i>
                                </p>

                                <h1 class="no-margins" id="chargeable-expenses-count" style="display: none;">
                                    <i class="fa fa-file-text"></i>
                                    <span>0</span>
                                </h1>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Start -->
                    <!-- <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <div>
                                <span class="pull-right text-right">
                                <small>Average value of sales in the past month in: <strong>United states</strong></small>
                                    <br/>
                                    All sales: 162,862
                                </span>
                                    <h3 class="font-bold no-margins">
                                        Half-year revenue margin
                                    </h3>
                                    <small>Sales marketing.</small>
                                </div>

                                <div class="m-t-sm">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div>
                                                <canvas id="lineChart" height="114"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <ul class="stat-list m-t-lg">
                                                <li>
                                                    <h2 class="no-margins">2,346</h2>
                                                    <small>Total orders in period</small>
                                                    <div class="progress progress-mini">
                                                        <div class="progress-bar" style="width: 48%;"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <h2 class="no-margins ">4,422</h2>
                                                    <small>Orders in last month</small>
                                                    <div class="progress progress-mini">
                                                        <div class="progress-bar" style="width: 60%;"></div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="m-t-md">
                                    <small class="pull-right">
                                        <i class="fa fa-clock-o"> </i>
                                        Update on 16.07.2015
                                    </small>
                                    <small>
                                        <strong>Analysis of sales:</strong> The value has been changed over time, and last month reached a level over $50,000.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Chart End -->
                </div>
                <!-- Left Eng -->
            </div>

            <!-- Case Tracker Event -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h3 class="pull-left font-bold no-margins">Pending Cases Action</h3>
                        </div>
                        <div class="ibox-content">
                            <div id='dashboard-case-tracker-calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="rmgin ibox">
                <div class="ibox-title">
                    <h3 class="pull-left font-bold no-margins">User Activity</h3>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button id="reload-logs" class="btn-white btn btn-xs">refresh</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content no-padding">
                    <table id="logs-table" class="table table-striped table-hover nowrap">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th></th>
                            <th>Subject</th>
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


@section('scripts')
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('vendor/fullcalendar/fullcalendar.min.js') !!}
    {!! Html::script('DataTables/datatables.js') !!}
    {!! Html::script('js/plugins/chartJs/Chart.min.js') !!}
    {!! Html::script('js/plugins/slimscroll/jquery.slimscroll.min.js') !!}

    <script>
        $(document).ready(function() {
            $.ajax("{{ route('dashboard.clients') }}").then(function(res){
                $("#clients-spinner").hide();
                $("#clients-count").fadeIn();
                $("#clients-count span").text(res);
            });

            $.ajax("{{ route('dashboard.cases') }}").then(function(res){
                $("#cases-spinner").hide();
                $("#cases-count").fadeIn();
                $("#cases-count span").text(res);
            });

            $.ajax("{{ route('dashboard.chargeable-expenses') }}").then(function(res){
                $("#chargeable-expenses-spinner").hide();
                $("#chargeable-expenses-count").fadeIn();
                $("#chargeable-expenses-count span").text(res);
            });

            $('#dashboard-case-tracker-calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,listWeek'
                },
                eventLimit: true, // allow "more" link when too many events

                // your event source
                events: function(start, end, timezone, callback) {
                    $.ajax({
                      url: "{{ route('case-tracker.pending-actions') }}",
                      dataType: 'json',
                      data: {
                        // our hypothetical feed requires UNIX timestamps
                        start: start.format('YYYY-MM-DD'),
                        end: end.format('YYYY-MM-DD')
                      },
                      success: function(result) {
                        var events = [];
                        $.each(result, function(key, event) {
                            events.push({
                                title: event.title,
                                start: event.start_date
                            });
                        });
                        console.log(events, 'the events');
                        callback(events);
                      }
                    });
                }
            });


            var logs = $('#logs-table').DataTable( {
                processing: true,
                serverside: true,
                searching: false,
                info: false,
                lengthChange: false,
                pageLength: 14,
                aaSorting: [],
                ajax: 'http://'+window.location.host+'/get-logs',
                columnDefs: [
                    // { width: '20', 'targets': [ 0 ] },
                    { width: '250px', 'targets': [ 1 ] },
                    { className: "client-avatar", "targets": [ 0 ] },
                    { className: "text-right", "targets": [ 3 ] },
                ],
                columns: [
                    {data: 'image', mRender: function(data, type, row){
                        return '<img alt="image" src="'+ data +'">';
                    }},
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
                            case 'Login':
                                return '<span class="label label-success">'+ row.action +'</span>';
                                break;
                            case 'Logout':
                                return '<span class="label">'+ row.action +'</span>';
                                break;
                            default:
                                return '<span class="label label-warning">'+ row.action +'</span>';
                        }
                    }},
                    {data: 'timestamp', mRender: function(data, type, row){
                        return '<span class="text-success">'+ data +'</span>';
                    }},
                ]
            });

            $(document).on('click','#reload-logs',function(){
                logs.ajax.reload();
            });

            $('#logs-table_wrapper > div:nth-child(3) > div:first-child').remove();
            $('#logs-table_wrapper > div:nth-child(3) > div:last-child').removeClass('col-sm-7').addClass('col-sm-12');


            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [48, 48, 60, 39, 56, 37, 30]
                    },
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 40, 51, 36, 25, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
        });
    </script>

@endsection