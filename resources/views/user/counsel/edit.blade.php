@extends('layouts.master')

@section('title', 'Counsel|Edit')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Edit Counsel</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('counsel.index') !!}">Counsel List</a>
                </li>
                <li class="active">
                    <strong>Edit Counsel</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Counsel <small>Basic Info</small></h5>
                    </div>
                    <div class="ibox-content">
                        {{Form::model($counsel, array('route'=>array('counsel.update',$counsel), 'method' => 'put'))}}
                        <div class="row">
                            <div class="col-md-5 col-md-push-7">
                                <div class="form-group">
                                    <div class="photo_holder">
                                        <img alt="image" class="img-responsive" src="/img/placeholder.jpg">
                                    </div>
                                    <div id="validation-errors"></div>
                                    {!! Form::hidden('image',$counsel->profile->image,array('id'=>'image_path','class'=>'required')) !!}
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
                            <div class="col-md-7 col-md-pull-5">
                                <div class="form-group">
                                    <label>First Name</label>
                                    {{Form::text('firstname',$counsel->profile->firstname, array('class'=>'form-control'))}}
                                    @if($errors->has('firstname'))
                                        <span class="text-danger">{{$errors->first('firstname')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    {{Form::text('middlename',$counsel->profile->middlename, array('class'=>'form-control'))}}
                                    @if($errors->has('middlename'))
                                        <span class="text-danger">{{$errors->first('middlename')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    {{Form::text('lastname',$counsel->profile->lastname, array('class'=>'form-control'))}}
                                    @if($errors->has('lastname'))
                                        <span class="text-danger">{{$errors->first('lastname')}}</span>
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
                                    {{Form::text('lawyer_type',null, array('class'=>'form-control'))}}
                                    @if($errors->has('lawyer-type'))
                                        <span class="text-danger">{{$errors->first('lawyer-type')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Lawyer Code</label>
                                    {{Form::text('lawyer_code',null, array('class'=>'form-control'))}}
                                    @if($errors->has('lawyer-code'))
                                        <span class="text-danger">{{$errors->first('lawyer-code')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    {{Form::textarea('address',$counsel->address->description, array('class'=>'form-control resize-vertical'))}}
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{$errors->first('address')}}</span>
                                    @endif
                                </div>
                                {{Form::submit('Update', array('class'=>'btn btn-success'))}}
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
                $('.photo_holder').empty().append('<img src="/uploads/image/'+ $('#image_path').val() +'" alt="Image" class="img-responsive">');
            }
        });
    </script>
@endsection