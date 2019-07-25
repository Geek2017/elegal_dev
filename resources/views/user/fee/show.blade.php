@extends('layouts.master')

@section('title', 'Blank|Page')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Fee Detail</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Fee Detail</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            {{--<div class="title-action">--}}
                {{--<button type="submit" class="btn btn-primary">Edit</button>--}}
            {{--</div>--}}
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-3">
                <div class="ibox float-e-margins fee-detail-box">
                    <div class="ibox-title">
                        <h5>Fee Details</h5>
                        <div class="ibox-tools">
                            <button type="button" data-id="{!! $fee->id !!}" class="action btn btn-xs btn-primary" data-type="fee-detail" data-action="edit"><i class="fa fa-pencil"></i> Edit</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <address>
                            <strong>{!! $fee->display_name !!}</strong><br>
                            <small class="text-success">Name</small>
                        </address>
                        <address>
                            <strong>{!! $fee->category->display_name !!}</strong><br>
                            <small class="text-success">Category</small>
                        </address>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Add Description</h5>
                    </div>
                    <div class="ibox-content" id="desc-box">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="desc-name" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" name="desc-description">
                        </div>
                        <div class="form-group">
                            <label>Default Amount</label>
                            <input type="text" class="form-control numonly" name="desc-amount" value="0">
                        </div>
                        <div class="text-left">
                            <button type="button" class="action btn btn-sm btn-success" data-action="add" data-type="fee-description">Store</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Description List</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table">
                                <thead>
                                <tr>
                                    <th>Description name</th>
                                    <th>Description info</th>
                                    <th>Default Amount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal inmodal fade" id="modal" data-id="0" data-action="add" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Edit Description</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" data-action="update" data-type="" class="action btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')
{{--    {!! Html::style('css/dataTables.min.css') !!}--}}
    {!! Html::style('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css') !!}
    {!! Html::style('css/plugins/toastr/toastr.min.css') !!}
@endsection

@section('scripts')
    {{--{!! Html::script('js/dataTables.min.js') !!}--}}
    {!! Html::script('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js') !!}
    {!! Html::script('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js') !!}
    {!! Html::script('https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js') !!}
    {!! Html::script('js/plugins/toastr/toastr.min.js') !!}
    <script>
        $(document).ready(function(){
            var desc = $('#desc-box');
            var modal = $('#modal');
            var fee_id = '{!! $fee->id !!}';
//            delay(function(){
//
//            }, 5000 );
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: '{!! route('fee-desc', array('id'=>$fee->id)) !!}',
                columnDefs: [
                    { className: "text-right", "targets": [ 2, 3 ] }
                ],
                columns: [
                    { data: 'description', name: 'description' },
                    { data: 'type', name: 'type' },
                    { data: 'amount', name: 'amount' },
                    { data: 'action', name: 'action' }
                ]
            });

            $(document).on('click','.action',function(){
                var action = $(this).data('action');
                var type = $(this).data('type');
                var id = $(this).data('id');
                console.log('click action: '+ action);
                console.log('click type: '+ type);
                console.log('click id: '+ id);
                switch (type){
                    case 'fee-detail':
                        switch(action) {
                            case 'edit':
                                modal.find('.modal-body').empty().append(feeDetailForm);
                                $.get('{!! route('fee-detail-find') !!}',{
                                    id: id
                                },function(data){
//                                    console.log(data);
                                    if(data.length != 0){
                                        var categoryOption = new Array();
                                        for(var a = 0; a < data[1].length; a++){
                                            var selected = (data[0].category_id === data[1][a].id) ? 'selected': '';
                                            categoryOption.push('<option value="'+ data[1][a].id +'" '+ selected +'>'+ data[1][a].display_name +'</option>');
                                        }
                                        modal.find('select[name="detail-category"]').append(categoryOption.join(''));
                                        modal.data('id',data[0].id);
                                        modal.find('input[name="detail-name"]').val(data[0].display_name);
                                        modal.find('.form-group').removeClass('has-error');
                                        modal.find('.action').data('type','fee-detail');
                                        modal.modal({backdrop: 'static', keyboard: false});
                                    }
                                });
                                break;
                            case 'update':
                                console.log('update id: '+ modal.data('id'));
                                console.log('update name: '+ modal.find('input[name="detail-name"]').val());
                                console.log('update category: '+ modal.find('select[name="detail-category"]').val());
                                if(detailValidator() < 1){
                                    $.post('{!! route('fee-update') !!}',{
                                        _token: '{!! csrf_token() !!}',
                                        id: modal.data('id'),
                                        name: modal.find('input[name="detail-name"]').val(),
                                        category: modal.find('select[name="detail-category"]').val()
                                    },function(data){
                                        console.log(data);
                                        $('.fee-detail-box').find('.ibox-content').empty().append('' +
                                            '<address>' +
                                            '<strong>'+ data.display_name +'</strong><br>' +
                                            '<small class="text-success">Name</small>' +
                                            '</address>' +
                                            '<address>' +
                                            '<strong>'+ data.category.display_name +'</strong><br>' +
                                            '<small class="text-success">Category</small>' +
                                            '</address>' +
                                            '');
                                        modal.modal('toggle');
                                        toastr.success('Successful!','Fee Detail updated successfully!');
                                    });
                                }

                                break;
                        }
                        break;
                    case 'fee-description':
                        switch(action){
                            case 'add':
                                if(descValidator() < 1){
                                    $.post('{!! route('fee-desc-store') !!}',{
                                        _token: '{!! csrf_token() !!}',
                                        id: fee_id,
                                        name: desc.find('input[name="desc-name"]').val(),
                                        desc: desc.find('input[name="desc-description"]').val(),
                                        amount: desc.find('input[name="desc-amount"]').val()
                                    },function(data){
                                        if(data.length != 0){
                                            desc.find('input').val('');
                                            table.ajax.reload();
                                            toastr.success('Successful!','Fee description added successfully!');
                                        }
                                    });
                                }
                                break;
                            case 'edit':
                                $.get('{!! route('fee-find') !!}',{
                                    id: id
                                },function(data){
                                    if(data.length != 0){
                                        modal.find('.modal-body').empty().append(feeDescriptionForm);
                                        modal.data('id',data.id);
                                        modal.find('input[name="desc-name"]').val(data.display_name);
                                        modal.find('input[name="desc-description"]').val(data.description);
                                        modal.find('input[name="desc-amount"]').val(data.default_amount);
                                        modal.find('.form-group').removeClass('has-error');
                                        modal.find('.action').data('type','fee-description');
                                        modal.modal({backdrop: 'static', keyboard: false});
                                    }
                                });
                                break;
                            case 'update':
                                desc = modal;
                                if(descValidator() < 1){
                                    $.post('{!! route('fee-desc-update') !!}',{
                                        _token: '{!! csrf_token() !!}',
                                        id: desc.data('id'),
                                        name: desc.find('input[name="desc-name"]').val(),
                                        desc: desc.find('input[name="desc-description"]').val(),
                                        amount: desc.find('input[name="desc-amount"]').val()
                                    },function(data){
                                        if(data.length != 0){
                                            desc.find('input').val('');
                                            table.ajax.reload();
                                            modal.modal('toggle');
                                            toastr.success('Successful!','Fee description updated successfully!');
                                        }
                                    });
                                }
                                break;
                            case 'delete':
                                $.get('{!! route('fee-desc-delete') !!}',{
                                    id: id
                                },function(data){
                                    if(data == 1){
                                        table.ajax.reload();
                                        toastr.success('Successful!','Fee description deleted successfully!');
                                    }
                                });
                        }
                        break;
                }

            });

            $(document).on('click','#desc-btn',function(){
                if(descValidator() < 1){
                    $.post('{!! route('fee-desc-store') !!}',{
                        _token: '{!! csrf_token() !!}',
                        id: fee_id,
                        name: desc.find('input[name="desc-name"]').val(),
                        desc: desc.find('input[name="desc-description"]').val(),
                        amount: desc.find('input[name="desc-amount"]').val()
                    },function(data){
                        if(data.length != 0){
                            desc.find('input').val('');
                            table.ajax.reload();
                            toastr.success('Successful!','Fee description added successfully!');
                        }
                    });
                }
            });

            var feeDetailForm = function(){
                var data = '' +
                    '<div class="form-group">' +
                    '<label>Name</label>' +
                    '<input type="text" class="form-control required" name="detail-name">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Category</label>' +
                    '<select name="detail-category" class="form-control"></select>' +
                    '</div>' +
                    '';
                return data;
            };

            var feeDescriptionForm = function(){
                var data = '' +
                    '<div class="form-group">' +
                        '<label>Description name</label>' +
                        '<input type="text" class="form-control required" name="desc-name">' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label>Description info</label>' +
                        '<input type="text" class="form-control" name="desc-description">' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label>Default Amount</label>' +
                        '<input type="text" class="form-control numonly" name="desc-amount">' +
                    '</div>' +
                '';
                return data;
            };

            var descValidator = function(){
                desc.find('.form-group').removeClass('has-error');
                var count = 0;
                desc.find('.required').each(function(){
                    if(!$(this).val()){
                        count += 1;
                        $(this).closest('.form-group').addClass('has-error');
                    }
                });
                desc.find('.numonly').each(function(){
                    if(!$(this).val() || !$(this).val() < 0){
                        count += 1;
                        $(this).closest('.form-group').addClass('has-error');
                    }
                });
                if(count > 0){
                    toastr.error('Required!','Invalid inputs!');
                }
                if(count > 1){
                    setTimeout(function(){
                        $('.form-group').removeClass('has-error');
                    }, 6000);
                }
                return count;
            };

            var detailValidator = function(){
                modal.find('.form-group').removeClass('has-error');
                var count = 0;
                modal.find('.required').each(function(){
                    if(!$(this).val()){
                        count += 1;
                        $(this).closest('.form-group').addClass('has-error');
                    }
                });
                modal.find('.numonly').each(function(){
                    if(!$(this).val() || !$(this).val() < 0){
                        count += 1;
                        $(this).closest('.form-group').addClass('has-error');
                    }
                });
                if(count > 0){
                    toastr.error('Required!','Invalid inputs!');
                }
                if(count > 1){
                    setTimeout(function(){
                        $('.form-group').removeClass('has-error');
                    }, 6000);
                }
                return count;
            };

            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            modal.on('shown.bs.modal', function () {
                console.log('id: '+ modal.data('id'));
                console.log('type: '+ modal.find('.action').data('type'));
            })

        });
    </script>
@endsection