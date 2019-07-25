@extends('layouts.master')

@section('title', 'Create Role')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create Role</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Create Role</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Role <small>Information</small></h5>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('route'=>array('role-store')))}}
                        <div class="form-group">
                            <label>Name</label>
                            {{Form::text('name',null, array('class'=>'form-control'))}}
                            @if($errors->has('name'))
                                <span class="text-danger">{{$errors->first('name')}}</span>
                            @endif
                        </div>
                        {{Form::submit('Create', array('class'=>'btn btn-success'))}}
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection