@extends('layouts.master')

@section('title', 'Cash Receipt Payments Reports')

@section('styles')
    {!! Html::style('css/plugins/datapicker/datepicker3.css') !!}
    {!! Html::style('css/plugins/select2/select2.min.css') !!}
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

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Cash Receipt Payment Report</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="#">Reports</a>
                </li>
                <li class="active">
                    <strong>Cash Receipt Payment Report</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>Counsel Service Report</h2>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-8">
                                <h3 class="date-type-title">
                                    <label>
                                        <input type="radio" name="date_type" class="date-type" value="dateRange"> For the Period
                                    </label>
                                </h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Date Star</label> 
                                            <input type="text" class="form-control date-picker" id="startDate" value="{{$now}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Date End</label> 
                                            <input type="text" class="form-control date-picker" id="endDate" value="{{$now}}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h3 class="date-type-title">
                                    <label>
                                        <input type="radio" name="date_type" class="date-type" value="asOf" checked> As Of
                                    </label>
                                </h3>
                                <div class="form-group">
                                    <label>&nbsp;</label> 
                                    <input type="text" class="form-control date-picker" id="asOf" value="{{$now}}">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="date-type-title">
                                    <label>
                                        Counsel
                                    </label>
                                </h3>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <!-- <label>Counsel</label>  -->
                                            <select class="form-control select2" id="counsels" name="counsel_id">
                                                <option value="">Select Counsel</option>
                                                @foreach ($counsels as $c)
                                                    <option value="{{$c->id}}">{{$c->profile->full_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-lg pull-right" id="show-report">Show</button>
                            </div>
                        </div>
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
            <h4 class="modal-title">Counsel Service Report</h4>
          </div>
          <div class="modal-body">
            <embed id="pdf-embed" width="100%" height="100%">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

@endsection

@section('scripts')
    {!! Html::script('js/plugins/datapicker/bootstrap-datepicker.js') !!}
    {!! Html::script('js/plugins/select2/select2.full.min.js') !!}

    <script>
        $(document).ready(function(){
          
            if($('#counsels').val()==''){
              $('#show-report').attr("disabled", true);
            }

            $('#counsels').change(function(){ 
                if($('#counsels').val()==''){
                    $('#show-report').attr("disabled", true);
                    // console.log($('#counsels').val())
                }else{
                    // console.log($('#counsels').val())
                    $('#show-report').attr("disabled", false);
                }
            });

            var $datePickers = $('.date-picker'),
                $dateType = $('.date-type'),
                $startDate = $('#startDate'),
                $endDate = $('#endDate'),
                $asOfDate = $('#asOf'),
                $counsels = $("#counsels")
                ;
            
            var $disabledDateRange = function (dateRange, AsOf){
                $startDate.attr('disabled', dateRange);
                $endDate.attr('disabled', dateRange);
                $asOfDate.attr('disabled', AsOf);
            };

            var $formatDate = function (date) {
                var d = new Date(date);
                
                var date = d.getDate();
                var month = d.getMonth();
                var $day = (date >= 10) ? date : ('0' + date),
                    $month = ( (month + 1) >=10 ) ? (month + 1) : '0' + (month + 1);

                return d.getFullYear() + '-' + $month + '-' + $day;
            };

            $datePickers.datepicker();
            $counsels.select2();

            $dateType.on('change', function(){
                var type = $(this).val();
                
                if (type == 'dateRange') {
                    $disabledDateRange(false, true);
                } else {
                    $disabledDateRange(true, false);
                }
            });

            $('#view-pdf').on('hidden.bs.modal', function () {
                $("#pdf-embed").attr('src', '');
            });


            $( "#show-report" ).on('click', function( /*event */) {
                var $url = "{{ route('reports.show-counsel-report-service') }}";
                console.log($startDate.val(), $endDate.val(), $asOfDate.val());

                $url += "?type=" + $dateType.val();
                $url += "&start_date=" + $formatDate($startDate.val());
                $url += "&end_date=" + $formatDate($endDate.val());
                $url += "&as_of_date=" + $formatDate($asOfDate.val());
                $url += "&counsel_id=" + $counsels.val();

                $('#view-pdf').modal('show').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(".modal-content").height());
                });

                $("#pdf-embed").attr('src', $url);
            });
        });
    </script>
@endsection