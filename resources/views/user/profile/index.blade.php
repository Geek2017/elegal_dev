@extends('layouts.master')

@section('title', 'Profile')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Profile</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Profile</strong>
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
                        <h4>Profile</h4>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('method' => 'post', 'url'=>route('profile.store')))}}
                        <div class="row">
                            @if(Session::has('message'))
                                <br/>
                                <div class="col-md-12">
                                  <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <span class="fa fa-ok"></span><em> {!! session('message') !!}</em>
                                  </div>
                                </div>
                            @endif

                            <div class="col-sm-8">
                                <h3 class="m-t-none m-b text-success">Basic Information</h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            {{Form::text('firstname',($user->profile)?$user->profile->firstname:null, array('class'=>'form-control', 'required'))}}
                                            @if($errors->has('firstname'))
                                                <span class="text-danger">{{$errors->first('firstname')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            {{Form::text('middlename',($user->profile)?$user->profile->middlename:null, array('class'=>'form-control'))}}
                                            @if($errors->has('middlename'))
                                                <span class="text-danger">{{$errors->first('middlename')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            {{Form::text('lastname',($user->profile)?$user->profile->lastname:null, array('class'=>'form-control', 'required'))}}
                                            @if($errors->has('lastname'))
                                                <span class="text-danger">{{$errors->first('lastname')}}</span> 
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            {{Form::email('email',$user->email, array('class'=>'form-control', 'required'))}}
                                            @if($errors->has('email'))
                                                <span class="text-danger">{{$errors->first('email')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group"></div>
                                        <div class="form-group">
                                            <label>Marital Status</label>
                                            {{Form::select('status', array(
                                            null => 'Select status',
                                            'married' => 'Married (and not separated)',
                                            'widowed' => 'Widowed (including living common law)',
                                            'separated' => 'Separated (including living common law)',
                                            'divorced' => 'Divorced (including living common law)',
                                            'single' => 'Single (including living common law)'
                                            ),($user->profile)?$user->profile->status:null,array('class'=>'form-control', 'required'))}}
                                            @if($errors->has('status'))
                                                <span class="text-danger">{{$errors->first('status')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Blood Type</label>
                                            {{Form::select('blood_type', array(
                                            null => 'Select Blood Type',
                                            'O negative' => 'O negative',
                                            'O positive' => 'O positive',
                                            'A negative' => 'A negative',
                                            'A positive' => 'A positive',
                                            'B negative' => 'B negative',
                                            'B positive' => 'B positive',
                                            'AB negative' => 'AB negative',
                                            'AB positive' => 'AB positive',
                                            ),($user->profile)?$user->profile->blood_type:null,array('class'=>'form-control', 'required'))}}
                                            @if($errors->has('blood_type'))
                                                <span class="text-danger">{{$errors->first('blood_type')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="photo_holder">
                                        <img alt="image" class="img-responsive" src="/img/placeholder.jpg">
                                    </div>
                                    <div id="validation-errors"></div>
                                    {!! Form::hidden('image',($user->profile)?$user->profile->image:null,array('id'=>'image_path','class'=>'required')) !!}
                                    <div class="progress upload-progress" style="display: none;">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <span class="sr-only"><span class="percentage"></span> Complete</span>
                                        </div>
                                    </div>
                                    @if($errors->has('image'))
                                        <span class="text-danger">{{$errors->first('image')}}</span>
                                    @endif
                                </div>
                                @if(auth()->user()->can('edit-profile'))
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="photo_file_trigger">Select Image</button>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="hr-line-dashed"></div>
                                @if(auth()->user()->can('edit-profile'))
                                <div class="text-right">
                                    {{Form::submit('Update profile', array('class'=>'btn btn-md btn-success'))}}
                                </div>
                                @endif
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
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
@endsection

@section('scripts')
    <!-- Data picker -->
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/jquery.form.min.js') !!}
    {!! Html::script('js/image-uploader.js') !!}
    <script>
        $(document).ready(function(){
            var modal = $('#modal');
            $('#modal-btn').on('click',function(){
                modal.modal({backdrop: 'static', keyboard: false});
            });

            $('#data_3 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

            $(document).on('click','.add-icoe',function(){
                $('#icoe-first').clone().appendTo('#icoe-second');
                $(this).hide();
                $('#icoe-second').append('<button type="button" class="btn btn-danger remove-icoe">Remove</button>')
            });
            $(document).on('click','.remove-icoe',function(){
                $('#icoe-second').empty();
                $('.add-icoe').show();
                $(this).remove();
            });

            if ($('#image_path').val().length != 0) {
                $('.photo_holder').empty().append('<img src="/uploads/image/'+ $('#image_path').val() +'" alt="Image" class="img-responsive">');
            }

        });
    </script>
@endsection