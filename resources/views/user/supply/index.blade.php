@extends('layouts.master')

@section('title', 'List of Supplies')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Supplies</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="#">Supplies</a>
                </li>
                <li class="active">
                    <strong>List of Supplies</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('supply.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i>Add Supply</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title text-right">
                       <button id="print-pdf" class="btn btn-primary">
                           <i class="fa fa-print"></i>
                           Print
                       </button>
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
                                            <th>Category</th>
                                            <th>Name</th>
                                            <!-- <th>Begin</th> -->
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Audit</th>
                                            <th>Short</th>
                                            <!-- <th></th> -->
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


    <!-- Modal -->
    <div id="view-pdf" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Office Supplies</h4>
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


@section('styles')
{!! Html::style('css/dataTables.min.css') !!}
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

@section('scripts')
    {!! Html::script('js/dataTables.min.js') !!}
    <script>
        $(document).ready(function(){
            var groupColumn = 0;
            var table = $('#cash-receipt-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                ajax: '{!! route('supplies') !!}',
                columnDefs: [
                    { className: "text-right", "targets": [ 2, 3, 4, 5 ] },
                    { className: "text-center", "targets": [ 6 ] },
                    { "visible": false, "targets": groupColumn }
                ],
                columns: [
                    { data: 'category', name: 'category' },
                    { data: 'name', name: 'name' },
                    { data: 'in', name: 'in' },
                    { data: 'out', name: 'out' },
                    { data: 'balance', name: 'balance' },
                    { data: 'short', name: 'short' },
                    { data: 'action', name: 'action' }
                ],
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
         
                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="6"><strong>'+group+'</strong></td></tr>'
                            );
         
                            last = group;
                        }
                    } );
                }
            });


            $("#print-pdf").on('click', function(){
                $('#view-pdf').modal('show').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(".modal-content").height());
                });

                $("#pdf-embed").attr('src', "{{route('supply.print')}}");
            });
        });
    </script>
@endsection