@extends('layouts.master')

@section('title', 'Create Billing')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-5">
            <h2>Create Billing</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Create Billing</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-7">
            <div class="title-action">
                <button type="submit" class="btn btn-primary">Create Billing</button>
            </div>
        </div>
    </div>

    <div class="fh-breadcrumb">

        <div class="fh-column">
            <div class="p-md bg-warning text-center">
                <h3>Select Service Reports</h3>
            </div>
            <div class="full-height-scroll">
                <ul class="list-group elements-list">
                    @foreach($service_reports as $key => $sr)
                        <li class="list-group-item @if ($key === 0) active @endif">
                            <a data-toggle="tab" href="#tab-{!! $key !!}">
                                <small class="pull-right text-muted"> {!! \Carbon\Carbon::parse($sr->created_at)->diffForHumans() !!}</small>
                                <strong>{!! $sr->feeDetail->transaction->client->fname !!} {!! $sr->feeDetail->transaction->client->lname !!}</strong>
                                <div class="small m-t-md">
                                    <h3 class="text-success">{!! $sr->feeDetail->fee->display_name !!}</h3>
                                    <p class="m-b-none">{!! $sr->feeDetail->cases->title !!}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

        <div class="full-height">
            <div class="full-height-scroll white-bg border-left">

                <div class="element-detail-box">

                    <div class="tab-content">
                        @foreach($service_reports as $key => $sr)
                        <div id="tab-{!! $key !!}" class="tab-pane @if ($key === 0) active @endif">

                            {{--<div class="pull-right">--}}
                                {{--<div class="tooltip-demo">--}}
                                    {{--<button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="left" title="Plug this message"><i class="fa fa-plug"></i> Plug it</button>--}}
                                    {{--<button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>--}}
                                    {{--<button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mark as important"><i class="fa fa-exclamation"></i> </button>--}}
                                    {{--<button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Move to trash"><i class="fa fa-trash-o"></i> </button>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="row">

                                <div class="col-sm-4">

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    Service Report Detail
                                                </div>
                                                <div class="panel-body">
                                                    <div class="small text-muted"><i class="fa fa-clock-o"></i> {!! \Carbon\Carbon::parse($sr->created_at)->toDayDateTimeString() !!}</div>
                                                    <h1>{!! $sr->feeDetail->fee->display_name !!}</h1>

                                                    <table class="table invoice-table">
                                                        <thead>
                                                        <tr>
                                                            <th>List</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php $total = 0; @endphp
                                                        @if($sr->feeDetail->charge_doc > 0)
                                                            @php $total += $sr->feeDetail->charge_doc; @endphp
                                                            <tr>
                                                                <td><div><strong>Charge Doc</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->charge_doc, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->rate_1 > 0)
                                                            @php $total += $sr->feeDetail->rate_1; @endphp
                                                            <tr>
                                                                <td><div><strong>Rate 1</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->rate_1, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->rate_2 > 0)
                                                            @php $total += $sr->feeDetail->rate_2; @endphp
                                                            <tr>
                                                                <td><div><strong>Rate 2</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->rate_2, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->rate > 0)
                                                            @php $total += $sr->feeDetail->rate; @endphp
                                                            <tr>
                                                                <td><div><strong>Rate</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->rate, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->consumable_time > 0)
                                                            <tr>
                                                                <td><div><strong>Consumable Time</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->consumable_time, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->excess_rate > 0)
                                                            @php $total += $sr->feeDetail->excess_rate; @endphp
                                                            <tr>
                                                                <td><div><strong>Excess Rate</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->excess_rate, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->amount > 0)
                                                            @php $total += $sr->feeDetail->amount; @endphp
                                                            <tr>
                                                                <td><div><strong>Amount</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->amount, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        @if($sr->feeDetail->cap_value > 0)
                                                            @php $total += $sr->feeDetail->cap_value; @endphp
                                                            <tr>
                                                                <td><div><strong>Cap Value</strong></div>
                                                                <td>{!! number_format($sr->feeDetail->cap_value, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                    <table class="table invoice-total">
                                                        <tbody>
                                                        <tr>
                                                            <td><strong>TOTAL :</strong></td>
                                                            <td>{!! number_format($total, 2, '.', ',') !!}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    Chargeable Expense
                                                </div>
                                                <div class="panel-body">

                                                    <table class="table invoice-table">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                            <th>Date Added</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php $total_expense = 0; @endphp
                                                        @foreach($sr->feeDetail->chargeables as $chargeable)
                                                            @php $total_expense += $chargeable->amount; @endphp
                                                            <tr>
                                                                <td><div><strong>{!! $chargeable->name !!}</strong></div>
                                                                <td><small>{!! $chargeable->description !!}</small></td>
                                                                <td>{!! \Carbon\Carbon::parse($chargeable->created_at)->toFormattedDateString() !!}
                                                                    <small class="text-success"> [{!! \Carbon\Carbon::parse($chargeable->created_at)->diffForHumans() !!}] </small>
                                                                </td>
                                                                <td>{!! number_format($chargeable->amount, 2, '.', ',') !!}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                    <table class="table invoice-total">
                                                        <tbody>
                                                        <tr>
                                                            <td><strong>TOTAL :</strong></td>
                                                            <td>{!! number_format($total_expense, 2, '.', ',') !!}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    Available Trust Fund
                                                </div>
                                                <div class="panel-body">
                                                    <div class="text-right">00.00</div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    Grand Total
                                                </div>
                                                <div class="panel-body">
                                                    <div class="text-right">00.00</div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>



                        </div>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/codemirror/codemirror.css') !!}
    {!! Html::style('css/plugins/codemirror/ambiance.css') !!}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    <script>
        $(document).ready(function(){
            $('body').addClass('fixed-sidebar no-skin-config full-height-layout');


        });
    </script>
@endsection