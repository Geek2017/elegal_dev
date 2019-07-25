@extends('layouts.master')

@section('title', 'Cash Receipt Payment | Edit')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Cash Receipts</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="#">Transaction</a>
                </li>
                <li>
                    <a href="#">Cash Receipt</a>
                </li>
                <li class="active">
                    <strong>Cash Receipt Payment</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                       
                    </div>
                    <div class="ibox-content">
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

                            <div class="col-sm-12">
                                <div class="row">
                                    {{Form::open(array('method' => 'PUT', 'route'=>['cash-receipt.update', 'id' => $cashReceipt->id], 'class' => 'form-horizontal', 'id' => 'payment-form'))}}

                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-lg-1 control-label">Client:</label>
                                                    <div class="col-lg-6">
                                                        <input name="payment_date" type="text" class="form-control" id="rc-payment-date" value="{{ $cashReceipt->client->profile->full_name }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-lg-12" id="rc-list-of-bill">
                                                <div class="ibox float-e-margins">
                                                    <div class="ibox-title">
                                                        <h4>
                                                            Paid Bills
                                                        </h4>

                                                        <h4 class="text-right">
                                                            <span>TOTAL:</span>
                                                            <span id='total-amount'>{{ number_format($cashReceipt->amount_paid, 2)}}</span>
                                                        </h4>
                                                    </div>
                                                    <div class="ibox-content">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <table class="table table-striped table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Bill</th>
                                                                            <th>Month</th>
                                                                            <th class="text-right">Tax</th>
                                                                            <!-- <th class="text-right">Amt. Paid</th> -->
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="rc-fee-table-body">
                                                                        @foreach ($cashReceipt->billOperationalFundReceipt as $key => $cr)
                                                                            @if ($cr->billing)
                                                                                <tr>
                                                                                    <td>{{ $cr->billing->bill_number }}</td>
                                                                                    <td>{{ $cr->billing->bill_date->format('F Y') }}</td>
                                                                                    <td class="text-right">{{ ($cr->billing->tax_amount > 0) ? number_format($cr->billing->tax_amount, 2) : '-' }} ({{($cr->billing->tax_amount > 0) ? $cr->billing->percentage_tax.'%' : '-' }})</td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="type" type="hidden" value="billing">
                                                    <input name="billing_ids" type="hidden" id="billing-ids">
                                                    <input name="amount_due" type="hidden" id="billing-amount-due" value="0">
                                                </div>
                                                <div class="form-group"></div><br/>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" >Date:</label>
                                                    <div class="col-lg-8">
                                                        <input name="payment_date" type="date" class="form-control" id="rc-payment-date" value="{{ $cashReceipt->payment_date->format('Y-m-d') }}" required>
                                                        @if ($errors->has('payment_date'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('payment_date') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label" >OR #:</label>
                                                    <div class="col-lg-8">
                                                        <input name="cash_receipt_no" type="text" class="form-control text-right" id="rc-or-no" value="{{ $cashReceipt->cash_receipt_no }}">
                                                        @if ($errors->has('billing_cash_receipt_no'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('billing_cash_receipt_no') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Amount Paid:</label>
                                                    <div class="col-lg-8">
                                                        <input name="amount_paid" type="number" step="any" class="form-control text-right" id="rc-amount-paid" value="{{$cashReceipt->amount_paid}}" required>
                                                        @if ($errors->has('billing_amount_paid'))
                                                            <span class="help-block m-b-none">
                                                                <strong>{{ $errors->first('billing_amount_paid') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group" style="display: none;">
                                                    <label class="col-lg-4 control-label">Act Type:</label>
                                                    <div class="col-lg-8">
                                                        <input name="account_id" type="text" class="form-control" id="rc-account-id" value="{{ $cashReceipt->account_id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12" id="button-wrapper">
                                                {{Form::submit('Update Payment', array('class'=>'btn btn-md btn-success pull-right'))}}
                                            </div>
                                        </div>
                                    </div>

                                    {{Form::close()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/dataTables.min.css') !!}
    {!! Html::style('css/plugins/select2/select2.min.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/dataTables.min.js') !!}
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection