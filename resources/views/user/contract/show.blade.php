@extends('layouts.master')

@section('title', 'Contract Show')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Contract Show</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Contract Show</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            {{--<div class="title-action">--}}
                {{--<button type="submit" class="btn btn-primary">Button</button>--}}
            {{--</div>--}}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="wrapper wrapper-content animated fadeInUp">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="m-b-md">
                                    @if(auth()->user()->can('edit-contract'))
                                    <a href="{!! route('contract.edit', array('contract'=>$contract->id)) !!}" class="btn btn-primary btn-xs pull-right">Edit Contract</a>
                                    @endif
                                    <h2>Contract with {!! $contract->client->profile->full_name !!}</h2>
                                </div>
                                <dl class="dl-horizontal">
                                    <dt>Status:</dt> <dd><span class="label label-primary">{!! ucfirst($contract->status) !!}</span></dd>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <dl class="dl-horizontal">
                                    <dt>Created by:</dt> <dd>{!! $contract->transaction->user->name !!}</dd>
                                    <dt>Contract Number:</dt> <dd>  {!! $contract->contract_number !!}</dd>
                                    <dt>Client:</dt> <dd><a href="#" class="text-navy"> {!! $contract->client->profile->full_name !!}</a> </dd>
                                </dl>
                            </div>
                            <div class="col-lg-7" id="cluster_info">
                                <dl class="dl-horizontal" >

                                    <dt>Last Updated:</dt> <dd> {!! \Carbon\Carbon::parse($contract->updated_at)->toDayDateTimeString() !!} </dd>
                                    <dt>Created:</dt> <dd> 	{!! \Carbon\Carbon::parse($contract->created_at)->toDayDateTimeString() !!} </dd>
                                    @if($contract->contract_type == 'general')
                                        <dt>Counsel:</dt>
                                        <dd class="project-people">
                                            <img alt="image" class="img-circle" title="Atty. {!! $data->counsel->profile->full_name !!}" src="{!! ($data->counsel->profile->image == null) ? '/img/placeholder.jpg' : '/uploads/image/'.$data->counsel->profile->image !!}">
                                        </dd>
                                    @endif

                                </dl>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row m-t-sm">
                            <div class="col-lg-12">
                                @switch($contract->contract_type)
                                    @case('special')
                                    <div class="panel blank-panel">

                                        <div class="panel-heading">
                                            <div class="panel-options">
                                                <ul class="nav nav-tabs">
                                                    @for($a = 0; $a < sizeof($data); $a++)
                                                        <li class="{!! ($a === 0) ? 'active' : '' !!}">
                                                            <a href="#{!! $data[$a]->id !!}" data-toggle="tab"> Case #{!! $a + 1 !!} </a>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="panel-body">

                                            <div class="tab-content">
                                                @for($a = 0; $a < count($data); $a++)
                                                    <div class="tab-pane {!! ($a === 0) ? 'active' : '' !!}" id="{!! $data[$a]->id !!}">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <dl class="dl-horizontal">
                                                                    <dt>Title:</dt> <dd>{!! $data[$a]->title !!}</dd>
                                                                    <dt>Venue:</dt> <dd>{!! $data[$a]->venue !!}</dd>
                                                                    <dt>Case Date:</dt> <dd>{!! \Carbon\Carbon::parse($data[$a]->date)->toFormattedDateString() !!}</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <dl class="dl-horizontal">
                                                                    <dt>Case Number:</dt> <dd>{!! $data[$a]->number !!}</dd>
                                                                    <dt>Case Classification:</dt> <dd>{!! $data[$a]->class !!}</dd>
                                                                    <dt>Status:</dt> <dd>{!! $data[$a]->status !!}</dd>
                                                                    <dt>Counsel:</dt>
                                                                    <dd class="project-people">
                                                                        @foreach($data[$a]->counselList as $counsel)
                                                                        {{--<a href=""><img alt="image" class="img-circle" src="{!! ($counsel->info->profile->image == null) ? '/img/placeholder.jpg' : '/uploads/image/'.$counsel->info->profile->image !!}"></a>--}}
                                                                        <img alt="image" class="img-circle" title="Atty. {!! $counsel->info->profile->full_name !!}" src="{!! ($counsel->info->profile->image == null) ? '/img/placeholder.jpg' : '/uploads/image/'.$counsel->info->profile->image !!}">
                                                                        @endforeach
                                                                    </dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                        <div class="hr-line-dashed"></div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <h2>Case Billing Info</h2>
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Bill No.</th>
                                                                            <th>Date</th>
                                                                            <th class="text-right">Amount</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($data[$a]->bills as $bill)
                                                                            <tr>
                                                                                <td>{!! $bill->billInfo->bill_number !!}</td>
                                                                                <td>{!! \Carbon\Carbon::parse($bill->billInfo->bill_date)->toFormattedDateString() !!}</td>
                                                                                <td class="text-right">{!! number_format($bill->amount, 2, '.', ',') !!}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor

                                            </div>

                                        </div>

                                    </div>
                                    @break

                                    @case('general')
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h2>Contract Billing Info</h2>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>Bill No.</th>
                                                        <th>Date</th>
                                                        <th class="text-right">Amount</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($contract->contractBills as $bill)
                                                        <tr>
                                                            <td>{!! $bill->billInfo->bill_number !!}</td>
                                                            <td>{!! \Carbon\Carbon::parse($bill->billInfo->bill_date)->toFormattedDateString() !!}</td>
                                                            <td class="text-right">{!! number_format($bill->amount, 2, '.', ',') !!}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @endswitch

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">

            <div class="wrapper wrapper-content">
                <div class="ibox-content">
                    <h2>Contract details</h2>
                    <dl class="dl-contract">
                        <dt>Contract Type:</dt> <dd>{!! $contract->contract_type !!}</dd>
                        <dt>Contract Number:</dt> <dd>{!! $contract->contract_number !!}</dd>
                        <dt>Contract Date:</dt> <dd>{!! \Carbon\Carbon::parse($contract->contract_date)->toFormattedDateString() !!}</dd>
{{--                        <dt>Start Date:</dt> <dd>{!! \Carbon\Carbon::parse($contract->start_date)->toFormattedDateString() !!}</dd>--}}
                        <dt>End Date:</dt> <dd>{!! \Carbon\Carbon::parse($contract->end_date)->toFormattedDateString() !!}</dd>
                        <dt>Status:</dt> <dd>{!! $contract->status !!}</dd>
                        <dt>Other Conditions:</dt> <dd><p class="small">{!! $contract->other_conditions !!}</p></dd>
                    </dl>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="ibox-content">
                    <h2>Remaining Balance</h2>
                    <h1 class="text-success"><strong>{!! number_format($contract->contract_amount - $contract->contractBills->sum('amount'), 2, '.', ',') !!}</strong></h1>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="ibox-content">
                    <h2>Billing Total</h2>
                    <h1 class="text-success"><strong>{!! number_format($contract->contractBills->sum('amount'), 2, '.', ',') !!}</strong></h1>
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