@extends('layouts.master')

@section('title', ($type == 'edit') ? 'Edit Note' : 'Create Note')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>{!! ($type == 'edit') ? 'Edit Note' : 'Create Note' !!}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>{!! ($type == 'edit') ? 'Edit Note' : 'Create Note' !!}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="button" class="btn btn-primary" id="submit" data-type="{!! ($type == 'edit') ? 'edit' : 'create' !!}">{!! ($type == 'edit') ? 'Update' : 'Create' !!}</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">

                    <div class="ibox-content">

                        <div class="form-group">
                            <label>Title</label>
                            {!! Form::text('name', ($type == 'edit') ? $data->display_name : null, array('class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <div class="summernote">{!! ($type == 'edit') ? $data->description : null !!}</div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/summernote/summernote.css') !!}
    {!! Html::style('css/plugins/summernote/summernote-bs3.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/summernote/summernote.min.js') !!}
    <script>
        $(document).ready(function(){
            $('.summernote').summernote();
            $(document).on('click','#submit',function(){
                $.post('{!! route('note-store') !!}',{
                    _token: '{!! csrf_token() !!}',
                    type: $(this).data('type'),
                    id: '{!! ($type == 'edit') ? $data->id : null !!}',
                    name: $('.ibox-content').find('input[name="name"]').val(),
                    description: $('.summernote').summernote('code')
                },function(data){
                    window.location.replace('{!! route('note') !!}');
                });
            });
        });
    </script>
@endsection