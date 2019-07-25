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

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                {{Form::open(array('method' => 'patch', 'url'=>route('supply.update', ['id' => $supply->id]), 'class' => 'form-horizontal'))}}
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
                                                    @if ($c['code'] == $supply->category)
                                                        <option value="{{$c['code']}}" selected>{{$c['title']}}</option>
                                                    @else
                                                        <option value="{{$c['code']}}">{{$c['title']}}</option>
                                                    @endif
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
                                            <input name="name" type="text" class="form-control" id="supply-name" value="{{ $supply->name }}">
                                            @if($errors->has('name'))
                                                <span class="text-danger">{{$errors->first('name')}}</span>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>In</label>
                                                    <input name="in" type="number" step="1" class="form-control" id="supply-in-no">
                                                </div>
                                                <div class="form-group">
                                                    <label>Out</label>
                                                    <input name="out" type="number" step="1" class="form-control" id="supply-out-no">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-center">
                                                <br><br>
                                                <h2 style="font-weight: bold;">Balance</h2>
                                                <h1 style="font-size: 40px;font-weight: bold;">
                                                    {{$supply->latestHistory->balance}} pcs.
                                                </h1>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12" id="button-wrapper">
                                                {{Form::submit('Update Supply', array('class'=>'btn btn-md btn-warning pull-right'))}}
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