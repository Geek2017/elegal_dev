@extends('layouts.master')

@section('title', 'Service Report')

@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/slick/slick.css') !!}
{!! Html::style('css/plugins/slick/slick-theme.css') !!}
{!! Html::style('css/plugins/toastr/toastr.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}
{!! Html::style('css/plugins/iCheck/custom.css') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}


<style>
.ibox-content .row div {
        -webkit-transition: width 0.3s ease;
        -moz-transition: width 0.3s ease;
        -o-transition: width 0.3s ease;
        transition: width 0.3s ease;
    }

.del {
        color: #fff;
        background-color: #d9534f;
        border-color: #d343f3a;
}

.del:focus {
        color: #fff;
        background-color: #c9302c;
        border-color: #761c19
}

.del:hover {
        color: #fff;
        background-color: #c9302c;
        border-color: #ac2925
}

.del:active {
        color: #fff;
        background-color: #c9302c;
        border-color: #ac2925
}
</style>
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
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
    <div class="col-lg-4">
        <div class="title-action">
            <button type="submit" class="btn btn-primary" onclick="saveSR()">Save Contract</button>
        </div>
    </div>
</div>

<div id="root" class="wrapper wrapper-content animated fadeInUp">
    <div class="ibox float-e-argins">
        <div class="ibox-content">
            <div class="row">
                <div class="slicker">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Cases
                        </div>
                        <div class="panel-body">
                            <table id="case" class="table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Fee Details
                        </div>
                        <div class="panel-body">
                            <table id="fee" class="table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Charge Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="btn btn-muted col-lg-offset-6" onclick="prev()">Back</button>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Chargeable Expense
                            <a href="#" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#chargeableModal">Add</a>
                        </div>
                        <div class="panel-body">
                            <table id="chargeable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox checkbox-info">
                                                <input id="checkboxMain" onchange="selectedHandler(this)" type="checkbox">
                                                <label for="checkboxMain">Select All</label>
                                            </div>
                                            <div class="checkbox checkbox-danger">
                                                <a class="btn-danger btn btn-xs" onclick="delChargeables()" style="display: none;">Delete</a>
                                            </div>
                                            
                                        </th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button class="btn btn-muted col-lg-offset-6" onclick="prev()">Back</button>
                </div>     
            </div>
        </div>
        </div>
    </div>          
</div>
<div class="modal inmodal fade" id="chargeableModal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Chargeable Expense</h4>
                <small class="font-bold">Add new Chargeable Expense</small>
            </div>
            <div class="modal-body">
                <div class="input-group m-b"><span class="input-group-addon bg-muted" >Name</span>
                    <input name="name" type="text" class="form-control" required>
                </div>
                <div class="input-group m-b"><span class="input-group-addon bg-muted" >Description</span>
                    <input name="description" type="text" class="form-control" required>
                </div>
                <div class="input-group m-b"><span class="input-group-addon bg-muted" >Amount</span>
                    <input name="amount" type="text" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="sendChargeable()" data-dismiss="modal">Add</button>
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
@endsection
