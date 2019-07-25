@extends('layouts.master')

@section('title', 'Client Profile')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Client Profile</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('client.index') !!}">Client List</a>
                </li>
                <li class="active">
                    <strong>Client Profile</strong>
                </li>
            </ol>
        </div>
        @if(auth()->user()->can('edit-client'))
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('client.edit', array('client' => $client->id)) !!}" class="btn btn-white"><i class="fa fa-pencil"></i> Edit</a>
            </div>
        </div>
        @endif
    </div>

    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-md-6">
                <div class="profile-image">
                    <img src="{!! ($client->profile->image != '') ? '/uploads/image/'.$client->profile->image : '/img/placeholder.jpg' !!}" class="img-circle circle-border m-b-md" alt="profile">
                </div>
                <div class="profile-info">
                    <div class="">
                        <div>
                            <h2 class="no-margins">
                                {!! $client->profile->firstname !!} {!! $client->profile->middlename !!} {!! $client->profile->lastname !!}
                            </h2>
                            <h4>{!! $client->count !!}</h4>
                            <small>
                                {!! $client->business->address->description !!}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="col-md-3">--}}
                {{--<table class="table small m-b-xs">--}}
                    {{--<tbody>--}}
                    {{--<tr>--}}
                        {{--<td><strong>142</strong> Contracts</td>--}}
                        {{--<td><strong>22</strong> Cases</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td><strong>61</strong> Comments</td>--}}
                        {{--<td><strong>54</strong> Articles</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td><strong>154</strong> Tags</td>--}}
                        {{--<td><strong>32</strong> Friends</td>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
            {{--<div class="col-md-3">--}}
                {{--<small>Trust Fund</small>--}}
                {{--<h2 class="no-margins">206 480</h2>--}}
                {{--<div id="sparkline1"></div>--}}
            {{--</div>--}}
        </div>
        
        <div class="hr-line-dashed"></div>

        <div class="row">

            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title bg-muted">
                                <h5>Trust Fund Information</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon bg-muted">Balance:</span>
                                        <label class="form-control text-success text-right" id="fund-total" data-total="0">0.00</label>
                                        @if(auth()->user()->can('add-client-trust-fund-client'))
                                        <span class="input-group-addon bg-muted span-btn fund-add-btn"><i class="fa fa-plus"></i></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <h3>Record</h3>
                                <table class="table table-bordered" id="trust-fund-record-table">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Amount</th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title bg-muted">
                                <h5 class="pull-left">Contract Information</h5>
                                @if(auth()->user()->can('add-contract'))
                                <a href="{!! route('create-contract', array('id' => $client->id)) !!}" class="btn btn-xs btn-success pull-right">Create</a>
                                @endif
                            </div>
                            <div class="ibox-content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="col-sm-4">--}}
                {{--<div class="ibox float-e-margins">--}}
                    {{--<div class="ibox-title bg-muted">--}}
                        {{--<h5 class="pull-left">Billing Information</h5>--}}
                    {{--</div>--}}
                    {{--<div class="ibox-content">--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>

    </div>



    <div class="modal inmodal fade" id="fund-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Trust Fund</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon bg-muted">Amount:</span>
                            <input name="amount" type="text" class="form-control numonly required" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="textarea-group">
                            <span class="textarea-group-addon bg-muted">Description: <small>[optional]</small></span>
                            <textarea name="description" id="" class="form-control resize-vertical"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="fund-store-btn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}

    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/jquery.masknumber.js') !!}
    <script>
        $(document).ready(function(){
            var fundModal = $('#fund-modal');
            var client_id = '{!! $client->id !!}';
            trustFundRecord();

            function trustFundRecord(){
                console.log(client_id);
                var table = $('#trust-fund-record-table');
                $.get('{!! route('trust-fund-record') !!}',{
                    id: client_id
                },function(data){
                    console.log(data);
//                    $('#fund-total').text(data[1].balance);
                    $('#fund-total').text(numeral(data[1].balance).format('0,0.00'));
                    if(data[0].length > 0){
                        table.find('tbody').empty();
                        var action;
                        var amount;
                        for(var a = 0; a < data[0].length; a++){
                            if(data[0][a].billing_id === null){
                                action = 'Deposit';
                                amount = data[0][a].deposit;
                            }else{
                                action = 'Deducted';
                                amount = data[0][a].credit;
                            }
                            table.find('tbody').append('' +
                                '<tr>' +
                                    '<td>'+ data[0][a].created_at +'</td>' +
                                    '<td>'+ action +'</td>' +
                                    '<td class="text-right">'+ numeral(amount).format('0,0.00') +'</td>' +
                                    '<td class="text-right">'+ numeral(data[0][a].balance).format('0,0.00') +'</td>' +
                                '</tr>' +
                            '');
                        }

                    }else{

                    }
                });
            }

            $(document).on('click','.fund-add-btn',function(){
                fundModal.modal({backdrop: 'static', keyboard: false});
                $('.numonly').maskNumber({decimal: '.', thousands: ','});
            });

            $(document).on('click','#fund-store-btn',function(){
                var amount = parseInt(fundModal.find('input[name="amount"]').val()||0);
                if(amount < 1){
                    fundModal.find('input[name="amount"]').closest('.form-group').addClass('has-error');
                    toastr.error('Required!','Invalid amount!');
                }else{

                    var amount = fundModal.find('input[name="amount"]').val()||'0';

                    $.post('{!! route('store-fund') !!}',{
                        _token: '{!! csrf_token() !!}',
                        deposit: parseFloat(amount.replace(/,/g, '')),
                        desc: fundModal.find('textarea[name="description"]').val(),
                        id: client_id
                    },function(data){
                        if(data != 0){
                            toastr.success('Successful!','Trust fund added!');
                            trustFundRecord();
                            fundModal.modal('toggle');
                            fundModal.find('input[name="amount"]').val('');
                        }else{
                            toastr.error('Failed!','Cannot save data, repeat process!');
                        }
                    });
                }
            });

        });
    </script>
@endsection