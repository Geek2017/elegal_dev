@extends('layouts.master')

@section('title', 'Trust Fund Deposits')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Trust Fund Deposits</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Trust Fund Deposits</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" class="btn btn-primary">Button</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content width-limit-lg">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>List of Clients with Trust Fund Deposits </h5>
                    </div>
                    <div class="ibox-content">
                        <h2><small>Total Client Deposits: </small><strong class="text-success">{!! number_format($total, '2', '.', ',') !!}</strong></h2>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                            <tr>
                                <td>{!! $data->client->profile->full_name !!}</td>
                                <td>{!! $data->balance !!}</td>
                                <td>
                                    <a href="{!! route('client.show', array('client'=>$data->client->id)) !!}" class="btn-white btn btn-xs"><i class="fa fa-search text-success"></i> show</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
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