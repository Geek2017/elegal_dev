@extends('layouts.master')

@section('title', 'Billing Info')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Billing Info</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Billing Info</strong>
                </li>
            </ol>
        </div>

        <div class="col-lg-4">
            <div class="title-action">
                @if($count > 0)
                <a href="{{ route('billing.pdf-preview', $data->id) }}" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print Bill</a>
                @endif
                @if( ($data->special_billing === 0) && ($data->id === $last->id) )
                <a href="{{ route('billing.edit', array('billing' => $data->id)) }}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit Bill</a>
                @endif
            </div>
        </div>

    </div>

    {{--<div class="wrapper wrapper-content billing-show billing-mockup">--}}
    <div class="wrapper wrapper-content ">
        <div class="billing-show">
            <div class="row">
                <div class="col-sm-12">
                    {!! $data->content !!}
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/numeral.js') !!}

    <script>
        $(document).ready(function(){
//            $('.wrapper-content > div').addClass('billing-show');

            $(document).on('click','#void',function(){
                console.log($(this).data('id'));
            });

        });
    </script>
@endsection