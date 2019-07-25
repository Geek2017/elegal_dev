@extends('layouts.master')

@section('title', 'Billing')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Billing</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Billing</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Create <small>Billing</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-4">

                                <div class="panel panel-default table-box">
                                    <div class="panel-heading">
                                        <label>Billing Information</label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Billing No.:</span>
                                                <input type="text" name=""  class="form-control">
                                                <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Billing Date.:</span>
                                                <input type="text" name=""  class="form-control">
                                                <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Unpaid Balance:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Last Billing Date:</span>
                                                <input type="text" name=""  class="form-control">
                                                <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Last Payment Amount:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Last Paid Date:</span>
                                                <input type="text" name=""  class="form-control">
                                                <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon bg-muted">GR Fee:</span>
                                                        <input type="number" name=""  class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" name=""  class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Total PF:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Total Charge Exp:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon bg-muted">Excess Alloted # of hrs.:</span>
                                                        <input type="number" name=""  class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" name=""  class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Paralegal:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Current Due:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Adjustment:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Balance Forwarded:</span>
                                                <input type="text" name=""  class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-8">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default table-box">
                                            <div class="panel-heading">
                                                <label>Client's Info:</label>
                                            </div>
                                            <div class="panel-body">

                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <div class="form-group">
                                                            <div class="input-group m-b">
                                                                <span class="input-group-addon bg-muted">Docket No.:</span>
                                                                <input type="text" name=""  class="form-control">
                                                                <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="input-group m-b">
                                                                <span class="input-group-addon bg-muted">Contract No.:</span>
                                                                <input type="checkbox" class="js-switch" checked />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon bg-muted">Client Name:</span>
                                                        <input type="text" name=""  class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon bg-muted">Billing Address:</span>
                                                        <input type="text" name=""  class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="input-group m-b">
                                                        <span class="input-group-addon bg-muted">Contact Person:</span>
                                                        <input type="text" name=""  class="form-control">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default table-box">
                                            <div class="panel-heading">
                                                <label>Period Covered:</label>
                                            </div>
                                            <div class="panel-body">

                                                <div class="row" id="data_5">

                                                    <div class="input-daterange">
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <div class="input-group m-b">
                                                                    <span class="input-group-addon bg-muted">From:</span>
                                                                    <input type="text" name="start" value="{!! \Carbon\Carbon::now()->format('m/d/Y') !!}"  class="form-control">
                                                                    <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group">
                                                                <div class="input-group m-b">
                                                                    <span class="input-group-addon bg-muted">To:</span>
                                                                    <input type="text" name="end" value="{!! \Carbon\Carbon::now()->addDays(6)->format('m/d/Y') !!}"  class="form-control">
                                                                    <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-calendar"></i></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <div class="input-group m-b">
                                                                <span class="input-group-addon bg-muted">Refresh:</span>
                                                                <input type="text" name=""  class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default table-box">
                                            <div class="panel-heading">
                                                <label>Service Report:</label>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table id="" class="table table-stripped dt-responsive nowrap">
                                                            <thead>
                                                            <tr>
                                                                <th>S.R. No</th>
                                                                <th>Date Trans</th>
                                                                <th>Nature of Service</th>
                                                                <th>Rate</th>
                                                                <th>#hr/pgs</th>
                                                                <th>Total Fee</th>
                                                                <th>...</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>Lorem ipsum.</td>
                                                                <td>...</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="hr-line-dashed"></div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="input-group m-b">
                                                                <span class="input-group-addon bg-muted">Total Hrs:</span>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="input-group m-b">
                                                                <span class="input-group-addon bg-muted">Excess Hrs:</span>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <div class="input-group m-b">
                                                                <span class="input-group-addon bg-muted">Total S.R.:</span>
                                                                <input type="text" name="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    {!! Html::style('css/plugins/switchery/switchery.css') !!}
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/switchery/switchery.js') !!}
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    <script>
        $(document).ready(function(){
            var elem = document.querySelector('.js-switch');
            var switchery = new Switchery(elem, { color: '#1AB394' });

            $('#data_5 .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });
        });
    </script>
@endsection