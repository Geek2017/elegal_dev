@extends('layouts.master')

@section('title', 'Profile')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Profile</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Profile</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                <a href="{!! route('profile-edit') !!}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit </a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Profile Detail</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="m-t-none m-b text-success">Basic Information</h3>
                                <div class="row">
                                    <div class="col-lg-5 col-lg-push-7">
                                        <img alt="image" class="img-responsive" src="{!! ($data->profile->image != '') ? '/uploads/image/'.$data->profile->image : 'http://via.placeholder.com/300x300' !!}">
                                    </div>
                                    <div class="col-lg-7 col-lg-pull-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <p>{{ $data->profile->firstname }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Middle Name</label>
                                                    <p>{{ $data->profile->middlename }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>last Name</label>
                                                    <p>{{ $data->profile->lastname }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" id="data_3">
                                                    <label>Date of Birth</label>
                                                    <p>{{ \Carbon\Carbon::parse($data->profile->dob)->toFormattedDateString() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Marital Status</label>
                                                    <p>{{ $data->profile->status }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Blood Type</label>
                                                    <p>{{ $data->profile->blood_type }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="hr-line-dashed"></div>
                                <h3 class="m-t-none m-b text-success">Contact Information</h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            @foreach($data->profile->contact as $contact)
                                                @if(($contact->type == 'telephone')||($contact->type == 'mobile')||($contact->type == 'fax'))
                                                    <label>Contact Number ({{$contact->type}})</label>
                                                    <p>{{ $contact->description }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Permanent Address</label>
                                            @foreach($data->profile->contact as $contact)
                                                @if($contact->type == 'permanent_address')
                                                    <p>{{ $contact->description }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="form-group">
                                            <label>Present Address</label>
                                            @foreach($data->profile->contact as $contact)
                                                @if($contact->type == 'present_address')
                                                    <p>{{ $contact->description }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>
                                <h3 class="m-t-none m-b text-success">In case of Emergency Contact</h3>
                                @foreach($data->icoe as $icoe)
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <p>{!! $icoe->name !!}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Relation</label>
                                        <p>{!! $icoe->relation_type !!}</p>
                                    </div>
                                    <div class="form-group">
                                        @foreach($icoe->contact as $contact)
                                            @if(($contact->type == 'telephone')||($contact->type == 'mobile')||($contact->type == 'fax'))
                                                <label>Contact Number ({{$contact->type}})</label>
                                                <p>{{ $contact->description }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        @foreach($icoe->contact as $contact)
                                            @if($contact->type == 'present_address')
                                                <p>{{ $contact->description }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                @endforeach

                            </div>

                            <div class="col-sm-6">
                                <h3 class="m-t-none m-b text-success">Account security question</h3>
                                <div class="form-group">
                                    <label>Question 1</label>
                                    <p>{!! $data->sqa[0]->question !!}</p>
                                </div>
                                <div class="form-group">
                                    <label>Answer 1</label>
                                    <p>{!! $data->sqa[0]->answer !!}</p>
                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <label>Question 2</label>
                                    <p>{!! $data->sqa[1]->question !!}</p>
                                </div>
                                <div class="form-group">
                                    <label>Answer 2</label>
                                    <p>{!! $data->sqa[1]->answer !!}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('styles')

@endsection

@section('scripts')

@endsection