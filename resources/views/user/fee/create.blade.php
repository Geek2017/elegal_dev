@extends('layouts.master')

@section('title', 'Create Fee')


@section('content')
    {{Form::open(array('route'=>array('fee.store')))}}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create Fee</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Create Fee</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" class="btn btn-primary">Store Fee </button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Fee Detail</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label>Fee Name</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($categories as $category)
                                            <option value="{!! $category->id !!}">{!! $category->display_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Fee Description <small class="text-success">[ optional, can add later ]</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div id="desc-box">
                            {{--<div class="row">--}}
                                {{--<div class="col-sm-5">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label>Description</label>--}}
                                        {{--<input type="text" name="desc-name[]" class="form-control">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label>Type</label>--}}
                                        {{--<input type="text" class="form-control" name="desc-description[]">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-3">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label>Default Amount</label>--}}
                                        {{--<input type="text" class="form-control" name="desc-amount[]">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-left">
                                    <button type="button" id="desc-add-btn" class="btn btn-sm btn-success">Add Description</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}
@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    <script>
        $(document).ready(function(){
            $(document).on('click','#desc-add-btn',function(){
                $('#desc-box').append('' +
                    '<div class="row">' +
                        '<div class="col-sm-5">' +
                            '<div class="form-group">' +
                            '<label>Description</label>' +
                            '<input type="text" name="desc-name[]" class="form-control">' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-4">' +
                            '<div class="form-group">' +
                                '<label>Type</label>' +
                                '<input type="text" class="form-control" name="desc-description[]">' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-3">' +
                            '<div class="form-group">' +
                                '<label>Default Amount</label>' +
                                '<span class="pull-right span-btn"><i class="fa fa-times-circle text-danger desc-remove"></i></span>' +
                                '<input type="text" class="form-control" name="desc-amount[]" value="0">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '');
            });

            $(document).on('click','.desc-remove',function(){
                $(this).closest('.row').remove();
            });

        });
    </script>
@endsection