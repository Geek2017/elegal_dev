@extends('layouts.master')

@section('title', 'Blank|Page')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Blank</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Blank</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('note-edit', array('id'=>$data->id)) !!}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{!! $data->display_name !!}</h5>
                    </div>
                    <div class="ibox-content">

                        {!! $data->description !!}

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