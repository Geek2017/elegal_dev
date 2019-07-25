@extends('layouts.head')
@section('title','Register')

<!-- styles for wizard -->
@section('styles')
<style>
@media only screen and (max-width: 1199px) {
    .fieldScroll {
        overflow-y:scroll;
    }
}
.wizard-big.wizard > .content {
    min-height: 360px;
}
</style>
@endsection

@section('content')
<!-- Content -->
<div id="wrapper">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h2>
                        Sign Up
                    </h2>
                    <p>
                        Please fill up each form, to move to the next form click next.
                    </p>

                    <form id="form" action="#" class="wizard-big">
                    {{ csrf_field() }}
                        <h1>Account</h1>
                        <fieldset>
                            <h2>Account Information</h2>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Email *</label>
                                        <input id="email" name="email" type="text" class="form-control required email">
                                    </div>
                                    <div class="form-group">
                                        <label>Password *</label>
                                        <input id="password" name="password" type="text" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password *</label>
                                        <input id="confirm" name="confirm" type="text" class="form-control required">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="text-center">
                                        <div style="margin-top: 20px">
                                            <i class="fa fa-sign-in" style="font-size: 180px;color: #e5e5e5 "></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h1>Profile</h1>
                        <fieldset>
                            <h2>Profile Information</h2>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Date of Birth *</label>
                                        <input id="bday" name="bday" type="date" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact No. *</label>
                                        <input id="contactNo" name="contactNo" type="text" class="form-control required">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h1>Emergency Contacts</h1>
                        <fieldset class="fieldScroll">
                            <div class="row">
                                <div class="col-lg-6">
                                <h2>Contact #1</h2>
                                    <div class="form-group">
                                        <label>Name *</label>
                                        <input id="name1" name="name1" type="text" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact No. *</label>
                                        <input id="contactNo1" name="contactNo1" type="text" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <p><label>Complete Adress *</label></p>
                                        <textarea class="form-control"></textarea>
                                    </div>
                                </div>
                                <h2>Contact #2</h2>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Name *</label>
                                        <input id="name2" name="name2" type="text" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact No. *</label>
                                        <input id="contactNo2" name="contactNo2" type="text" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <p><label>Complete Adress *</label></p>
                                        <textarea class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h1>Security Question</h1>
                        <fieldset>
                        <div class="col-lg-6">
                            <div class="form-group">
                            <label>Question #1 *</label>
                            <select class="form-control m-b" name="question1" required>
                                <option value="" disabled selected>Please select question</option>
                                <option>option 1</option>
                                <option>option 2</option>
                                <option>option 3</option>
                                <option>option 4</option>
                            </select>                            
                                <label>Answer *</label>
                                <input id="answer1" name="answer1" type="text" class="form-control required">
                            </div>                      
                        </div>
                        <div class="col-lg-6">
                        <div class="form-group">
                            <label>Question #2 *</label>
                            <select class="form-control m-b" name="question2" required>
                                <option value="" disabled selected>Please select question</option>
                                <option>option 1</option>
                                <option>option 2</option>
                                <option>option 3</option>
                                <option>option 4</option>
                            </select>                            
                                <label>Answer *</label>
                                <input id="answer2" name="answer2" type="text" class="form-control required">
                        </div> 
                        </div>                       
                        </fieldset>
                    </form>
                </div>
            </div>
            </div>

        </div>
    </div>
</div>
<!-- Script for wizard -->
@section('scripts')


@endsection
    
@endsection

