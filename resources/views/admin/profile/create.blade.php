@extends('layouts.master')

@section('title', 'Profile Create')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create Profile</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Profile Create</strong>
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
                        <h5>Create Profile</h5>
                    </div>
                    <div class="ibox-content">
                        {{ Form::open(array('route'=>'profile-store')) }}
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="m-t-none m-b text-success">Basic Information</h3>
                                <div class="row">
                                    <div class="col-lg-5 col-lg-push-7">
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
                                    <div class="col-lg-7 col-lg-pull-5">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            {{Form::text('firstname',null, array('class'=>'form-control'))}}
                                            @if($errors->has('firstname'))
                                                <span class="text-danger">{{$errors->first('firstname')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            {{Form::text('middlename',null, array('class'=>'form-control'))}}
                                            @if($errors->has('middlename'))
                                                <span class="text-danger">{{$errors->first('middlename')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            {{Form::text('lastname',null, array('class'=>'form-control'))}}
                                            @if($errors->has('lastname'))
                                                <span class="text-danger">{{$errors->first('lastname')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group" id="data_3">
                                            <label>Date of Birth</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="dob" class="form-control" value="01/13/2000">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Marital Status</label>
                                            {{Form::select('status', array(
                                            null => 'Select status',
                                            'married' => 'Married (and not separated)',
                                            'widowed' => 'Widowed (including living common law)',
                                            'separated' => 'Separated (including living common law)',
                                            'divorced' => 'Divorced (including living common law)',
                                            'single' => 'Single (including living common law)'
                                            ),null,array('class'=>'form-control'))}}
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
                                            ),null,array('class'=>'form-control'))}}
                                            @if($errors->has('blood_type'))
                                                <span class="text-danger">{{$errors->first('blood_type')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>
                                <h3 class="m-t-none m-b text-success">Contact Information</h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <div class="input-group">
                                                <div class="input-group-select">
                                                    <select name="contact-type" class="form-control">
                                                        <option value="">Select type</option>
                                                        <option value="telephone">Telephone</option>
                                                        <option value="fax">Fax</option>
                                                        <option value="mobile">Mobile</option>
                                                    </select>
                                                </div>
                                                <input type="text" name="contact-number" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Permanent Address</label>
                                            {{Form::textarea('permanent_address',null, array('class'=>'form-control resize-vertical'))}}
                                            @if($errors->has('permanent_address'))
                                                <span class="text-danger">{{$errors->first('permanent_address')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Present Address</label>
                                            {{Form::textarea('present_address',null, array('class'=>'form-control resize-vertical'))}}
                                            @if($errors->has('present_address'))
                                                <span class="text-danger">{{$errors->first('present_address')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>
                                <h3 class="m-t-none m-b text-success">In case of Emergency Contact</h3>
                                <div id="icoe-first">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" name="icoe-name[]" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Relation</label>
                                        <input type="text" name="icoe-relation[]" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <div class="input-group">
                                            <div class="input-group-select">
                                                <select name="icoe-contact-type[]" class="form-control">
                                                    <option value="">Select type</option>
                                                    <option value="telephone">Telephone</option>
                                                    <option value="fax">Fax</option>
                                                    <option value="mobile">Mobile</option>
                                                </select>
                                            </div>
                                            <input type="text" name="icoe-contact-number[]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="icoe-address[]" class="form-control resize-vertical"></textarea>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                                <div id="icoe-second"></div>

                                <button type="button" class="btn btn-success add-icoe">Add another</button>


                            </div>

                            <div class="col-sm-6">
                                <h3 class="m-t-none m-b text-success">Account security question</h3>
                                <div class="form-group">
                                    <label>Question 1</label>
                                    {{Form::select('question[]', array(
                                    null => 'Select Type',
                                    'What was your childhood nickname?' => 'What was your childhood nickname?',
                                    'In what city or town did your mother and father meet?' => 'In what city or town did your mother and father meet?',
                                    'What is your favorite team?' => 'What is your favorite team?',
                                    'What was your favorite sport in high school?' => 'What was your favorite sport in high school?',
                                    'What is the first name of the boy or girl that you first kissed?' => 'What is the first name of the boy or girl that you first kissed?',
                                    'What was the name of the hospital where you were born?' => 'What was the name of the hospital where you were born?',
                                    'What school did you attend for sixth grade?' => 'What school did you attend for sixth grade?',
                                    'In what town was your first job?' => 'In what town was your first job?',
                                    ),null,array('class'=>'form-control'))}}
                                </div>
                                <div class="form-group">
                                    <label>Answer 1</label>
                                    {{Form::text('answer[]',null, array('class'=>'form-control'))}}
                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <label>Question 2</label>
                                    {{Form::select('question[]', array(
                                    null => 'Select Type',
                                    'What is the name of your favorite childhood friend?' => 'What is the name of your favorite childhood friend?',
                                    'What is the middle name of your oldest child?' => 'What is the middle name of your oldest child?',
                                    'What is your favorite movie?' => 'What is your favorite movie?',
                                    'What was your favorite food as a child?' => 'What was your favorite food as a child?',
                                    'What was the make and model of your first car?' => 'What was the make and model of your first car?',
                                    'Who is your childhood sports hero?' => 'Who is your childhood sports hero?',
                                    'What was the last name of your third grade teacher?' => 'What was the last name of your third grade teacher?',
                                    'What was the name of the company where you had your first job?' => 'What was the name of the company where you had your first job?',
                                    ),null,array('class'=>'form-control'))}}
                                </div>
                                <div class="form-group">
                                    <label>Answer 2</label>
                                    {{Form::text('answer[]',null, array('class'=>'form-control'))}}
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="hr-line-dashed"></div>
                                <div class="text-right">
                                    {{Form::submit('Create', array('class'=>'btn btn-lg btn-primary'))}}
                                </div>
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

            if($('#image_path').val().length != 0){
                $('.photo_holder').empty().append('<img src="/temp/image/'+ $('#image_path').val() +'" alt="Image" class="img-responsive">');
            }

        });
    </script>
@endsection