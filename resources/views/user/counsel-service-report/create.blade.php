@extends('layouts.master')

@section('title', 'Service Report')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Create Service Report</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Create Service Report</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                {{--<button type="submit" class="btn btn-primary">Button</button>--}}
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-3">

                <div class="ibox float-e-margins" id="contract-box">
                    <div class="ibox-title">
                        <h5>Client's with active Contracts</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label>Client list</label>
                            <select name="" class="form-control client-select">
                                <option value="">Select client</option>
                                @foreach($clients as $client)
                                    <option value="{!! $client->id !!}">{!! $client->profile->firstname !!} {!! $client->profile->lastname !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="fee-info-box"></div>

            </div>
            <div class="col-sm-9" id="sr-box"></div>
        </div>
    </div>

    <div class="modal inmodal fade" id="modal" data-id="0" data-type="" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary modal-submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
    {!! Html::style('css/plugins/chosen/chosen.css') !!}
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    {!! Html::script('js/plugins/chosen/chosen.jquery.js') !!}
    {!! Html::script('js/numeral.js') !!}
    {!! Html::script('js/moment.js') !!}
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    <script>
        $(document).ready(function(){
            var conBox = $('#contract-box');
            var feeInfoBox = $('#fee-info-box');
            var srBox = $('#sr-box');
            var modal = $('#modal');



        });
    </script>
@endsection