@extends('layouts.master')

@section('title', 'Client')

@section('styles')
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/iCheck/custom.css') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
{!! Html::style('css/plugins/toastr/toastr.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}

<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Client</h2>
        <ol class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li>
                <a href="{!! route('clients.index') !!}">Client List</a>
            </li>
            <li class="active">
                <strong>Client</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-4">
        <div class="title-action">
            @if(if_uri('clients/create'))
                <button type="button" class="btn btn-primary" onclick="constructClient(true)">Create</button> 
            @else
                <button type="button" class="btn btn-primary" onclick="constructClient(false)">Save</button>
            @endif
        </div>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <h2>Client Details</h2>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-lg-6">
                            <div class="input-group m-b"><span class="input-group-addon bg-muted" >First Name</span>
                                <input name="fname" type="text" class="form-control" value="{!! $client->fname !!}" required>
                            </div>
                            <div class="input-group m-b"><span class="input-group-addon bg-muted">Middle Name</span>
                                <input name="mname" type="text" class="form-control" value="{!! $client->mname !!}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group m-b"><span class="input-group-addon bg-muted" >Last Name</span>
                                <input name="lname" type="text" class="form-control" value="{!! $client->lname !!}" required>
                            </div>
                            <div class="input-group m-b"><span class="input-group-addon bg-muted">Email</span>
                                <input name="email" type="text" class="form-control" value="{!! $client->email !!}" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <h2>Business Details</h2>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#businessModal">Add Business</button>
                        </div>
                    </div>
                <div class="col-lg-12">
                    <table id="business" class="table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>Billing</th>
                                <th>Business Name</th>
                                <th>Address</th>
                                <th><span>Select All</span>
                                    <button class="btn btn-danger btn-xs no-margins" onclick="confirm()" style="display:none;">Delete</button></th>
                                <th>
                                    <div class="checkbox checkbox-danger no-padding">
                                        <input id="selectAll" type="checkbox" onchange="selectAll(this.checked)">
                                        <label></label>
                                    </div>
                                </th>
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

<div class="modal inmodal fade" id="businessModal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add New Business Detail</h4>
                <small class="font-bold">Please input the details below.</small>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class=" col-lg-12">
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Business Name</span>
                                <input type="text"  name="name" class="form-control" >
                            </div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Contact No.</span>
                                <input name="contact" type="text" class="form-control">
                            </div>

                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Business Type</span>
                                <select name="business_nature" class="form-control">
                                    <option selected value=""></option>
                                    <option value="1">Corporate</option>
                                    <option value="2">Infrastructure</option>
                                    <option value="3">advertising</option>
                                    <option value="4">Media broadcasting</option>
                                    <option value="5">Contraction</option>
                                    <option value="6">Consulting</option>
                                    <option value="7">IT/Telco</option>
                                    <option value="8">transportation</option>
                                    <option value="9">Logistics</option>
                                    <option value="10">Finance</option>
                                    <option value="11">entertainment</option>
                                    <option value="12">Clothing</option>
                                    <option value="13">Cosmetics</option>
                                    <option value="14">Agriculture</option>
                                    <option value="15">Hospitality/Tourism</option>
                                    <option value="16">NGO</option>
                                    <option value="17">LGU</option>
                                    <option value="18">Others</option>
                                </select>
                            </div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Officer In Charge</span>
                                <input name="oic" type="text" class="form-control" >
                            </div>
                            <div class="row"><hr></div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Search</span>
                                <input id="acInput1" placeholder="Type to search address" onfocus="" type="text" class="form-control autocomplete" autocomplete="off">
                            </div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Street Address</span>
                                <div class="row">
                                <div class="col-lg-6"><input name="street_number" type="text" class="form-control street_number_acInput1" ></div>
                                    <div class="rmlt col-lg-6"><input name="route" type="text" class="form-control route_acInput1" ></div>
                                </div>
                            </div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Barangay/Town</span>
                                <input type="text"  name="neighborhood" class="form-control neighborhood_acInput1 administrative_area_level_5_acInput1" >
                            </div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Municipality/City</span>
                                <input name="locality" type="text" class="form-control locality_acInput1" >
                            </div>
                            <div class="input-group m-b">
                                <span class="input-group-addon bg-muted">Province/Conurbation</span>
                                <input name="administrative_area_level_1" type="text" class="form-control administrative_area_level_1_acInput1 administrative_area_level_2_acInput1" >
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon bg-muted">Country</span>
                                        <input name="country"  type="text" class="form-control  country_acInput1" >
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon bg-muted">Zip Code</span>
                                        <input name="postal_code" type="text" class="form-control  postal_code_acInput1" >
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveAddress()">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
{!! Html::script('js/plugins/toastr/toastr.min.js') !!}
{!! Html::script('js/plugins/toastr/toastr.min.js') !!}
{!! Html::script('js/business.js') !!}
<script>
    let from = location.search;
    from = from.substr(from.indexOf('=')+1);
$(document).ready(function(){
    setBilling({!! $client->billing !!})
    getBusiness({!! $client->id !!});
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('#_token').attr('value')
    }
});
});
</script>
{!! Html::script('js/google.js') !!}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVS0CN-Ez85eOLGEh7d113v9LE9ZzDses&libraries=places&callback=initAutocomplete"
async defer></script>
{!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
{!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
@endsection