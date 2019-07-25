@extends('layouts.master')

@section('title', 'Paralegal Assignment Sheet')

@section('styles')
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
    </style>
@endsection

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Paralegal Assignment Sheet</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Form</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><small>Paralegal Sheet Information</small></h5>
                    </div>
                    <div class="ibox-content">
                        {{Form::open(array('method' => 'GET', 'id'=>'pas-form', 'class' => 'form-horizontal'))}}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-lg-12">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">Assigning Counsel:</span>
                                        <input name="assigning_counsel" type="text" class="form-control" id="assigning-counsel" required>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">FAS# (Start):</span>
                                        <input name="fas_start" type="number" class="form-control" id="fas-start" required>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">FAS# (Code):</span>
                                        <input name="fas_code" type="text" class="form-control" id="fas-code" required>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="input-group m-b"><span class="input-group-addon bg-muted">FAS# (No. Pages):</span>
                                        <input name="fas_pages" type="number" class="form-control" id="fas-pages" required>
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        {{Form::button('Print', array('class'=>'btn btn-lg btn-warning pull-right', 'id'=>'print'))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
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
            <h4 class="modal-title">Peralegal Assignment Sheet Viewer</h4>
          </div>
          <div class="modal-body">
            <!-- <div style="min-height: 545px; overflow: scroll;" id="pdf-container"> -->
                <!-- <div id="pdfViewer" width="100%" height="100%"> -->
                    <embed id="pdf-embed" width="100%" height="100%">
                <!-- </div> -->
            <!-- </div> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            $('#view-pdf').on('hidden.bs.modal', function () {
                $("#pdf-embed").attr('src', '');
            });


            $( "#print" ).on('click', function( /*event */) {
                var $url = "{{ route('pas.print') }}";

                $('#view-pdf').modal('show').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(".modal-content").height());
                });

                $url += "?assigning_counsel=" + $("#assigning-counsel").val();
                $url += "&fas_start=" + $("#fas-start").val();
                $url += "&fas_code=" + $("#fas-code").val();
                $url += "&fas_pages=" + $("#fas-pages").val();

                $("#pdf-embed").attr('src', $url);


                // event.preventDefault();
            });
        });
    </script>
@endsection