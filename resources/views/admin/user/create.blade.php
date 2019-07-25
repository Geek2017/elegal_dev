@extends('layouts.master')

@section('title', 'Post page')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create User</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="/user">User</a>
                </li>
                <li class="active">
                    <strong>Create User</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Create User <small>complete all fields</small></h5>

                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                {{Form::open(array('route'=>array('user.store')))}}
                                <div class="form-group">
                                    <label>Name</label>
                                    {{Form::text('name',null, array('class'=>'form-control'))}}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{$errors->first('name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    {{Form::text('email',null, array('class'=>'form-control'))}}
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{$errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    {{Form::password('password', array('class'=>'form-control'))}}
                                    @if($errors->has('password'))
                                        <span class="text-danger">{{$errors->first('password')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Repeat Password</label>
                                    {{Form::password('repeat-password', array('class'=>'form-control'))}}
                                    @if($errors->has('repeat-password'))
                                        <span class="text-danger">{{$errors->first('repeat-password')}}</span>
                                    @endif
                                </div>
                                {{Form::submit('Create', array('class'=>'btn btn-success'))}}
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')

@endsection

@section('scripts')

@endsection