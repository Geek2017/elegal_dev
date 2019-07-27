<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta id="_token" value="{!! csrf_token() !!}">

    <title>E-Legal | @yield('title')</title>
    {{ Html::favicon( 'img/placeholder.jpg' ) }}

    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('font-awesome/css/font-awesome.css') !!}
    {!! Html::style('css/animate.css') !!}
    {!! Html::style('css/style.css') !!}
    {!! Html::style('css/elegal-style.css') !!}
    @yield('styles')

</head>
@role('admin') <body class="skin-3" > @else <body class=""> @endrole
{{--<body class="skin-3">--}}

<div id="wrapper">
    @php
        $user = \App\User::with('profile')->find(Auth::user()->id);
    @endphp
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="{!! ($user->profile != '') ? '/uploads/image/'.$user->profile->image : '/img/placeholder.jpg' !!}" style="width: 48px;" />
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs">
                                    <strong class="font-bold">{!! ($user->profile != '') ? $user->profile->firstname.' '.$user->profile->lastname : Auth::user()->name !!}</strong>
                                </span>
                                <span class="text-muted text-xs block">
                                    @foreach(\Spatie\Permission\Models\Role::get() as $role)
                                        @role($role->name)
                                            {!! ucfirst($role->display_name) !!}
                                        @endrole
                                    @endforeach
                                    <b class="caret"></b></span>
                            </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            @if(auth()->user()->can('browse-profile'))
                            <li><a href="{{ route('profile.index') }}">Profile</a></li>
                            <li class="divider"></li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        PMR
                    </div>
                </li>

                {{--side menus start--}}
                <li class="{!! if_uri_pattern(array('/')) == 1 ? 'active' : '' !!}">
                    <a href="/"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span></a>
                </li>

                @if(auth()->user()->can('browse-client'))
                <li class="{!! if_uri_pattern(array('client*')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa fa-legal"></i> <span class="nav-label">Client</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="{!! if_uri_pattern(array('client')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('client.index') !!}"><i class="fa  fa-exchange"></i> <span class="nav-label">List</span></a>
                        </li>
                        @if(auth()->user()->can('add-client'))
                        <li class="{!! if_uri_pattern(array('client/create')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('client.create') !!}"><i class="fa  fa-plus-square"></i> <span class="nav-label">Create</span></a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(auth()->user()->can('browse-counsel'))
                <li class="{!! if_uri_pattern(array('counsel*')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa fa-briefcase"></i> <span class="nav-label">Counsel</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li class="{!! if_uri_pattern(array('counsel')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('counsel.index') !!}"><i class="fa  fa-exchange"></i> <span class="nav-label">List</span></a>
                        </li>

                        @if(auth()->user()->can('add-counsel'))
                        <li class="{!! if_uri_pattern(array('counsel/create')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('counsel.create') !!}"><i class="fa  fa-plus-square"></i> <span class="nav-label">Create</span></a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(auth()->user()->can('browse-contract'))
                <li class="{!! if_uri_pattern(array('contract*')) == 1 ? 'active' : '' !!}">
                    <a href="{!! route('contract.index') !!}"><i class="fa fa-folder-open-o"></i> <span class="nav-label">Contracts</span></a>
                </li>
                @endif

                @if(auth()->user()->can('browse-case-tracker'))
                <li class="{!! if_uri_pattern(array('case-tracker')) == 1 ? 'active' : '' !!}">
                    <a href="{!! route('case-tracker.index') !!}"><i class="fa fa-balance-scale"></i> <span class="nav-label">Case Tracker</span></a>
                </li>
                @endif

                @if(auth()->user()->can('browse-supply-management'))
                <li class="{!! if_uri_pattern(array('supply*')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa fa-list"></i> <span class="nav-label">Supply Management</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="{!! if_uri_pattern(array('supply')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('supply.index') !!}"><i class="fa fa-list"></i> <span class="nav-label">List of Supply</span></a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Temporary hide this menu please do not remove! -->
                <!-- <li class="{!! if_uri_pattern(array('chart-of-accounts*', 'accounting*',)) == 1 ? 'active' : '' !!}">
                    <a href="#">
                        <i class="fa fa-bar-chart"></i>
                        <span class="nav-label">Accounting</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        <li class="{!! if_uri_pattern(array('chart.of.accounts*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('chart-of-accounts.index') !!}">
                                <i class="fa fa-pie-chart"></i> <span class="nav-label">Chart of Accounts</span>
                            </a>
                        </li>
                    </ul>
                </li> -->

                @if( (auth()->user()->can('browse-charge-slip')) || (auth()->user()->can('browse-cash-receipt')) )
                <li class="{!! if_uri_pattern(array('walk-in*', 'cash-receipt*')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa  fa-tasks"></i> <span class="nav-label">Transactions</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        @if(auth()->user()->can('browse-charge-slip'))
                        <li class="{!! if_uri_pattern(array('walk-in*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('walk-in.charge-slip.index') !!}"><i class="fa fa fa-tag"></i> <span class="nav-label">Walk In Charge Slip</span></a>
                        </li>
                        @endif

                        @if( auth()->user()->can('browse-cash-receipt') )
                        <li class="{!! if_uri_pattern(array('cash-receipt*')) == 1 ? 'active' : '' !!}">
                            <a href="#">Cash Receipt <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                @if(auth()->user()->can('browse-cash-receipt'))
                                <li class="{!! if_uri_pattern(array('cash-receipt')) == 1 ? 'active' : '' !!}">
                                    <a href="{!! route('cash-receipt.index') !!}"><i class="fa fa-money"></i> <span class="nav-label">List of Payment</span></a>
                                </li>
                                @endif
                                @if(auth()->user()->can('add-cash-receipt'))
                                <li class="{!! if_uri_pattern(array('cash-receipt')) == 1 ? 'active' : '' !!}">
                                    <a href="{!! route('cash-receipt.create') !!}"><i class="fa fa-money"></i> <span class="nav-label">Create Payment</span></a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif

                @if( (auth()->user()->can('browse-report')) || (auth()->user()->can('browse-activity-report-sheet')) || (auth()->user()->can('browse-service-report')) )
                <li class="{!! if_uri_pattern(array('report*', 'ars*', 'service-report*', 'trust-fund*', 'counsel-service-reports')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa fa-list"></i> <span class="nav-label">Reports</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        @if(auth()->user()->can('browse-activity-report-sheet'))
                        <li class="{!! if_uri_pattern(array('ars*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('ars.index') !!}"><i class="fa fa-pencil-square"></i> <span class="nav-label">Activity Report Sheet</span></a>
                        </li>
                        @endif

                        @if(auth()->user()->can('cash-receipt-report'))
                        <li class="{!! if_uri_pattern(array('reports/cash-receipts*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('reports.cash-receipt') !!}"><i class="fa fa-file-text"></i> <span class="nav-label">Cash Receipts</span></a>
                        </li>
                        @endif

                        @if(auth()->user()->can('counsel-service-report-report'))
                        <li class="{!! if_uri_pattern(array('reports/cash-receipts*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('reports.counsel-service-reports') !!}"><i class="fa fa-file-text"></i> <span class="nav-label">Counsel Service Reports</span></a>
                        </li>
                        @endif

                        @if( (auth()->user()->can('browse-service-report')) && (!auth()->user()->hasrole('counsel')) )
                        <li class="{!! if_uri_pattern(array('service-report*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('service-report.index') !!}"><i class="fa fa-file-text"></i> <span class="nav-label">Service Report</span></a>
                        </li>
                        @endif

                        @if(auth()->user()->can('trust-fund-ledger-report'))
                        <li class="{!! if_uri_pattern(array('trust-fund*')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('trust-fund.index') !!}"><i class="fa fa-file-text"></i> <span class="nav-label">Trust Fund Ledger</span></a>
                        </li>
                        @endif

                    </ul>
                </li>
                @endif

                @if( (auth()->user()->hasRole('counsel')) && (auth()->user()->can('browse-counsel-service-report')) )
                    <li class="{!! if_uri_pattern(array('')) == 1 ? 'active' : '' !!}">
                        <a href=""><i class="fa fa-file-text"></i> <span class="nav-label">Service Report</span></a>
                    </li>
                @endif

                @if(auth()->user()->can('browse-billing'))
                <li class="{!! if_uri_pattern(array('billing*')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">Billing</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="{!! if_uri_pattern(array('billing')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('billing.index') !!}"> <i class="fa  fa-exchange"></i> <span class="nav-label">List</span></a>
                        </li>
                        @if(auth()->user()->can('add-billing'))
                        <li class="{!! if_uri_pattern(array('billing/create')) == 1 ? 'active' : '' !!}">
                            <a href="{!! route('billing.create') !!}"><i class="fa fa-plus-square"></i> <span class="nav-label">Create</span></a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(auth()->user()->hasRole('admin'))
                <li class="{!! if_uri_pattern(array('user*','profile*','role*','logs','print*','fee*','note*')) == 1 ? 'active' : '' !!}">
                    <a href="#"><i class="fa fa-gears"></i> <span class="nav-label">Others</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li class="{!! if_uri_pattern(array('user*','profile*')) == 1 ? 'active' : '' !!}"><a href="{!! route('user.index') !!}">Users</a></li>
                        <li class="{!! if_uri_pattern(array('role*')) == 1 ? 'active' : '' !!}"><a href="{!! route('role') !!}">Roles</a></li>
                        <li class="{!! if_uri_pattern(array('logs')) == 1 ? 'active' : '' !!}"><a href="{!! route('logs') !!}">Logs</a></li>
                        <li class="{!! if_uri_pattern(array('fee*')) == 1 ? 'active' : '' !!}">
                            <a href="#">Fee's Info <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li class="{!! if_uri_pattern(array('fee')) == 1 ? 'active' : '' !!}"><a href="{!! route('fee.index') !!}">List</a></li>
                                <li class="{!! if_uri_pattern(array('fee/create')) == 1 ? 'active' : '' !!}"><a href="{!! route('fee.create') !!}">Create</a></li>
                            </ul>
                        </li>
                        <li class="{!! if_uri_pattern(array('note*')) == 1 ? 'active' : '' !!}">
                            <a href="#">Billing Notes <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li class="{!! if_uri_pattern(array('note')) == 1 ? 'active' : '' !!}"><a href="{!! route('note') !!}">Notes / Text</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Paralegal Assignment Sheet -->
                @if( (auth()->user()->hasRole('admin')) || (auth()->user()->hasRole('office-clerk')) )
                <li class="{!! if_uri_pattern(array('paralegal*')) == 1 ? 'active' : '' !!}">
                    <a href="{!! route('pas.index') !!}"><i class="fa fa-file-text"></i> <span class="nav-label">Paralegal Assignment Sheet</span></a>
                </li>
                @endif

                {{--side menus end--}}

            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">

        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <!-- <form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form> -->
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Welcome to PMR E-Legal System</span>
                    </li>
                    
                    <!-- <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="profile.html" class="pull-left">
                                        <img alt="image" class="img-circle" src="/img/a7.jpg">
                                    </a>
                                    <div class="media-body">
                                        <small class="pull-right">46h ago</small>
                                        <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                        <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="profile.html" class="pull-left">
                                        <img alt="image" class="img-circle" src="/img/a4.jpg">
                                    </a>
                                    <div class="media-body ">
                                        <small class="pull-right text-navy">5h ago</small>
                                        <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                        <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="profile.html" class="pull-left">
                                        <img alt="image" class="img-circle" src="/img/profile.jpg">
                                    </a>
                                    <div class="media-body ">
                                        <small class="pull-right">23h ago</small>
                                        <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                        <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="text-center link-block">
                                    <a href="mailbox.html">
                                        <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li> -->
                    
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell"></i>  
                            <span class="label label-primary" id="total-no-of-notifications">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li id="no-pending-notifications">
                                <a href="javascript:void()">
                                    <div class="text-center">
                                        <STRONG>NO PENDING NOTIFICATIONS</STRONG>
                                    </div>
                                </a>
                            </li>

                            <!-- <li>
                                <a href="mailbox.html">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="profile.html">
                                    <div>
                                        <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                        <span class="pull-right text-muted small">12 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="grid_options.html">
                                    <div>
                                        <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <div class="text-center link-block">
                                    <a href="notifications.html">
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li> -->
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>

            </nav>
        </div>

        @include('flash::message')
        @yield('content')

        <div class="footer">
            <div>
                <strong>Powered By:</strong> <a href="https://www.pacificblueit.com" target="_blank" >Pacific Blue I.T. &copy; {{ Date('Y') }}</a>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="is_case_was_notified" id="is_case_was_notified" value="{{$is_case_was_notified}}">
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

<!-- Mainly scripts -->
{!! Html::script('js/jquery-3.3.1.min.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('js/plugins/metisMenu/jquery.metisMenu.js') !!}
{!! Html::script('js/plugins/slimscroll/jquery.slimscroll.min.js') !!}
{!! Html::style('css/plugins/sweetalert/sweetalert.css') !!}

<!-- Custom and plugin javascript -->
{!! Html::script('js/inspinia.js') !!}
{!! Html::script('js/plugins/pace/pace.min.js') !!}
{!! Html::script('js/plugins/sweetalert/sweetalert.min.js') !!}
{{--{!! Html::script('js/jquery.masknumber.js') !!}--}}
{!! Html::script('js/elegal-script.js') !!}
@yield('scripts')

</body>

</html>
