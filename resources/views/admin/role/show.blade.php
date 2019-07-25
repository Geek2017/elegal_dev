@extends('layouts.master')

@section('title', 'Role')


@section('content')

    {{ Form::open(array('route' => array('role-update', $role->id))) }}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Show Role [ <strong>{!! $role->display_name !!}</strong> ]</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="/role">Role</a>
                </li>
                <li class="active">
                    <strong>Show Role</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Role Name: <strong class="text-success">{!! $role->display_name !!}</strong></h5>
                    </div>
                    <div class="ibox-content">
                        @foreach($permissions->chunk(4) as $chunk)
                            <div class="row">
                                @foreach($chunk as $permission)
                                    <div class="col-sm-3">
                                        <dl class="permission-box">
                                            @php $datas = \Spatie\Permission\Models\Permission::where('table_name',$permission->table_name)->get(); @endphp
                                            <dt>
                                                <div class="i-checks">
                                                    <input type="checkbox" value="" class="check-all">
                                                    {!! $permission->table_display_name !!}
                                                </div>
                                            </dt>
                                            @foreach($datas as $data)
                                                <dd>
                                                    <div class="i-checks">
                                                        <input type="checkbox" name="permission[]" class=" @if($loop->first)first-permission @else permission @endif " value="{!! $data->name !!}" @if(in_array($data->id, $default)) checked @endif >
                                                        {!! $data->display_name !!}
                                                    </div>
                                                </dd>
                                            @endforeach
                                        </dl>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hr-line-dashed"></div>
                        @endforeach


                        
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}

@endsection


@section('styles')
    {!! Html::style('css/plugins/iCheck/custom.css') !!}
@endsection

@section('scripts')
    {!! Html::script('js/plugins/iCheck/icheck.min.js') !!}
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green'
            });

            checkAll();
            function checkAll(){
                $('.permission-box').each(function(){
                    var checkbox = $(this).find('.permission, .first-permission').length;
                    var checked = $(this).find('.permission:checked, .first-permission:checked').length;

                    if(checkbox === checked){
                        $(this).find('.check-all').iCheck('check');
                    }else{
                        $(this).find('.check-all').iCheck('uncheck');
                    }

//                    var checkbox = $(this).find('.permission').length;
//                    var checked = $(this).find('.permission:checked').length;
//                    var first = $(this).find('.first-permission:checked').length;

//                    if( (checkbox !== 0) && (checkbox === checked) ){
//                        $(this).find('.check-all').iCheck('check');
//                    }else{
//                        $(this).find('.check-all').iCheck('uncheck');
//                    }
//                    if( (checkbox === 0) && (first === 1)){
//                        $(this).find('.check-all').iCheck('check');
//                    }else{
//                        $(this).find('.check-all').iCheck('uncheck');
//                    }

                });
            }

            $('.check-all').on('ifClicked', function(){
                var box = $(this).closest('.permission-box').find('.permission, .first-permission');
                if($(this).is(':checked')){
                    box.iCheck('uncheck');
                }else{
                    box.iCheck('check');
                }
            });

            $('.permission').on('ifToggled', function(){
                var checkbox = $(this).closest('.permission-box').find('.permission').length;
                var checked = $(this).closest('.permission-box').find('.permission:checked').length;
                if(checkbox === checked){
                    $(this).closest('.permission-box').find('.check-all').iCheck('check');
                }else{
                    $(this).closest('.permission-box').find('.check-all').iCheck('uncheck');
                }
                if(checked > 0){
                    $(this).closest('.permission-box').find('.first-permission').iCheck('check');
                }else{
                    $(this).closest('.permission-box').find('.first-permission').iCheck('uncheck');
                }
            });

            $('.first-permission').on('ifToggled', function(){
                var first = $(this).closest('.permission-box').find('.first-permission:checked').length;
                var checked = $(this).closest('.permission-box').find('.permission').length;
                if(first < 1){
                    $(this).closest('.permission-box').find('.permission').iCheck('uncheck');
                }
                if( (checked === 0) && (first === 1)){
                    $(this).closest('.permission-box').find('.check-all').iCheck('check');
                }else{
                    $(this).closest('.permission-box').find('.check-all').iCheck('uncheck');
                }
            });

        });
    </script>
@endsection