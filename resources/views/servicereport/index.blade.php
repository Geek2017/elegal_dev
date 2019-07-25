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
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Service Report</strong>
                </div>
                <div class="panel-body">
                    <table id="serviceReport" class="table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>Fee</th>
                                <th>Case</th>
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


@endsection

@section('scripts')
{!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
{!! Html::script('js/plugins/slick/slick.min.js') !!}
{!! Html::script('js/plugins/toastr/toastr.min.js') !!}
{!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
{!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}

<script>
console.log({!! $data !!});
serviceReport = $('#serviceReport').DataTable({
        autoWidth: false,
        data:{!! $data !!},
        columns: [
            {data: "transaction_detail.fee.name"},
            {data: "transaction_detail.cases.title"},
        ],
        columnDefs: [{
            targets: 2,
            data: null,
            render:function(row){
                return `<a class="btn-success btn btn-xs" href="/service-report/${row.id}">View</a>` 
            }
        }]
    });
</script>
@endsection
