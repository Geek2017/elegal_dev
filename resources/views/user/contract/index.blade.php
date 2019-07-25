@extends('layouts.master')

@section('title', 'Contract List')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Contract List</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Contract List</strong>
                </li>
            </ol>
        </div>
        @if(auth()->user()->can('add-contract'))
        <div class="col-lg-2">
            <div class="title-action">
                <button type="button" class="btn btn-primary contract-btn">Create Contract</button>
            </div>
        </div>
        @endif
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Client's <small>Contract List</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">

                                <table id="contract-table" class="table table-striped dt-responsive" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Contract Number</th>
                                        <th>Client</th>
                                        <th>Case Detail</th>
                                        <th>Contract Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
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

    <div class="modal inmodal fade" id="modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Choose Client</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Client list</label>
                        <select name="client" class="form-control">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary create-contract">Proceed to Contract</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
    {!! Html::style('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css') !!}
    {!! Html::style('css/plugins/chosen/chosen.css') !!}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    {!! Html::script('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js') !!}
    {!! Html::script('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js') !!}
    {!! Html::script('https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js') !!}
    {!! Html::script('js/plugins/chosen/chosen.jquery.js') !!}
    <script>
        $(document).ready(function(){
            var modal = $('#modal');
            var caseTable = $('#contract-table').DataTable( {
                processing: true,
                serverside: true,
//                searching: false,
                pageLength: 5,
                ajax: '{!! route('contract-list') !!}',
                columnDefs: [
                    { width: '30', 'targets': [ 0 ] },
                    { className: "text-right", "targets": [ 5, 6 ] }
                ],
                columns: [
                    {data: 'number', name: 'number'},
                    {data: 'client', name: 'client'},
                    {data: 'type', name: 'type'},
                    {data: 'date', name: 'date'},
                    {data: 'status', name: 'status'},
                    {data: 'amount', name: 'amount'},
                    {data: 'action', name: 'action'}
                ]
            });

            $(document).on('click', '.contract-btn', function(){
                var select = modal.find('select[name="client"]');
                $.get('{!! route('get-client-list') !!}', function(data){
                    if(data.length != 0){
                        select.empty().append('<option value="">Select Client</option>');
                        for(var a = 0; a < data.length; a++){
//                            select.append('<option value="'+ data[a].id +'">'+ data[a].profile.firstname +' '+ data[a].profile.lastname +'</option>')
                            select.append('<option value="'+ data[a].client_id +'">'+ data[a].firstname +' '+ data[a].lastname +'</option>')
                        }
                    }
                    modal.modal({backdrop: 'static', keyboard: false});
                });
            });

            modal.on('shown.bs.modal', function () {
                modal.find('select[name="client"]').chosen().trigger("chosen:updated");
            });

            $(document).on('click', '.create-contract', function(){
                if(modal.find('select[name="client"]').val() != ''){
                    window.location = 'create-contract/'+ modal.find('select[name="client"]').val();
                }
            });
        });
    </script>
@endsection