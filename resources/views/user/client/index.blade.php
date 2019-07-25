@extends('layouts.master')

@section('title', 'Client List')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Client List</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Client List</strong>
                </li>
            </ol>
        </div>
        @if(auth()->user()->can('add-client'))
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('client.create') !!}" class="btn btn-primary">Create</a>
            </div>
        </div>
        @endif
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Client <small>list</small></h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
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

@endsection


@section('styles')
    {!! Html::style('css/dataTables.min.css') !!}
    {!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/dataTables.min.js') !!}
    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
    <script>
        $(document).ready(function(){
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('client-list') !!}',
                columnDefs: [
//                    { className: "text-right", "targets": [ 3 ] }
                ],
                columns: [
                    { data: 'count', name: 'count' },
                    { data: 'name', name: 'name' },
                    { data: 'address', name: 'address' },
                    { data: 'action', name: 'action' }
                ]
            });

            $(document).on('click','.delete-client',function(){
                var id = $(this).data('id');
                $.get('{!! route('client-destroy') !!}',{
                    id: id
                },function(data){
                    table.ajax.reload();
                    console.log(data);

                    {{--if(data == 'able'){--}}
                        {{--swal({--}}
                            {{--title: 'Are you sure?',--}}
                            {{--text: 'Your will not be able to recover this Client',--}}
                            {{--type: 'warning',--}}
                            {{--showCancelButton: true,--}}
                            {{--confirmButtonColor: '#DD6B55',--}}
                            {{--confirmButtonText: 'Yes, delete it!',--}}
                            {{--cancelButtonText: 'No, cancel pls!'--}}
                        {{--},--}}
                        {{--function (isConfirm) {--}}
                            {{--if (isConfirm) {--}}
                                {{--$.get('{!! route('client-destroy') !!}',{--}}
                                    {{--id: id,--}}
                                    {{--type: 'delete'--}}
                                {{--},function(data){--}}
{{--//                                    console.log(data);--}}
                                    {{--if(data == 'deleted'){--}}
                                        {{--swal("Deleted!", "Client Deleted", "success");--}}
                                    {{--}--}}
                                {{--});--}}
                            {{--}--}}
                        {{--});--}}
                    {{--}else{--}}
                        {{--swal("Cancelled", "The Client already has Transactions", "error");--}}
                    {{--}--}}
                });
            });
        });
    </script>
@endsection