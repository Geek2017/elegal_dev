@extends('layouts.master')

@section('title', 'Mockup Billing')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
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
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" id="create-billing" class="btn btn-primary" disabled=""><i class="fa fa-print"></i> Create Billing & PDF File</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-3">
                <div class="ibox float-e-margins setter">
                    <div class="ibox-title">
                        <h5>Client's with active Contracts</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group" id="month-input">
                            <label>Month / Year Select</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="month-select" class="form-control month-select" value="{!! \Carbon\Carbon::now()->format('F Y') !!}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9" id="billing-content">

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Billing Information</h5>
                    </div>
                    <div class="billing-mockup" id="billing-box">
                        <div class="ibox">
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-4 col-sm-push-8">
                                        <div class="professional-services"><h2>Professional Services</h2>
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td>Date</td>
                                                    <td class="bg-muted">Sep 20, 2018</td>
                                                </tr>
                                                <tr>
                                                    <td>Invoice</td>
                                                    <td>18-00001</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer ID</td>
                                                    <td>[ 00001 ]</td>
                                                </tr>
                                                <tr>
                                                    <td>Billing Period</td>
                                                    <td>September 01 - 30, 2018</td>
                                                </tr>
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
                                                <tr>
                                                    <th colspan="2">Bill to:</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Langosh, Ariel Lang</td>
                                                </tr>
                                                <tr>
                                                    <td>Company</td>
                                                </tr>
                                                <tr>
                                                    <td>83972 Kenyon Loop Suite 300
                                                        Elysetown, MA 85627
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>800-984-6491</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="panel panel-default summary">
                                            <div class="panel-heading text-center"><strong>Billing Summary</strong>
                                            </div>
                                            <div class="panel-body professional-fee"><h5><i>Items Subject to Tax [ PROFESSIONAL FEES ]</i></h5>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="title"><h5>Unsettled Bill <span class="pull-right">0.00</span></h5></div>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>Bill No.</th>
                                                                    <th class="text-right">Total</th>
                                                                    <th class="text-right">Amount Paid</th>
                                                                    <th class="text-right">Latest Balance</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>Bill #18-00001</td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td class="text-right">0.00</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="title"><h5>Current Charges <span class="pull-right">15,000.00</span>
                                                            </h5></div>
                                                        <div class="table-responsive">
                                                            <table class="table-summary">
                                                                <tbody>
                                                                <tr>
                                                                    <td colspan="3">Special Retainers</td>
                                                                    <td>10,000.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">Fixed General Retainer</td>
                                                                    <td>5,000.00</td>
                                                                </tr>
                                                                <tr class="bill-bottom">
                                                                    <td colspan="3">Excess General Retainers</td>
                                                                    <td>0.00</td>
                                                                </tr>
                                                                </tbody>
                                                                <tbody>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2"><strong>Total Current
                                                                            Charges</strong></td>
                                                                    <td><strong>15,000.00</strong></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="title"><h5>Subtotal <span class="pull-right">15,000.00</span>
                                                            </h5></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body chargeable-fee"><h5><i>Items Not Subject Tax [ CHARGEABLE EXPENSES ]</i></h5>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="title"><h5>Unsettled Operational Fund <span class="pull-right">0.00</span></h5></div>
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>Bill No.</th>
                                                                    <th class="text-right">Total</th>
                                                                    <th class="text-right">Amount Paid</th>
                                                                    <th class="text-right">Latest Balance</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>Bill #18-00001</td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td class="text-right">0.00</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="title"><h5>Current Charges <span class="pull-right">0.00</span> </h5></div>
                                                        <div class="table-responsive">
                                                            <table class="table-summary">
                                                                <tbody>
                                                                <tr>
                                                                    <td><strong>Add: </strong></td>
                                                                    <td colspan="2">Current Charges</td>
                                                                    <td>1,231.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Less:</td>
                                                                    <td>Deposit</td>
                                                                    <td></td>
                                                                    <td>10,000.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2"><strong>Total Amount For
                                                                            Reimbursement</strong></td>
                                                                    <td><strong>0.00</strong></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <br>
                                                        <p><strong>Please Immediately reimburse defecit amount of your Trust Fund Account</strong></p></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="panel panel-default summary">
                                            <div class="panel-heading"><strong>Other Comments</strong></div>
                                            <div class="p-xs"><h4 style="color: rgb(103, 106, 108); margin-top: 5px;">
                                                    Note:</h4>
                                                <ol style="color: rgb(103, 106, 108);">
                                                    <li>Bills are due and payable upon receipt.</li>
                                                    <li>Check payment should be payable to ATTY. PETER LEO M. RALLA.
                                                    </li>
                                                    <li>Payment made after billing period is not included in this
                                                        statement.
                                                    </li>
                                                    <li>The Trust Fund appearing under item II, represents the deposits
                                                        made to the firm to cover all chargeable expenses.
                                                    </li>
                                                    <li>Kindly reimburse immediately the deficit amount reflected under
                                                        the Trust Fund Balance and replenish your deposit account with
                                                        the firm to cover future expenses. Thank you
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="total-footer">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>Professional Fees</td>
                                                    <td><span>₱</span> 15,000.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Chargeables</td>
                                                    <td><span>₱</span> 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Amount Due</td>
                                                    <td><span>₱</span> 15,000.00</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <table>
                                                <thead>
                                                <tr>
                                                    <td>Make all checks payable to</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Atty. Peter Leo M. Ralla</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="text-center"><p>If you have any questions about this invoice, please contact.</p>
                                            <p>[ Name, Phone #, E-mail ]</p>
                                            <h3><i>Thank You For Your Business!</i></h3></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="panel panel-default detail">
                            <div class="panel-heading text-center"><strong>Detail of Service Report</strong></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="detail-head">
                                            <li>
                                                <ul>
                                                    <li>No</li>
                                                    <li>Service Detail</li>
                                                    <li>Profl Fee</li>
                                                </ul>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li>Chargeable Expenses</li>
                                                    <li>Description</li>
                                                    <li>Amount</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-solid"></div>

                            <div class="panel-body detail-item">

                                <div class="row row-eq-height">
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="3">Jay Walking [1233112]</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="2">Chargeable Expenses</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="row row-eq-height">
                                    <div class="col-sm-6">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td>Sep 18, 2018</td>
                                                <td>Appearance Fee</td>
                                                <td>5,000.00</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <small>SR-000001-09-2018</small>: Communications Expenses: asdf
                                                </td>
                                                <td>1,231.00</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row row-eq-height">
                                    <div class="col-sm-6">
                                        <table>
                                            <tfoot>
                                            <tr>
                                                <td>Total Fees</td>
                                                <td>10,000.00</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table>
                                            <tfoot>
                                            <tr>
                                                <td>Total Chargeable Expenses</td>
                                                <td>1,231.00</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="hr-line-solid"></div>

                            <div class="panel-body detail-item">
                                <div class="row row-eq-height">
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="3">Jay Walking [1233112]</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Sep 18, 2018</td>
                                                <td>Appearance Fee</td>
                                                <td>5,000.00</td>
                                            </tr>
                                            <tr>
                                                <td>SR-000001-09-2018</td>
                                                <td colspan="2">Arraignment (asdf)</td>
                                            </tr>
                                            </tbody>
                                            <tbody>
                                            <tr>
                                                <td>Sep 20, 2018</td>
                                                <td>Appearance Fee</td>
                                                <td>5,000.00</td>
                                            </tr>
                                            <tr>
                                                <td>SR-000002-09-2018</td>
                                                <td colspan="2">Hearing (asdfas)</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table>
                                            <tfoot>
                                            <tr>
                                                <td>Total Fees</td>
                                                <td>10,000.00</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="2">Chargeable Expenses</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <small>SR-000001-09-2018</small>
                                                    : Communications Expenses: asdf
                                                </td>
                                                <td>1,231.00</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table>
                                            <tfoot>
                                            <tr>
                                                <td>Total Chargeable Expenses</td>
                                                <td>1,231.00</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-solid"></div>

                            <div class="panel-body detail-item">
                                <div class="row row-eq-height">
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="3">General Retainer (000004-09-2018)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Sep 20, 2018</td>
                                                <td>Time Service Fee</td>
                                                <td>200 min/s</td>
                                            </tr>
                                            <tr>
                                                <td>SR-000003-09-2018</td>
                                                <td colspan="2">Study: Research (asdfas) 200 min/s</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table>
                                            <tfoot>
                                            <tr>
                                                <td>Total Minutes</td>
                                                <td>200 min/s</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="2">Chargeable Expenses</th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <table>
                                            <tfoot>
                                            <tr>
                                                <td>Total Chargeable Expenses</td>
                                                <td>0.00</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="hr-line-solid"></div>


                            <div class="panel-body detail-footer">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th>PF (Special Retainers)</th>
                                                <td>10,000.00</td>
                                            </tr>
                                            </thead>
                                            <thead>
                                            <tr>
                                                <th colspan="2">PF (Excess General Retainer)</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/plugins/summernote/summernote.min.js') !!}
@endsection