@extends('layouts.master')

@section('title', 'Create Client')


@section('content')
    {{Form::open(array('route'=>array('client.store')))}}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Create Client</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('client.index') !!}">Client List</a>
                </li>
                <li class="active">
                    <strong>Create Client</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                {{Form::submit('Create Client', array('class'=>'btn btn-success saver'))}}
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Basic Info</h5>
                    </div>
                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-sm-5 col-sm-push-7">
                                <div class="form-group">
                                    <label>Photo</label>
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
                                    <label>First Name <span><small class="text-danger">[ required ]</small></span></label>
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
                                    <label>Last Name <span><small class="text-danger">[ required ]</small></span></label>
                                    {{Form::text('last-name',null, array('class'=>'form-control'))}}
                                    @if($errors->has('last-name'))
                                        <span class="text-danger">{{$errors->first('last-name')}}</span>
                                    @endif
                                </div>
                                <div>
                                    <label> <input type="checkbox" name="walk_in" class="bs-switch"> Is Client Walk-In?</label>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Business Info</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Business name</label>
                                            {{Form::text('business-name',null, array('class'=>'form-control'))}}
                                            @if($errors->has('business-name'))
                                                <span class="text-danger">{{$errors->first('business-name')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile no.</label>
                                            {{Form::number('business-mobile',null, array('class'=>'form-control'))}}
                                            @if($errors->has('business-mobile'))
                                                <span class="text-danger">{{$errors->first('business-mobile')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Telephone no.</label>
                                            {{Form::number('business-telephone',null, array('class'=>'form-control'))}}
                                            @if($errors->has('business-telephone'))
                                                <span class="text-danger">{{$errors->first('business-telephone')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label>Address <span><small class="text-danger">[ required ]</small></span></label>
                                            {{Form::textarea('business-address',null, array('class'=>'form-control resize-vertical'))}}
                                            @if($errors->has('business-address'))
                                                <span class="text-danger">{{$errors->first('business-address')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            {{Form::email('email',null, array('class'=>'form-control'))}}
                                            @if($errors->has('email'))
                                                <span class="text-danger">{{$errors->first('email')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5 class="pull-left">Billing Address <small class="text-success">[ If billing address is different from Client Business Info ]</small></h5>
                                <button type="button" id="billing-btn" data-action="add" class="btn btn-success btn-xs pull-right">Add</button>
                                <input type="hidden" name="billing" value="0">
                            </div>
                            <div class="ibox-content" style="display: none">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label>Billing business name</label>
                                            <input type="text" name="billing-business-name" class="form-control">
                                            @if($errors->has('billing-business-name'))
                                                <span class="text-danger">{{$errors->first('billing-business-name')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Billing address</label>
                                            <textarea name="billing-address" class="form-control resize-vertical"></textarea>
                                            @if($errors->has('billing-address'))
                                                <span class="text-danger">{{$errors->first('billing-address')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Billing in charge</label>
                                            <input type="text" name="billing-in-charge" class="form-control">
                                            @if($errors->has('billing-in-charge'))
                                                <span class="text-danger">{{$errors->first('billing-in-charge')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Billing Telephone no.</label>
                                            <input type="text" name="billing-telephone" class="form-control">
                                            @if($errors->has('billing-telephone'))
                                                <span class="text-danger">{{$errors->first('billing-telephone')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    {{Form::close()}}

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
    {!! Html::script('js/bootstrap.min.js') !!}
    {!! Html::script('js/plugins/bootstrap-switch/bootstrap-switch.js') !!}
    <script>
        $(document).ready(function(){

            $(".bs-switch").bootstrapSwitch({
                on: 'Yes',
                off: 'No',
                onClass: 'success',
                offClass: 'default',
                size: 'sm',
            });


            if($('#image_path').val().length != 0){
                $('.photo_holder').empty().append('<img src="/temp/image/'+ $('#image_path').val() +'" alt="Image" class="img-responsive">');
            }

            $(document).on('click','#billing-btn',function(){
                var action = $(this).data('action');
                var content = $(this).closest('.ibox').find('.ibox-content');
                switch (action){
                    case 'add':
                        $(this).data('action','delete');
                        $(this).text('remove');
                        $(this).removeClass('btn-success').addClass('btn-danger');
                        $(this).closest('div').find('input[name="billing"]').val(1);
                        content.show();
                        break;
                    default:
                        $(this).data('action','add');
                        $(this).text('add');
                        $(this).removeClass('btn-danger').addClass('btn-success');
                        $(this).closest('div').find('input[name="billing"]').val(0);
                        content.hide();
                }
            });
        });
        $(document).on('click','.saver',function(){
            
            });
              
    </script>
@endsection