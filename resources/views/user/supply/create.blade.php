@extends('layouts.master')

@section('title', 'Supply')


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
                    <strong>Add Supply</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                {{Form::open(array('route'=>array('supply.store'), 'class' => 'form-horizontal', 'id' => 'supply-form'))}}
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                           <h3>Supply Information</h3>
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
                                
                                <div class="col-md-12">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <select name="category" id="rc-account-id" class="form-control" required>
                                                @foreach ($categories as $c)
                                                    <option value="{{$c['code']}}">{{$c['title']}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('category'))
                                                <span class="help-block m-b-none">
                                                    <strong>{{ $errors->first('category') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input name="name" type="text" class="form-control" id="supply-name">
                                            @if($errors->has('name'))
                                                <span class="text-danger">{{$errors->first('name')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Initial Supply</label>
                                            <input name="initial_supply" type="number" class="form-control" id="supply-init-no">
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12" id="button-wrapper">
                                                {{Form::submit('Add Supply', array('class'=>'btn btn-md btn-success pull-right'))}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{Form::close()}}
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
            
        });
    </script>
@endsection