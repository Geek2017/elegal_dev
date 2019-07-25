@extends('layouts.master')

@section('title', 'Chargeable Expenses')


@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Chargeable Expenses</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                </li>
                <li class="active">
                    <strong>Chargeable Expenses</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <div class="title-action">
                {{--<button type="submit" class="btn btn-primary">Button</button>--}}
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Create <small>Chargeable Expense</small></h5>
                    </div>
                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon bg-muted">S.R. No.:</span>
                                        <input type="text" name=""  class="form-control">
                                        <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon bg-muted">Docket No.:</span>
                                        <input type="text" name=""  class="form-control">
                                        <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon bg-muted">Contract No.:</span>
                                        <input type="text" name=""  class="form-control">
                                        <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <div class="input-group m-b date">
                                        <span class="input-group-addon bg-muted">Date Rendered:</span>
                                        <input type="text" name=""  class="form-control">
                                        <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon bg-muted">Contract Type:</span>
                                        <div class="input-group-btn input-group-select">
                                            {{Form::select('', array(null => 'Select Retainer','Special' => 'Special','General' => 'General'),null,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group m-b date">
                                        <span class="input-group-addon bg-muted">Council:</span>
                                        <input type="text" name=""  class="form-control">
                                        <span class="input-group-addon bg-muted"><span class=""><i class="fa fa-search"></i></span></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon bg-muted">Description:</span>
                                        <div class="input-group-btn input-group-select">
                                            {{Form::select('', array(null => 'Select','Desc-1' => 'Desc-1','Desc-2' => 'Desc-2'),null,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group m-b date">
                                        <span class="input-group-addon bg-muted">Amount:</span>
                                        <input type="text" name=""  class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <div class="input-group m-b date">
                                        <span class="input-group-addon bg-muted">Client Name:</span>
                                        <input type="text" name=""  class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group m-b date">
                                        <span class="input-group-addon bg-muted">Case Title:</span>
                                        <input type="text" name=""  class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group m-b date">
                                        <span class="input-group-addon bg-muted">Trust Fund:</span>
                                        <input type="text" name=""  class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon bg-muted">Type:</span>
                                        <div class="input-group-btn input-group-select">
                                            {{Form::select('', array(null => 'Select','Type-1' => 'Type-1','Type-2' => 'Type-2'),null,array('class'=>'form-control'))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="textarea-group">
                                        <span class="textarea-group-addon bg-muted">Explanation:</span>
                                        <textarea name="other_conditions" id="" class="form-control resize-vertical"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default table-box">
                                    {{--<div class="panel-heading">--}}
                                        {{--<label>Fees List</label>--}}
                                    {{--</div>--}}
                                    <div class="table-box">
                                        <table id="" class="table table-stripped dt-responsive nowrap">
                                            <thead>
                                            <tr>
                                                <th width="40%">Description</th>
                                                <th width="15%">Amount</th>
                                                <th width="15%">Type</th>
                                                <th width="30%">Explanation</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            <tr>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A animi architecto asperiores atque beatae consequatur cum distinctio dolor dolore eligendi, enim esse eum eveniet harum magnam minima neque, nesciunt nihil non nostrum obcaecati odio officiis omnis optio perspiciatis quas reprehenderit sapiente sit ullam velit vitae voluptatem voluptates voluptatibus! Autem, mollitia.</td>
                                                <td>1,000,000.00</td>
                                                <td>Lorem ipsum</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam amet blanditiis dignissimos dolorum ex libero odio reiciendis repellendus sunt unde?</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
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
    {{--{!! Html::style('') !!}--}}
@endsection

@section('scripts')
    {{--{!! Html::script('') !!}--}}
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection