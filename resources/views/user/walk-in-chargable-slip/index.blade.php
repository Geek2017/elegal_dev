@extends('layouts.master')

@section('title', 'Activity Report Sheet')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Walk-In Charge Slip</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('walk-in.charge-slip.index') !!}">Walk-In Charges Slip</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('walk-in.charge-slip.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i> Create Charge Slip</a>
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
                                    <table class="table table-bordered" id="charge-slips-table">
                                        <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>SR. No.</th>
                                            <th>Date</th>
                                            <th>Expenses</th>
                                            <th>Prof. Fee</th>
                                            <th>Charges</th>
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
            var table = $('#charge-slips-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                ajax: "{!! route('walk-in.charge-slip.lists') !!}",
                columns: [
                    { 
                        data: 'client', 
                        name: 'client',
                        searchable: false,
                        sortable:false,
                        "render": function (data, type, row, meta) {
                            return data.profile.full_name;
                        }
                    },
                    { data: 'charge_slip_no', name: 'charge_slip_no', sortable:false },
                    { 
                        data: null, 
                        searchable: false,
                        sortable:false,
                        "render": function (data, type, row, meta) {
                            return data.formatted_transaction_date;
                        }
                    },
                    { data: 'total_expenses', name: 'total_expenses', sortable:false },
                    { data: 'professional_fees', name: 'professional_fees', sortable:false},
                    { data: 'total_charges', name: 'total_charges', sortable:false},
                    { data: 'action', name: 'action', sortable:false}
                ]
            });

            $(document).on('click', "a.delete-walk-in-charge-slip", function(){
                var data = $(this).data();
                
                var r = confirm("Delete Charge Slip?");
                if (r == true) {
                    window.location.href = data.url;
                }

            });
        });
    </script>
@endsection