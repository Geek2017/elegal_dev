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
                <button type="submit" class="btn btn-primary">Button</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content billing-mockup billing-show" id="billing-box">

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
                                            <tr><td>Date</td><td class="bg-muted">may 01, 2001</td></tr>
                                            <tr><td>Invoice</td><td>01-000001</td></tr>
                                            <tr><td>Customer ID</td><td>[00001]</td></tr>
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
                                            <tr><td>Name</td></tr>
                                            <tr><td>Company</td></tr>
                                            <tr><td>Address 1</td></tr>
                                            <tr><td>Address 1</td></tr>
                                            <tr><td>Phone</td></tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">

                                <div class="panel panel-default summary">
                                    <div class="panel-heading text-center"><strong>Billing Summary</strong></div>
                                    <div class="panel-body professional-fee"><h5><i>Items Subject to Tax [ PROFESSIONAL FEES ]</i></h5>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="title"><h5>Previous Balance <span class="pull-right">52,000.00</span></h5></div>
                                                <div class="table-responsive">
                                                    <table>
                                                        <tbody>
                                                            <tr><td colspan="3"><strong>Balance from previous bill</strong></td><td></td></tr>
                                                            <tr><td></td><td colspan="2">Bill #18-00001</td><td>52,000.00</td></tr>
                                                        </tbody>
                                                        <tbody>
                                                            <tr><td><strong>Less: </strong></td><td colspan="2">Adjustment</td><td>0.00</td></tr>
                                                            <tr><td></td><td>Payment Received</td><td></td><td>0.00</td></tr>
                                                            <tr><td></td><td colspan="2"><strong>Unpaid Balance</strong></td><td><strong>52,000.00</strong></td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="title"><h5>Current Charges <span class="pull-right">80,783.33</span></h5></div>
                                                <div class="table-responsive">
                                                    <table>
                                                        <tbody>
                                                            <tr><td colspan="3">Special Retainers</td><td>71,200.00</td></tr>
                                                            <tr><td colspan="3">Fixed General Retainer</td><td>9,000.00</td></tr>
                                                            <tr class="bill-bottom"><td colspan="3">Excess General Retainers</td><td>583.33</td></tr>
                                                        </tbody>
                                                        <tbody>
                                                            <tr><td></td><td colspan="2"><strong>Total Current Charges</strong></td><td><strong>80,783.33</strong></td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="title"><h5>Subtotal <span class="pull-right">132,783.33</span></h5></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body chargeable-fee"><h5><i>Items Not Subject Tax [ CHARGEABLE EXPENSES ]</i></h5>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="title"><h5>Previous Balance <span class="pull-right">52,000.00</span></h5></div>
                                                <div class="table-responsive">
                                                    <table>
                                                        <tbody>
                                                            <tr><td colspan="3">Balance from previous bill</td><td>0.00</td></tr>
                                                        </tbody>
                                                        <tbody>
                                                            <tr class="tr-line"><td><strong>Less: </strong></td><td colspan="2">Reimbursement Received</td><td>0.00</td></tr>
                                                            <tr><td></td><td colspan="2"><strong>Unpaid Balance</strong></td><td><strong>52,000.00</strong></td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <br>
                                                <div class="table-responsive">
                                                    <table>
                                                        <tbody>
                                                            <tr><td><strong>Add: </strong></td><td colspan="2">Current Charges</td><td>500.00</td></tr>
                                                            <tr><td>Less:</td><td>Deposit</td><td></td><td>1,230.00</td></tr>
                                                            <tr><td></td><td colspan="2"><strong>Total Amount ForReimbursement</strong></td><td><strong>0.00</strong></td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <br>
                                                <p><strong>Please Immediately reimburse defecit amount of your Trust Fund Account</strong></p>
                                            </div>
                                        </div>
                                    </div>
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

                                    <table class="table">
                                        <tbody>
                                        <tr><td>Professional Fees</td><td><span>&#8369;</span> 1,000,000.00</td></tr>
                                        <tr><td>Percentage Tax on PF</td><td><span>&#8369;</span> 1,000,000.00</td></tr>
                                        <tr><td>Chargeables</td><td><span>&#8369;</span> 1,000,000.00</td></tr>
                                        <tr><td>Other</td><td><span>&#8369;</span> 1,000,000.00</td></tr>
                                        <tr><td>Total Amount Due</td><td><span>&#8369;</span> 1,000,000.00</td></tr>
                                        </tbody>
                                    </table>

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

        <div class="panel panel-default detail"><div class="panel-heading text-center"><strong>Detail of Service Report</strong></div><div class="panel-body"><div class="row"><div class="col-sm-12"><ul class="detail-head"><li><ul><li>No</li><li>Service Detail</li><li>Profl Fee</li></ul></li><li><ul><li>Chargeable Expenses</li><li>Description</li><li>Amount</li></ul></li></ul></div></div></div><div class="hr-line-solid"></div><div class="panel-body detail-item"><div class="row row-eq-height"><div class="col-sm-6"><table><thead><tr><th colspan="3">Jay walk [112233]</th></tr></thead><tbody><tr><td>Sep 6, 2018</td><td>Documentation (Page) Fee</td><td>15,000.00</td></tr><tr><td>SR-000006-09-2018</td><td colspan="2"> (asdfa) 12 page/s</td></tr></tbody><tbody><tr><td>Sep 6, 2018</td><td>Documentation (Page) Fee</td><td>15,000.00</td></tr><tr><td>SR-000007-09-2018</td><td colspan="2"> (asdfas) 12 page/s</td></tr></tbody></table><table><tfoot><tr><td>Total Fees</td><td>30,000.00</td></tr></tfoot></table></div><div class="col-sm-6"><table><thead><tr><th colspan="2">Chargeable Expenses </th></tr></thead></table><table><tfoot><tr><td>Total Chargeable Expenses</td><td>0.00</td></tr></tfoot></table></div></div></div><div class="hr-line-solid"></div><div class="panel-body detail-item"><div class="row row-eq-height"><div class="col-sm-6"><table><thead><tr><th colspan="3">General Retainer (000002-09-2018)</th></tr></thead><tbody><tr><td>Sep 4, 2018</td><td>Time Service Fee</td><td>250 min/s</td></tr><tr><td>SR-000003-09-2018</td><td colspan="2">Documentation (Time) (research) 250 min/s</td></tr></tbody><tbody><tr><td>Sep 5, 2018</td><td>Time Service Fee</td><td>700 min/s</td></tr><tr><td>SR-000004-09-2018</td><td colspan="2">Conference / Meeting: Advice (asdf) 700 min/s</td></tr></tbody></table><table><tfoot><tr><td>Total Minutes</td><td>950 min/s</td></tr></tfoot></table></div><div class="col-sm-6"><table><thead><tr><th colspan="2">Chargeable Expenses </th></tr></thead><tbody><tr><td><small>SR-000003-09-2018</small>: E-Load: Globe</td><td>30.00</td></tr></tbody><tbody><tr><td><small>SR-000004-09-2018</small>: Copying Charge: ID picture</td><td>120.00</td></tr></tbody></table><table><tfoot><tr><td>Total Chargeable Expenses</td><td>150.00</td></tr></tfoot></table></div></div></div><div class="hr-line-solid"></div><div class="panel-body detail-footer"><div class="row"><div class="col-sm-6"><table><thead><tr><th>PF (Special Retainers)</th><td>30,000.00</td></tr></thead><thead><tr><th colspan="2">PF (Excess General Retainer)</th></tr></thead><tbody><tr><td style="padding-left: 30px;">Time Service Fee <small>[ 600 min/s ]</small></td><td>350 min/s</td></tr></tbody><thead><tr><th>Paralegal Charges</th><td>00,000.00</td></tr></thead></table><table><tbody><tr><th colspan="3">Breakdown of Payments made (Professional Fees)</th></tr><tr><td>00/00/0000</td><td>0000000</td><td>00,000.00</td></tr></tbody></table><table><tbody><tr><th colspan="3">Breakdown of Payments made (Chargeable Expenses)</th></tr><tr><td>00/00/0000</td><td>0000000</td><td>00,000.00</td></tr></tbody></table></div></div></div></div>

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