@extends('layouts.master')

@section('title', 'Fee')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Fee list</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Fee list</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('fee.create') !!}" class="action btn btn-primary">Create Fee</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Fee List</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="fee-table">
                                <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Description</th>
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
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/dataTables.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    <script>
        $(document).ready(function(){
            var modal = $('#modal');
            var table = $('#fee-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('fee-list') !!}',
                columnDefs: [
                    { className: "text-right", "targets": [ 4 ] }
                ],
                columns: [
                    { data: 'category', name: 'category' },
                    { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection