@extends('layouts.master')

@section('title', 'Counsel|Profile')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Counsel Profile</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="{!! route('counsel.index') !!}">Counsel List</a>
                </li>
                <li class="active">
                    <strong>Counsel Profile</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                @if(auth()->user()->can('edit counsel'))
                <a href="{!! route('counsel.edit', array('counsel' => $counsel->id)) !!}" class="btn btn-white"><i class="fa fa-pencil"></i> Edit</a>
                @endif
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row m-b-lg m-t-lg">
            <div class="col-md-6">

                <div class="profile-image">
                    <img src="{!! ($counsel->profile->image != '') ? '/uploads/image/'.$counsel->profile->image : '/img/placeholder.jpg' !!}" class="img-circle circle-border m-b-md" alt="profile">
                </div>
                <div class="profile-info">
                    <div class="">
                        <div>
                            <h2 class="no-margins">
                                {!! $counsel->profile->firstname !!} {!! $counsel->profile->middlename !!} {!! $counsel->profile->lastname !!}
                            </h2>
                            <h4>{!! $counsel->lawyer_type !!} <small>[ {!! $counsel->lawyer_code !!} ]</small></h4>
                            <small>
                                {!! $counsel->address->description !!}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <table class="table small m-b-xs">
                    <tbody>
                    <tr>
                        <td>
                            <strong>142</strong> Projects
                        </td>
                        <td>
                            <strong>22</strong> Followers
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <strong>61</strong> Comments
                        </td>
                        <td>
                            <strong>54</strong> Articles
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>154</strong> Tags
                        </td>
                        <td>
                            <strong>32</strong> Friends
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <small>Sales in last 24h</small>
                <h2 class="no-margins">206 480</h2>
                <div id="sparkline1"></div>
            </div>


        </div>
    </div>

@endsection


@section('styles')
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection