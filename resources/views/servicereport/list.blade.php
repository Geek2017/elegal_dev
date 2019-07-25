@extends('layouts.master')
@section('title', 'Service Report')

@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/toastr/toastr.min.css') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
{!! Html::style('css/plugins/slick/slick.css') !!}
{!! Html::style('css/plugins/slick/slick-theme.css') !!}
@endsection

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Service Report</h2>
        <ol class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li class="active">
                <strong>Service Report</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div id="root" class="wrapper wrapper-content animated fadeInUp">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="m-b-md">
                        {{-- <a href="#" class="btn btn-success btn-xs pull-right">Update</a> --}}
                    <h2>S.R. No.: {{ date_format(date_create($report['serviceReport']['created_at']),"Ymd")."-".$report['serviceReport']['id'] }}<strong></strong></h2>
                    </div>
                    <dl class="dl-horizontal">
                    <dt>Case Class:</dt> <dd><span class="label label-primary"></span></dd>
                    </dl>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <dl class="dl-horizontal">
                    <dt>Client Name:</dt> <dd>{{ $report['client']['fname']." ".$report['client']['lname']}}</dd>
                        <dt>Docket No.:</dt> <dd></dd>
                        <dt>Case Title.:</dt> <dd>{{ $report['case'][0]['title']}}</dd>
                        @php
                            $leadCounsel = $report['case'][0]['counsel_list']->where('lead',1)->first();
                        @endphp
                        <dt>Lead Counsel:<dt> <dd>{{ $leadCounsel->info->fname." ".$leadCounsel->info->lname }}</dd>
                        <dt>Venue:</dt> <dd>{{ $report['case'][0]['venue']}}</dd>
                    </dl>
                </div>
                <div class="col-lg-5">
                    <dl class="dl-horizontal">
                        <dt>Contract No.:</dt> <dd>{{ $report['contract']['contract_number']}}<dd>
                        <dt>Contract Type:</dt> <dd>{{ $report['contract']['contract_type']}}</dd>
                        <dt>Date Rendered:</dt> <dd>{{ date_format(date_create($report['contract']['start_date']),'l F d, Y')}}</dd>
                    </dl>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Chargeable Expense</strong>
                            <a href="#" class="btn btn-primary btn-xs pull-right">Add</a>
                        </div>
                        <div class="panel-body">
                            <table id="chargeable" class="table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
{!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
{!! Html::script('js/plugins/slick/slick.min.js') !!}
{!! Html::script('js/plugins/toastr/toastr.min.js') !!}
{!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
{!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
{!! Html::script('js/reportScript.js') !!}
<script>
    tblChargeable.ajax.url(`create?request=chargeable&id={{$report['serviceReport']['id']}}`).load()
</script>
@endsection
