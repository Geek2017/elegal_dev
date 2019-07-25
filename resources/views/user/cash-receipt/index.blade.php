@extends('layouts.master')

@section('title', 'List of Payments')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Cash Receipts (List of Payment)</h2>
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
                    <strong>List of Cash Receipt Payments</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('cash-receipt.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i>Create Payment</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
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
                                

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="cash-receipt-table">
                                        <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Payment Date</th>
                                            <th>OR. No.</th>
                                            <th>Amount Due</th>
                                            <th>Amount Paid</th>
                                            <th>Balance (Unpaid Due)</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                    </table>
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
@endsection

@section('scripts')
    {!! Html::script('js/dataTables.min.js') !!}
    <script>
        $(document).ready(function(){
            var table = $('#cash-receipt-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                ajax: '{!! route('cash-receipt-list') !!}',
                columnDefs: [
                    { className: "text-right", "targets": [ 1,2,3,4,5 ] }
                ],
                columns: [
                    { data: 'client_full_name', name: 'client_full_name' },
                    { data: 'payment_date', name: 'payment_date' },
                    { data: 'cash_receipt_no', name: 'cash_receipt_no' },
                    { data: 'amount_due', name: 'amount_due' },
                    { data: 'amount_paid', name: 'amount_paid' },
                    { data: 'balance', name: 'balance' },
                    { data: 'action', name: 'action' }
                ]
            });


            $(document).on('click', "a.delete-ars", function(){
                var data = $(this).data();
                
                var r = confirm("Delete Activity Report Sheet?");
                if (r == true) {
                    // txt = "You pressed OK!";
                    window.location.href = data.url;
                }

            });
        });
    </script>
@endsection