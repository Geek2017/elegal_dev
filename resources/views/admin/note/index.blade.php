@extends('layouts.master')

@section('title', 'Note List')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Note List</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Note List</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('note-create') !!}" class="btn btn-primary">Create</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">

                        <table id="table" class="table table-striped dt-responsive" style="width:100%">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>

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
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('note-list') !!}',
                columnDefs: [
                    { className: "text-right", "targets": [ 1 ] }
                ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'action', name: 'action' }
                ]
            });
        });
    </script>
@endsection