@extends('layouts.master')

@section('title', 'Post page')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>User Edit {!! $urole !!}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>User Edit</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>User Edit</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                {{Form::model($user, array('route'=>array('user.update',$user), 'method' => 'put'))}}
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
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" id="" class="form-control">
                                        @foreach($roles as $role)
                                            @if($role->name == $urole)
                                                <option value="{!! $role->name !!}" selected>{!! ucfirst($role->display_name) !!}</option>
                                            @else
                                                <option value="{!! $role->name !!}">{!! ucfirst($role->display_name) !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                {{Form::submit('Save', array('class'=>'btn btn-success'))}}
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