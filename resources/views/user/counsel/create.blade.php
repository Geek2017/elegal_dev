@extends('layouts.master')

@section('title', 'Counsel|Create')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Create Counsel</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('counsel.index') !!}">Counsel List</a>
                </li>
                <li class="active">
                    <strong>Create Counsel</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Counsel <small>Basic Info</small></h5>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('route'=>array('counsel.store')))}}
                        <div class="row">
                            <div class="col-sm-5 col-sm-push-7">
                                <div class="form-group">
                                    <div class="photo_holder">
                                        <img alt="image" class="img-responsive" src="/img/placeholder.jpg">
                                    </div>
                                    <div id="validation-errors"></div>
                                    {!! Form::hidden('image',null,array('id'=>'image_path','class'=>'required')) !!}
                                    <div class="progress upload-progress" style="display: none;">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <span class="sr-only"><span class="percentage"></span> Complete</span>
                                        </div>
                                    </div>
                                    @if($errors->has('image'))
                                        <span class="text-danger">{{$errors->first('image')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="photo_file_trigger">Select Image</button>
                                </div>
                            </div>
                            <div class="col-sm-7 col-sm-pull-5">
                                <div class="form-group">
                                    <label>First Name</label>
                                    {{Form::text('first-name',null, array('class'=>'form-control'))}}
                                    @if($errors->has('first-name'))
                                        <span class="text-danger">{{$errors->first('first-name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    {{Form::text('middle-name',null, array('class'=>'form-control'))}}
                                    @if($errors->has('middle-name'))
                                        <span class="text-danger">{{$errors->first('middle-name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    {{Form::text('last-name',null, array('class'=>'form-control'))}}
                                    @if($errors->has('last-name'))
                                        <span class="text-danger">{{$errors->first('last-name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    {{Form::email('email',null, array('class'=>'form-control'))}}
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{$errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Lawyer Type</label>
                                    {{Form::text('lawyer-type',null, array('class'=>'form-control'))}}
                                    @if($errors->has('lawyer-type'))
                                        <span class="text-danger">{{$errors->first('lawyer-type')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Lawyer Code</label>
                                    {{Form::text('lawyer-code',null, array('class'=>'form-control'))}}
                                    @if($errors->has('lawyer-code'))
                                        <span class="text-danger">{{$errors->first('lawyer-code')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    {{Form::textarea('address',null, array('class'=>'form-control resize-vertical'))}}
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{$errors->first('address')}}</span>
                                    @endif
                                </div>
                                {{Form::submit('Create', array('class'=>'btn btn-success'))}}
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Photo Uploader Form --}}
    {!! Form::open(array('route'=>'upload-image','files'=>'true','id'=>'image_uploader','class'=>'sr-only')) !!}
    {!! Form::file('photo',array('id'=>'photo_file_input')) !!}
    {!! Form::close() !!}

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {!! Html::script('js/jquery.form.min.js') !!}
    {!! Html::script('js/image-uploader.js') !!}
    <script>
        $(document).ready(function(){
            if($('#image_path').val().length != 0){
                $('.photo_holder').empty().append('<img src="/temp/image/'+ $('#image_path').val() +'" alt="Image" class="img-responsive">');
            }
        });
    </script>
@endsection