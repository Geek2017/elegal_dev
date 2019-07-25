@extends('layouts.master')

@section('title', 'Blank|Page')


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
                <button type="submit" class="btn btn-primary">Button</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content billing-show" id="billing-box">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox">
                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-sm-4 col-sm-push-8">
                                <div class="professional-services">
                                    <h2>Professional Services</h2>
                                    <table>
                                        <tbody>
                                        <tr><td>Date</td><td class="bg-muted">{!! \Carbon\Carbon::parse($data->bill_date)->toFormattedDateString() !!}</td></tr>
                                        <tr><td>Invoice</td><td>{!! $data->bill_number !!}</td></tr>
                                        <tr><td>Customer ID</td><td>[ {!! $data->client->count !!} ]</td></tr>
                                        <tr><td>Billing Period</td><td>May 1 - 31, 2018</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">

                                <div class="bill-to">

                                    <table>
                                        <thead>
                                        <tr><th colspan="2">Bill to:</th></tr>
                                        </thead>
                                        <tbody>
                                        <tr><td>{!! $data->client->profile->full_name !!}</td></tr>
                                        @if($data->client->billing_address == null)
                                            <tr><td>{!! $data->client->business->name !!}</td></tr>
                                        @endif
                                        <tr><td>{!! ($data->client->billing_address == null) ? $data->client->business->address->description : $data->client->billing_address !!}</td></tr>
                                        <tr><td>{!! ($data->client->business->telephone == null) ? $data->client->business->mobile->description : $data->client->business->telephone->description !!}</td></tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default summary">
                                    <div class="panel-heading text-center"><strong>Billing Summary</strong></div>
                                    <div class="panel-body professional-fee"><h5><i>Items Subject to Tax [ PROFESSIONAL FEES ]</i></h5></div>
                                    <div class="panel-body chargeable-fee"><h5><i>Items Not Subject Tax [ CHARGEABLE EXPENSES ]</i></h5></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-default summary">
                                    <div class="panel-heading"><strong>Other Comments</strong></div>
                                    <div class="p-xs">
                                        {!! $note->description !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="total-footer">
                                    <table>
                                        <thead>
                                        <tr><td>Make all checks payable to</td></tr>
                                        </thead>
                                        <tbody>
                                        <tr><td>Atty. Peter Leo M. Ralla</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="text-center">
                                    <p>If you have any questions about this invoice, please contact.</p>
                                    <p>[ Name, Phone #, E-mail ]</p>
                                    <h3><i>Thank You For Your Business!</i></h3>
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
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/numeral.js') !!}
    <script>
        $(document).ready(function(){
            var billBox = $('#billing-box');
            $.get('{!! route('bill-info', array('id'=>$data->id)) !!}', function(data){
                console.log(data);
                console.log(data[1].prev_balance.length);

                var pfSubTotal = 0;
                var ceSubTotal = 0;

                var totalBalance = 0;
                var previousBalance = [];

                switch (true){
                    case (data[1].prev_balance.length > 0):
                        for(var a = 0; a < data[1].prev_balance.length; a++){
                            totalBalance += parseFloat(data[1].prev_balance[a].total);
                            previousBalance.push('<tr><td></td><td colspan="2">Bill #'+ data[1].prev_balance[a].bill_number +'</td>' +
                                '<td>'+ numeral(data[1].prev_balance[a].total).format('0,0.00') +'</td></tr>');
                        }
                        break;
                    default:
                        previousBalance.push('<tr><td></td><td colspan="2">- none -</td><td>0.00</td></tr>');
                }
                pfSubTotal += totalBalance;

                billBox.find('.professional-fee').append('' +
                    '<div class="row">' +
                    '<div class="col-sm-12">' +
                    '<div class="title"><h5>Previous Balance <span class="pull-right">'+ numeral(totalBalance).format('0,0.00') +'</span></h5></div>' +
                    '<div class="table-responsive">' +
                    '<table>' +
                    '<tbody>' +
                    '<tr><td colspan="3"><strong>Balance from previous bill</strong></td><td></td></tr>' +
                    '' + previousBalance +
                    '</tbody>' +
                    '<tbody>' +
                    '<tr><td><strong>Less: </strong></td><td colspan="2">Adjustment</td><td>0.00</td></tr>' +
                    '<tr><td></td><td>Payment Received</td><td></td><td>0.00</td></tr>' +
                    '<tr><td></td><td colspan="2"><strong>Unpaid Balance</strong></td><td><strong>'+ numeral(totalBalance).format('0,0.00') +'</strong></td></tr>' +
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '');

                var specialRetainer = 0;
                var fixedGR = 0;
                var excessGR = 0;
                var totalCurrent = 0;

                for(var b = 0; b < data[1].length; b++){
                    switch (data[1]){

                    }
                }
                pfSubTotal += totalCurrent;

                billBox.find('.professional-fee').append('' +
                    '<div class="row">' +
                    '<div class="col-sm-12">' +
                    '<div class="title"><h5>Current Charges <span class="pull-right">'+ numeral().format('0,0.00') +'</span></h5></div>' +
                    '<div class="table-responsive">' +
                    '<table>' +
                    '<tbody>' +
                    '<tr><td colspan="3">Special Retainers</td><td>'+ numeral(specialRetainer).format('0,0.00') +'</td></tr>' +
                    '<tr><td colspan="3">Fixed General Retainer</td><td>'+ numeral(fixedGR).format('0,0.00') +'</td></tr>' +
                    '<tr class="bill-bottom"><td colspan="3">Excess General Retainers</td><td>'+ numeral(excessGR).format('0,0.00') +'</td></tr>' +
                    '</tbody>' +
                    '<tbody>' +
                    '<tr><td></td><td colspan="2"><strong>Total Current Charges</strong></td><td><strong>'+ numeral(totalCurrent).format('0,0.00') +'</strong></td></tr>' +
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '');

                billBox.find('.professional-fee').append('' +
                    '<div class="row">' +
                    '<div class="col-sm-12">' +
                    '<div class="title"><h5>Subtotal <span class="pull-right">'+ numeral(pfSubTotal).format('0,0.00') +'</span></h5></div>' +
                    '</div>' +
                    '</div>' +
                    '');

                billBox.find('.chargeable-fee').append('' +
                    '<div class="row">' +
                    '<div class="col-sm-12">' +
                    '<div class="title"><h5>Previous Balance <span class="pull-right">52,000.00</span></h5></div>' +
                    '<div class="table-responsive">' +
                    '<table>' +
                    '<tbody>' +
                    '<tr><td colspan="3">Balance from previous bill</td><td>0.00</td></tr>' +
                    '</tbody>' +
                    '<tbody>' +
                    '<tr class="tr-line"><td><strong>Less: </strong></td><td colspan="2">Reimbursement Received</td><td>0.00</td></tr>' +
                    '<tr><td></td><td colspan="2"><strong>Unpaid Balance</strong></td><td><strong>52,000.00</strong></td></tr>' +
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '<br>' +
                    '<div class="table-responsive">' +
                    '<table>' +
                    '<tbody>' +
                    '<tr><td><strong>Add: </strong></td><td colspan="2">Current Charges</td><td>500.00</td></tr>' +
                    '<tr><td>Less:</td><td>Deposit</td><td></td><td>1,230.00</td></tr>' +
                    '<tr><td></td><td colspan="2"><strong>Total Amount ForReimbursement</strong></td><td><strong>0.00</strong></td></tr>' +
                    '</tbody>' +
                    '</table>' +
                    '</div>' +
                    '<br>' +
                    '<p><strong>Please Immediately reimburse defecit amount of your Trust Fund Account</strong></p>' +
                    '</div>' +
                    '</div>' +
                    '');


                billBox.find('.total-footer').prepend('' +
                    '<table class="table">' +
                    '<tbody>' +
                    '<tr><td>Professional Fees</td><td><span>&#8369;</span> 1,000,000.00</td></tr>' +
                    '<tr><td>Percentage Tax on PF</td><td><span>&#8369;</span> 1,000,000.00</td></tr>' +
                    '<tr><td>Chargeables</td><td><span>&#8369;</span> 1,000,000.00</td></tr>' +
                    '<tr><td>Other</td><td><span>&#8369;</span> 1,000,000.00</td></tr>' +
                    '<tr><td>Total Amount Due</td><td><span>&#8369;</span> 1,000,000.00</td></tr>' +
                    '</tbody>' +
                    '</table>' +
                    '');
            });
        });
    </script>
@endsection