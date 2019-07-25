@extends('layouts.master')

@section('title', 'Clients')

@section('styles')
{!! Html::style('css/plugins/toastr/toastr.min.css') !!}
{!! Html::style('css/plugins/dataTables/datatables.min.css') !!}
{!! Html::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') !!}


@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="rmlt col-lg-6">
        <h2>Client's List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li class="active">
                <a href="/"><strong>Client List</strong></a>
            </li>
        </ol>
    </div>
    <div class="col-lg-4 pull-right">
        <div class="title-action">
            <a href="{!! route('clients.create') !!}" type="button" class="btn btn-w-m btn-primary">Add Client</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content">

    @include('client.list')

    @include('client.modal')

</div>

@endsection

@section('scripts')

{!! Html::script('js/plugins/dataTables/datatables.min.js') !!}
{!! Html::script('js/plugins/toastr/toastr.min.js') !!}
{{-- {!! Html::script('js/clientScript.js') !!} --}}
{!! Html::script('js/google.js') !!}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVS0CN-Ez85eOLGEh7d113v9LE9ZzDses&libraries=places&callback=initAutocomplete"
async defer></script>

<script>
tblClient = $('#client').dataTable( {
    "autoWidth": false,
    "data": {!! $client->get() !!},
    "columnDefs": [{
        
        "targets": 0,
        "data": null,
        "render": function (row) {
            return row.fname + " " + row.lname;} },{

        "targets": 1,
        "data": null,
        "render":function(row){
            return `${row.email}`
        }},{

        "targets": 2,
        "data": null,
        "className": "text-right",
        "render": function (row) {
            return  `<div class="btn-group">
                        <a class="btn-success btn btn-xs" href="create-contract/${row.id}">Contract</a>
                        <a class="btn-primary btn btn-xs" href="/clients/${row.id}">View</a></a>
                        <button class="btn-danger btn btn-xs" id=${row.id} onclick="destroy(this.id)">Delete</button>
                    </div>`;}}
        
    ]
} );
</script>
@endsection