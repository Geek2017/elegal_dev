@extends('layouts.master')

@section('title', 'Client Trust Fund - Ledger')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Client Trust Fund - Ledger</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Client Trust Fund - Ledger</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Client Trust Fund</h2>
                    </div>
                    <div class="ibox-content">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Client:</label>
                                        <div class="col-lg-9">
                                            <select class="form-control select2" id="client-select" name="client_id">
                                                <option value="">Select Client</option>
                                                @foreach ($clients as $c)
                                                    <option value="{{$c->id}}">{{$c->profile->full_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Total Funds:</label>
                                        <div class="col-lg-9">
                                            <input name="total_funds" type="text" class="form-control text-right" id="total-funds" value="0.00" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Current Balance:</label>
                                        <div class="col-lg-9">
                                            <input name="current_balance" type="text" class="form-control text-right" id="current-balance" value="0.00" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-md btn-info" id="view-all-clients">
                                        <i class="fa fa-users"></i>
                                        All Clients
                                    </button>
                                    
                                    <button type="button" class="btn btn-md btn-primary pull-right" id="view">
                                        <i class="fa fa-eye"></i>
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="view-pdf" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Member's Trust Fund - Ledger</h4>
          </div>
          <div class="modal-body">
                <embed id="pdf-embed" width="100%" height="100%">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>

@endsection


@section('styles')
    {!! Html::style('css/plugins/select2/select2.min.css') !!}
    {!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}
    <style type="text/css">
        .modal {
          margin: 0 auto; 
        }
        .modal-dialog,
        .modal-content {
            /* 80% of window height */
            height: 95%;
            width: 98%;
        }

        .modal-body {
            /* 100% = dialog height, 120px = header + footer */
            height: calc(100% - 120px);
            padding: 0!important;
            /*width: 100%*/
            /*overflow-y: scroll;*/
        }
        h3.date-type-title {
            padding: 5px 0 5px 5px;
            background-color: #4d4dff;
            color: white;
        }
    </style>
@endsection

@section('scripts')
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
    <script>
        $(document).ready(function(){
            var $clientSelect = $('#client-select')
                $totalBalance = $('#total-funds'),
                $currentBalance = $('#current-balance'),
                $viewButton = $("#view"),
                $viewAllClients = $("#view-all-clients"),
                $selectedClientId = null;

            $clientSelect.select2().on('select2:select', function (e) {
                var data = e.params.data;
                
                $selectedClientId = data.id;
                $.get("{!! route('trust-fund.members') !!}?client_id=" + data.id , function(data, status) {
                    $totalBalance.val(data.total_deposit);
                    $currentBalance.val(data.latest_balance);
                });
            });

            var $showModalReport = function (url) {
                $('#view-pdf').modal('show').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(".modal-content").height());
                });

                $("#pdf-embed").attr('src', url);
            }

            $viewButton.on('click', function() {
                if (!$selectedClientId) {
                    swal({
                        title: "Member's Trust Fund - Ledger",
                        text: 'Please select a client to view.',
                        type: 'warning',
                    });

                    return;
                }

                $showModalReport("{{route('trust-fund.print')}}?client_id="+$selectedClientId);
            });

            $viewAllClients.on('click', function() {
                $showModalReport("{{route('trust-fund.print-all')}}");
            });

            $(document).on('focus', '.select2', function (e) {
              if (e.originalEvent) {
                $(this).siblings('select').select2('open');    
              } 
            });
        });
    </script>
@endsection