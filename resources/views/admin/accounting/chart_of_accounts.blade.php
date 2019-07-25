@extends('layouts.master')

@section('title', 'Activity Report Sheet')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Activity report sheet</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>List</strong>
                </li>
                <li class="active">
                    <strong>Chart of Accounts</strong>
                </li>
            </ol>
        </div>
        <!-- <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('ars.create') !!}" class="btn btn-primary"><i class="fa fa-plus"></i> New Ars.</a>
            </div>
        </div> -->
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Chart of Accounts</h5>

                        <div class="ibox-tools">
                            <button class="btn btn-sm btn-primary" id="add-root" title="Add Category" style="margin-right: 15px;">
                                <i class="fa fa-plus"></i>
                                Root Category
                            </button>
                            <button class="btn btn-sm btn-info" id="add-item" title="Add Item" disabled="true">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" id="edit-item" title="Edit Item/Root" disabled="true">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" id="remove-item" title="Delete Item/Root" disabled="true">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div id="coa-js-tree" style="height: 300px; overflow: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal-root" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form class="form-horizontal" id="acct-form" novalidate>
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Account Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="row-root-acct-form" style="display: none;">
                            <div class="col-lg-12">
                                <!-- <div class="form-group">
                                    <label class="col-lg-2 control-label">Code:</label>
                                    <div class="col-lg-10">
                                        <input name="acct_code" id="root-acct-code" type="text" class="form-control" required>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        <input name="acct_title" id="root-acct-title" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Category:</label>
                                    <div class="col-lg-5">
                                        <select id="root-acct-category-type" name="category_type" class="form-control" required>
                                            <option value="">-SELECT-</option>
                                            <option value="A">ASSET</option>
                                            <option value="D">DIVIDEND</option>
                                            <option value="Q">EQUITY</option>
                                            <option value="X">EXPENSE</option>
                                            <option value="L">LIABILITY</option>
                                            <option value="R">REVENUE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-6 control-label">
                                        &nbsp;
                                        <input type="checkbox" name="root_cash_type" id="root-act-cash-type" class="bs-switch"> Account is Cash Type?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="row-acct-form" style="display: none;">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        <input name="acct_title" id="acct-title" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Type:</label>
                                    <div class="col-lg-5">
                                        <select id="acct-type" name="type" class="form-control">
                                            <option value="">-SELECT-</option>
                                            <option value="C">CATEGORY</option>
                                            <option value="A">ACCOUNT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-6 control-label" id="label-acct-type" style="display: none;">
                                        &nbsp;
                                        <input type="checkbox" name="cash_type" id="act-type" class="bs-switch"> Account is Cash Type?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info save-transactions">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
            </form>
        </div>
    </div>

@endsection


@section('styles')
    {!! Html::style('vendor/jstree/themes/default/style.min.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/bootstrap-switch/bootstrap-switch.js') !!}
    {!! Html::script('vendor/jstree/jstree.min.js') !!}
    <script>
        $(document).ready(function(){
            var $coaJsTree = $("#coa-js-tree"),
            $rootModal = $("#modal-root"),

            $btnAddRoot = $("#add-root"),
            $rootActCode = $("#root-acct-code"),
            $rootActTitle = $("#root-acct-title"),
            $rootAcctCategory = $("#root-acct-category-type"),
            $rootAcctCashType = $("#root-act-cash-type"),

            $rootActTitle = $("#root-acct-title"),
            $acctTitle = $("#acct-title"),
            $acctType = $("#acct-type"),
            $acctCashType = $("#act-cash-type"),

            $rootAcctCategory = $("#root-acct-category-type"),
            $rootAcctCashType = $("#root-act-cash-type"),

            $btnAddItem = $("#add-item"),
            $btnEditItem = $("#edit-item"),
            $btnRemoveItem = $("#remove-item"),

            $btnSaveAct =   $(".save-transactions"),
            $transactionType = '',
            $selectedAcct = null,
            $level = 0,
            $editType = null;

            $(".bs-switch").bootstrapSwitch({
                on: 'Yes',
                off: 'No',
                onClass: 'success',
                offClass: 'default',
                size: 'sm',
            });

            var setFieldRequired = function(rootAcct, AcctItem) {
                // $rootActCode.attr('required', rootAcct);
                $rootActTitle.attr('required', rootAcct);
                $rootAcctCategory.attr('required', rootAcct);
                $rootAcctCashType.attr('required', rootAcct);

                // $actCode.attr('required', AcctItem);
                $acctTitle.attr('required', AcctItem);
                $acctType.attr('required', AcctItem);
            };

            var openForm = function (type) {
                switch (type){
                    case 'root':
                        $("#row-acct-form").hide();
                        $("#row-root-acct-form").show();
                        setFieldRequired(true, false);
                        $rootActCode.focus();
                        break;
                    case 'item':
                        $("#row-root-acct-form").hide();
                        $("#row-acct-form").show();
                        setFieldRequired(false, true);
                        $rootActCode.focus();
                        break;

                }
            }

            $btnAddRoot.on('click', function(){
                $transactionType = 'root';
                $rootModal.modal('show').on('shown.bs.modal', function() {
                    openForm('root');
                });
            });

            $btnAddItem.on('click', function(){
                $transactionType = 'addItem';
                $rootModal.modal('show').on('shown.bs.modal', function() {
                    openForm('item');
                });
            });

            $btnEditItem.on('click', function() {
                $transactionType = 'editItem';
                $rootModal.modal('show').on('shown.bs.modal', function() {
                    if ($selectedAcct.metadata.parent) {
                        $editType = 'item';
                        openForm('item');
                        $acctTitle.val($selectedAcct.metadata.title);
                        $acctType.val($selectedAcct.metadata.type);
                    } else {
                        $editType = 'root';
                        openForm('root');
                        $rootActTitle.val($selectedAcct.metadata.title);
                        $rootAcctCategory.val($selectedAcct.metadata.category_type);
                        $("#root-act-cash-type").attr('checked', ($selectedAcct.metadata.is_cash_type)? true:false).trigger('change');
                    }
                });
            });

            $btnRemoveItem.on('click', function(){
                var data = $(this).data();
                
                var r = confirm("Delete Account?");
                if (r == true) {
                    $.ajax({
                        url: 'chart-of-accounts/' + $selectedAcct.metadata.id + '?_token={{csrf_token()}}',
                        type: 'DELETE',
                        success: function(result) {
                            // Do something with the result 
                            var ref = $coaJsTree.jstree(true),
                                sel = ref.get_selected();
                                console.log(sel);
                            if(!sel.length) { return false; }
                            ref.delete_node(sel);
                            toolbarDisabled(false, true, true, true);
                            $selectedAcct = null;
                        }
                    });
                }
            });

            var setNormalBalance = function (categoryType) {
                switch (categoryType) {
                    case 'A':
                    case 'D':
                    case 'X':
                        return 'D';
                        break;
                    case 'Q':
                    case 'L':
                    case 'R':
                        return 'C';
                        break;
                    default:
                        return 'D';
                }
            };

            var getIconClass = function (data) {
                if (data.type === 'C' && data.is_cash_type === "1") return 'fa fa-money blue';

                if (data.type === 'C') return 'fa fa-book green';

                if (data.type === 'A' && data.is_cash_account === 0) return 'fa fa-file-o orange2';

                if (data.type === 'A' && data.is_cash_account === 1 && data.is_default_cash_account === 0) return 'fa fa-money red';

                if (data.type === 'A' && data.is_cash_account === 1 && data.is_default_cash_account === 1) return 'fa fa-money orange2';

                return 'fa fa-book green'
            };

            var formatData = function (dataToFormat, additionalData) {
                additionalData = additionalData || {};

                var node = {
                    id: dataToFormat.id,
                    text: dataToFormat.title,
                    icon: getIconClass(dataToFormat),
                    // data: {
                    // },
                    metadata: dataToFormat
                };

                var formattedData = $.extend(node, additionalData);

                return formattedData;
            };

            var toolbarDisabled = function (root, add, edit, remove) {
                $btnAddRoot.attr('disabled', root);
                $btnAddItem.attr('disabled', add);
                $btnEditItem.attr('disabled', edit);
                $btnRemoveItem.attr('disabled', remove);
            };

            $coaJsTree.jstree({ 
                'core' : {
                    'check_callback': true,
                    'data' : {
                      'url' : "{{ route('chart.of.accounts.list') }}",
                      'data' : function (node) {
                        // console.log(node);
                        return { 'id' : node.id};
                      }
                    }
                },
                "plugins": ["hotkeys", "json_data", "data", "themes", "ui", "crrm"], // "crrm",  , "dnd"
            }).bind("select_node.jstree", function(evt, data){
                var item = data.node;
                $selectedAcct = item.original;
                // $selectedAcctMetadata = item.original.metadata;
                // console.log(item.children.length);
                if (item.children.length > 0) {
                    toolbarDisabled(false, false, false, true);
                } else {
                    toolbarDisabled(false, false, false, false);
                }
            }).bind("loaded.jstree", function (event, data) {
                // you get two params - event & data - check the core docs for a detailed description
                $(this).jstree("open_all");
            });

            $("#acct-type").bind('change', function(){
                switch($(this).val()) {
                    case 'C':
                        $('#label-acct-type').show();
                        $acctCashType.attr('required', true);
                        break;
                    case 'D':
                    default:
                        $('#label-acct-type').hide();
                        $acctCashType.attr('required', false);
                        break;
                };
            });
            $('#acct-form').bind('submit',function(event) {
                
                event.preventDefault();

                switch($transactionType) {
                    case 'root':
                        console.log($rootAcctCashType.val());

                        var isCashType = ($rootAcctCashType.val() == 'on') ? 1:0;

                        $.post( "{{ route('chart-of-accounts.store') }}?_token={{csrf_token()}}" , 
                            {
                                // code: $rootActCode.val(), 
                                title: $rootActTitle.val(), 
                                level: 1, 
                                cash_type: isCashType, 
                                category_type: $rootAcctCategory.val(),
                                normal_account_balance: setNormalBalance($rootAcctCategory.val()),
                                type: 'C', 
                            }
                        ).done(function( data ) {
                            $rootModal.modal('hide');
                            // add mudule
                            $coaJsTree.jstree(true).create_node('#', formatData(data) , "last", function() {
                                alert("New Account successfully added!");
                            });
                        }).fail(function(xhr, status, error) {
                            console.log(xhr, 'xhr');
                            console.log(status, 'status');
                            // error handling
                            alert(error);
                        });

                        break;
                    case 'addItem':
                        
                        var isCashType = ($rootAcctCashType.val() == 'on') ? 1:0;

                        $.post( "{{ route('chart-of-accounts.store') }}?_token={{csrf_token()}}" , 
                            {
                                // code: $acctCode.val(), 
                                title: $acctTitle.val(), 
                                level: $selectedAcct.metadata.level + 1, 
                                parent: $selectedAcct.metadata.id, 
                                cash_type: isCashType, 
                                category_type: $selectedAcct.metadata.category_type,
                                normal_account_balance: setNormalBalance($selectedAcct.metadata.category_type),
                                type: $acctType.val(), 
                            }
                        ).done(function( data ) {
                            $rootModal.modal('hide');
                            // add mudule
                            $coaJsTree.jstree(true).create_node($selectedAcct.metadata.id, formatData(data) , "last", function() {
                                alert("New Account successfully added!");
                            });

                        }).fail(function(xhr, status, error) {
                            console.log(xhr, 'xhr');
                            console.log(status, 'status');
                            // error handling
                            alert(error);
                        });                        
                        break;
                    case 'editItem':
                        
                        var isCashType = ($rootAcctCashType.val() == 'on') ? 1:0;
                        var patchData = {};

                        if ($editType == 'root') {
                            patchData = {
                                title: $rootActTitle.val(), 
                                cash_type: isCashType, 
                                category_type: $rootAcctCategory.val(),
                                normal_account_balance: setNormalBalance($rootAcctCategory.val()),
                            };
                        } else if ($editType == 'item') {
                            patchData = {
                                title: $acctTitle.val(), 
                                cash_type: isCashType, 
                                type: $acctType.val(), 
                                category_type: $selectedAcct.metadata.category_type,
                                normal_account_balance: setNormalBalance($selectedAcct.metadata.category_type),
                            };
                        }

                        $.post( "chart-of-accounts/"+$selectedAcct.metadata.id+"/update?_token={{csrf_token()}}" , 
                            patchData
                        ).done(function( data ) {
                            $rootModal.modal('hide');
                            // add mudule
                            // $coaJsTree.jstree(true).create_node($selectedAcct.metadata.id, formatData(data) , "last", function() {
                            //     alert("New Account successfully added!");
                            // });
                            // var ref = $coaJsTree.jstree(true),
                            //     sel = ref.get_selected();
                            // if(!sel.length) { return false; }
                            // sel = sel[0];
                            // ref.edit($selectedAcct);
                            $coaJsTree.jstree("refresh");
                        }).fail(function(xhr, status, error) {
                            console.log(xhr, 'xhr');
                            console.log(status, 'status');
                            // error handling
                            alert(error);
                        });                        
                        break;
                    // default:
                    //     code block
                }
            });

        });
    </script>
@endsection