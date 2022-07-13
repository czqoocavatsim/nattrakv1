<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.4/holder.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.4/holder.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @toastr_css
    @livewireStyles
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>@if(isset($_pageTitle)) {{ $_pageTitle }} :: @endif natTrak :: VATSIM</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <img src="{{ asset('images/natTrak_Logo_White_2000px.png') }}" class="" style="height: 50px; margin-top: 50px; margin-bottom: 50px; margin-left" />

            <div class="row d-flex justify-content-center">
                <div class="col-lg-10">
                    <nav class="shadow navbar navbar-expand-lg navbar-light bg-light menu rounded mx-n3">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('welcome') }}">Home</a>
                            </li>
                            @can('activeController')
                            <li class="nav-item dropdown">
                                <a class="nav-link {{ Request::is('controllers/*') ? 'active' : '' }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Controllers
                                </a>
                                <div class="dropdown-menu menu shadow" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('controllers.clx.pending') }}">Pending RCL Messages</a>
                                    <a class="dropdown-item" href="{{ route('controllers.clx.processed') }}">Processed RCL Messages</a>
                                </div>
                            </li>
                            @endcan
                            @can('activePilot')
                            <li class="nav-item dropdown">
                                <a class="nav-link {{ Request::is('pilots/*') ? 'active' : '' }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Pilots
                                </a>
                                <div class="dropdown-menu menu shadow" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('pilots.rcl.index') }}">Request Oceanic Clearance</a>
                                    <a href="{{ route('pilots.message-history') }}" class="dropdown-item">Message History</a>
                                </div>
                            </li>
                            @endcan
                            @can('administrate')
                            <li class="nav-item dropdown">
                                <a class="nav-link {{ Request::is('administration/*') ? 'active' : '' }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Administration
                                </a>
                                <div class="dropdown-menu menu shadow" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->access_level >= \App\Enums\AccessLevelEnum::Root)
                                        <a class="dropdown-item" href="{{ route('administration.accounts') }}">Admin Users</a>
                                    @endif
                                    <a href="{{ route('administration.controllers') }}" class="dropdown-item">Controller Permissions</a>
                                    <a class="dropdown-item" href="{{ route('administration.activity-log') }}">Activity Log</a>
                                    <a href="{{ route('administration.notams.index') }}" class="dropdown-item">NOTAMs</a>
                                </div>
                            </li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown">
                                    Tutorials
                                </a>
                                <div class="dropdown-menu menu shadow">
                                    <div class="dropdown-item small text-muted" style="cursor: default">Pilots</div>
                                    <a target="_blank" href="https://knowledgebase.ganderoceanic.ca/nattrak/requesting-oceanic-clearance/" class="dropdown-item">Requesting Oceanic Clearance</a>
                                    <a target="_blank" href="https://knowledgebase.ganderoceanic.ca/nattrak/receiving-your-clearance/" class="dropdown-item">Receiving Clearance</a>
                                    @can('activeController')
                                        <hr>
                                        <div class="dropdown-item small text-muted" style="cursor: default">Controllers</div>
                                        <a target="_blank" href="https://knowledgebase.ganderoceanic.ca/nattrak/pending-rcl-messages" class="dropdown-item">RCL Messages List</a>
                                        <a target="_blank" href="https://knowledgebase.ganderoceanic.ca//nattrak/issuing-clx" class="dropdown-item">Issuing Clearance</a>
                                    @endcan
                                </div>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            @auth
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="far fa-user-circle"></i> {{ Auth::user()->full_name ?? Auth::id() }} @if(Auth::user()->active_datalink_authority) ({{ Auth::user()->active_datalink_authority->name }}) @endif
                                        </a>
                                        <div class="dropdown-menu menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('auth.deauthenticate') }}">Logout <i data-feather="log-in"></i></a>
                                        </div>
                                    </li>
                                </ul>
                            @else
                                <ul class="navbar-nav ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('auth.redirect') }}">Login <i data-feather="log-in"></i></a>
                                    </li>
                                </ul>
                            @endauth
                            <li class="nav-item">
                                <div class="nav-link"><i class="far fa-calendar"></i> TMI {{ current_tmi() }}</div>
                            </li>
                        </ul>
                    </nav>
                    <br />
                    <br />
                    @yield('page')
                </div>
            </div>
        </div>
    </div>
    <footer class="page-footer font-small" style="margin-top: 3em; font-size: 0.7em;">
        <div class="footer-copyright text-center py-3 text-light">
            Developed by Liesel D, 1364284
        </div>
    </footer>
</div>
@toastr_js
@toastr_render
@livewireScripts
</body>
</html>
