<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ config('app.name', 'Kroxy') }} &mdash; @yield('title')</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="_token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/favicons/manifest.json">
        <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#ffffff">
        <link rel="shortcut icon" href="/favicons/favicon.ico">
        <meta name="msapplication-config" content="/favicons/browserconfig.xml">
        <meta name="theme-color" content="#0a0a0a">

        @include('layouts.scripts')

        @section('scripts')
            {!! Theme::css('vendor/select2/select2.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/bootstrap/bootstrap.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/adminlte/admin.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/adminlte/colors/skin-blue.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/sweetalert/sweetalert.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/animate/animate.min.css?t={cache-version}') !!}
            {!! Theme::css('css/pterodactyl.css?t={cache-version}') !!}
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        @show

        <style>
            /* =============================================
               KROXY ADMIN — Premium Black & White Theme
               ============================================= */
            * { box-sizing: border-box; }

            /* Loading overlay */
            #kroxy-admin-loader {
                position: fixed; inset: 0;
                background: #080808;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                z-index: 99999;
                transition: opacity 0.5s ease, visibility 0.5s ease;
            }
            #kroxy-admin-loader.loaded { opacity: 0; visibility: hidden; pointer-events: none; }
            .kal-logo {
                font-family: 'IBM Plex Sans', Arial, sans-serif;
                font-size: 2rem; font-weight: 700;
                color: #fff; letter-spacing: 0.1em;
                margin-bottom: 28px;
                animation: kal-pulse 2s ease-in-out infinite;
            }
            .kal-bar-track { width: 180px; height: 2px; background: rgba(255,255,255,0.08); border-radius: 2px; overflow: hidden; margin-bottom: 16px; }
            .kal-bar { height: 100%; background: #fff; animation: kal-anim 1.5s cubic-bezier(0.4,0,0.2,1) infinite; }
            .kal-dots { display: flex; gap: 7px; }
            .kal-dots span { width: 5px; height: 5px; background: rgba(255,255,255,0.35); border-radius: 50%; animation: kal-dot 1.4s ease-in-out infinite; }
            .kal-dots span:nth-child(2) { animation-delay: 0.2s; }
            .kal-dots span:nth-child(3) { animation-delay: 0.4s; }
            .kal-powered { position: absolute; bottom: 24px; font-size: 0.55rem; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(255,255,255,0.15); }
            @keyframes kal-pulse { 0%,100%{opacity:1} 50%{opacity:0.55} }
            @keyframes kal-anim  { 0%{transform:translateX(-100%)} 50%{transform:translateX(0)} 100%{transform:translateX(100%)} }
            @keyframes kal-dot   { 0%,80%,100%{transform:scale(1);opacity:.35} 40%{transform:scale(1.5);opacity:1} }

            /* Base */
            html, body { background: #0a0a0a !important; color: #e0e0e0 !important; }

            /* Top navbar */
            .main-header { background: #0a0a0a !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; box-shadow: 0 1px 20px rgba(0,0,0,0.6) !important; }
            .main-header .logo {
                background: #000 !important;
                border-right: 1px solid rgba(255,255,255,0.07) !important;
                color: #fff !important;
                font-weight: 700 !important;
                letter-spacing: 0.08em !important;
                font-size: 1.1rem !important;
            }
            .main-header .logo:hover { background: #111 !important; }
            .navbar-static-top { background: #0a0a0a !important; }
            .navbar-static-top .navbar-nav > li > a { color: rgba(255,255,255,0.5) !important; transition: color .15s; }
            .navbar-static-top .navbar-nav > li > a:hover { color: #fff !important; background: rgba(255,255,255,0.05) !important; }

            /* Sidebar */
            .main-sidebar { background: #0d0d0d !important; border-right: 1px solid rgba(255,255,255,0.07) !important; }
            .sidebar-menu > li > a { color: rgba(255,255,255,0.45) !important; transition: color .15s, background .15s; border-left: 3px solid transparent !important; }
            .sidebar-menu > li > a:hover,
            .sidebar-menu > li.active > a { color: #fff !important; background: rgba(255,255,255,0.05) !important; border-left-color: rgba(255,255,255,0.6) !important; }
            .sidebar-menu > li.header { color: rgba(255,255,255,0.2) !important; background: transparent !important; font-size: 0.6rem !important; letter-spacing: 0.14em !important; padding: 14px 16px 6px !important; }
            .sidebar-menu > li > a > .fa { color: rgba(255,255,255,0.35) !important; }
            .sidebar-menu > li.active > a > .fa,
            .sidebar-menu > li > a:hover > .fa { color: rgba(255,255,255,0.8) !important; }

            /* Content wrapper */
            .content-wrapper { background: #0a0a0a !important; }
            .content-header { border-bottom: 1px solid rgba(255,255,255,0.06) !important; padding: 12px 20px !important; }
            .content-header h1 { color: #fff !important; font-weight: 600 !important; font-size: 1.2rem !important; }
            .content-header .breadcrumb { background: transparent !important; color: rgba(255,255,255,0.3) !important; }
            .content-header .breadcrumb > li + li::before { color: rgba(255,255,255,0.2) !important; }
            .content-header .breadcrumb a { color: rgba(255,255,255,0.5) !important; }

            /* Cards / boxes */
            .box { background: #111 !important; border: 1px solid rgba(255,255,255,0.07) !important; border-top: none !important; border-radius: 4px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.4) !important; }
            .box-header { background: #161616 !important; border-bottom: 1px solid rgba(255,255,255,0.07) !important; border-radius: 4px 4px 0 0 !important; }
            .box-header .box-title { color: #fff !important; font-weight: 600 !important; font-size: 0.85rem !important; letter-spacing: 0.03em; }
            .box-footer { background: #111 !important; border-top: 1px solid rgba(255,255,255,0.07) !important; }
            .box-primary { border-top-color: rgba(255,255,255,0.3) !important; }
            .box-info    { border-top-color: rgba(255,255,255,0.2) !important; }
            .box-success { border-top-color: rgba(255,255,255,0.25) !important; }
            .box-warning { border-top-color: rgba(200,200,200,0.3) !important; }
            .box-danger  { border-top-color: rgba(150,150,150,0.4) !important; }

            /* Tables */
            .table { color: #d0d0d0 !important; }
            .table > thead > tr > th { background: #161616 !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; color: rgba(255,255,255,0.6) !important; font-size: 0.7rem !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
            .table-striped > tbody > tr:nth-of-type(odd) { background: rgba(255,255,255,0.02) !important; }
            .table > tbody > tr > td { border-top: 1px solid rgba(255,255,255,0.05) !important; color: rgba(255,255,255,0.7) !important; }
            .table > tbody > tr:hover > td { background: rgba(255,255,255,0.03) !important; color: #fff !important; }
            .table-hover > tbody > tr:hover { background: rgba(255,255,255,0.03) !important; }

            /* Forms */
            .form-control {
                background: #161616 !important;
                border: 1px solid rgba(255,255,255,0.12) !important;
                color: #e0e0e0 !important;
                border-radius: 3px !important;
                transition: border-color .15s;
            }
            .form-control:focus { border-color: rgba(255,255,255,0.35) !important; box-shadow: 0 0 0 2px rgba(255,255,255,0.05) !important; background: #1a1a1a !important; }
            .form-control::placeholder { color: rgba(255,255,255,0.2) !important; }
            label { color: rgba(255,255,255,0.65) !important; font-size: 0.8rem !important; font-weight: 500 !important; }
            .help-block { color: rgba(255,255,255,0.3) !important; font-size: 0.75rem !important; }

            /* Buttons */
            .btn-default { background: #1a1a1a !important; border: 1px solid rgba(255,255,255,0.15) !important; color: rgba(255,255,255,0.7) !important; }
            .btn-default:hover { background: #222 !important; border-color: rgba(255,255,255,0.3) !important; color: #fff !important; }
            .btn-primary { background: #fff !important; border-color: #fff !important; color: #000 !important; font-weight: 600 !important; }
            .btn-primary:hover { background: #e0e0e0 !important; border-color: #e0e0e0 !important; color: #000 !important; }
            .btn-success { background: rgba(255,255,255,0.85) !important; border-color: rgba(255,255,255,0.85) !important; color: #000 !important; font-weight: 600 !important; }
            .btn-success:hover { background: #fff !important; color: #000 !important; }
            .btn-danger  { background: #222 !important; border: 1px solid rgba(255,100,100,0.3) !important; color: rgba(255,180,180,0.8) !important; }
            .btn-danger:hover { background: rgba(255,100,100,0.1) !important; color: rgba(255,200,200,1) !important; }

            /* Alerts */
            .alert { border-radius: 4px !important; border: 1px solid rgba(255,255,255,0.1) !important; background: #151515 !important; color: rgba(255,255,255,0.75) !important; }
            .alert-success { border-color: rgba(255,255,255,0.15) !important; background: rgba(255,255,255,0.04) !important; }
            .alert-danger  { border-color: rgba(255,100,100,0.2) !important; background: rgba(255,50,50,0.04) !important; color: rgba(255,200,200,0.9) !important; }
            .alert-info    { border-color: rgba(255,255,255,0.1) !important; }

            /* Badges + labels */
            .label-primary, .label-info { background: rgba(255,255,255,0.15) !important; color: #fff !important; }
            .label-success { background: rgba(255,255,255,0.12) !important; color: #c8ffc8 !important; }
            .label-danger  { background: rgba(255,80,80,0.15) !important; color: #ffb0b0 !important; }

            /* Select2 */
            .select2-container--default .select2-selection--single,
            .select2-container--default .select2-selection--multiple {
                background: #161616 !important; border: 1px solid rgba(255,255,255,0.12) !important; color: #e0e0e0 !important;
            }
            .select2-dropdown { background: #161616 !important; border: 1px solid rgba(255,255,255,0.12) !important; }
            .select2-container--default .select2-results__option { color: rgba(255,255,255,0.7) !important; }
            .select2-container--default .select2-results__option--highlighted { background: rgba(255,255,255,0.08) !important; color: #fff !important; }

            /* Footer */
            .main-footer {
                background: #0a0a0a !important;
                border-top: 1px solid rgba(255,255,255,0.06) !important;
                color: rgba(255,255,255,0.2) !important;
                font-size: 0.75rem !important;
            }
            .main-footer a { color: rgba(255,255,255,0.3) !important; }
            .main-footer a:hover { color: rgba(255,255,255,0.6) !important; }

            /* Pagination */
            .pagination > li > a, .pagination > li > span { background: #161616 !important; border-color: rgba(255,255,255,0.1) !important; color: rgba(255,255,255,0.6) !important; }
            .pagination > li > a:hover { background: #222 !important; color: #fff !important; }
            .pagination > .active > a { background: #fff !important; border-color: #fff !important; color: #000 !important; }

            /* Nav tabs */
            .nav-tabs { border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
            .nav-tabs > li > a { color: rgba(255,255,255,0.45) !important; border: 1px solid transparent !important; }
            .nav-tabs > li > a:hover { color: rgba(255,255,255,0.8) !important; background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.1) !important; }
            .nav-tabs > li.active > a { background: #111 !important; border: 1px solid rgba(255,255,255,0.1) !important; border-bottom-color: #111 !important; color: #fff !important; }

            /* User avatar circle */
            .user-image { border: 2px solid rgba(255,255,255,0.15) !important; }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 8px; height: 8px; background: transparent; }
            ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
        </style>
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini" style="background:#0a0a0a;">

        <!-- Kroxy Admin Loading Screen -->
        <div id="kroxy-admin-loader">
            <div class="kal-logo">Kroxy</div>
            <div class="kal-bar-track"><div class="kal-bar"></div></div>
            <div class="kal-dots"><span></span><span></span><span></span></div>
            <div class="kal-powered">Powered by Pterodactyl Panel</div>
        </div>

        <div class="wrapper">
            <header class="main-header">
                <a href="{{ route('index') }}" class="logo">
                    <span>Kroxy</span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="user-menu">
                                <a href="{{ route('account') }}">
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(Auth::user()->email)) }}?s=160" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ Auth::user()->name_first }} {{ Auth::user()->name_last }}</span>
                                </a>
                            </li>
                            <li>
                                <li><a href="{{ route('index') }}" data-toggle="tooltip" data-placement="bottom" title="Exit Admin Control"><i class="fa fa-server"></i></a></li>
                            </li>
                            <li>
                                <li><a href="{{ route('auth.logout') }}" id="logoutButton" data-toggle="tooltip" data-placement="bottom" title="Logout"><i class="fa fa-sign-out"></i></a></li>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="header">Basic Administration</li>
                        <li class="{{ Route::currentRouteName() !== 'admin.index' ?: 'active' }}">
                            <a href="{{ route('admin.index') }}">
                                <i class="fa fa-home"></i> <span>Overview</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.settings') ?: 'active' }}">
                            <a href="{{ route('admin.settings')}}">
                                <i class="fa fa-wrench"></i> <span>Settings</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.api') ?: 'active' }}">
                            <a href="{{ route('admin.api.index')}}">
                                <i class="fa fa-gamepad"></i> <span>Application API</span>
                            </a>
                        </li>
                        <li class="header">Management</li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.databases') ?: 'active' }}">
                            <a href="{{ route('admin.databases') }}">
                                <i class="fa fa-database"></i> <span>Databases</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.locations') ?: 'active' }}">
                            <a href="{{ route('admin.locations') }}">
                                <i class="fa fa-globe"></i> <span>Locations</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.nodes') ?: 'active' }}">
                            <a href="{{ route('admin.nodes') }}">
                                <i class="fa fa-sitemap"></i> <span>Nodes</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.servers') ?: 'active' }}">
                            <a href="{{ route('admin.servers') }}">
                                <i class="fa fa-server"></i> <span>Servers</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.users') ?: 'active' }}">
                            <a href="{{ route('admin.users') }}">
                                <i class="fa fa-users"></i> <span>Users</span>
                            </a>
                        </li>
                        <li class="header">Service Management</li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.mounts') ?: 'active' }}">
                            <a href="{{ route('admin.mounts') }}">
                                <i class="fa fa-magic"></i> <span>Mounts</span>
                            </a>
                        </li>
                        <li class="{{ ! starts_with(Route::currentRouteName(), 'admin.nests') ?: 'active' }}">
                            <a href="{{ route('admin.nests') }}">
                                <i class="fa fa-th-large"></i> <span>Nests</span>
                            </a>
                        </li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    @yield('content-header')
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    There was an error validating the data provided.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @foreach (Alert::getMessages() as $type => $messages)
                                @foreach ($messages as $message)
                                    <div class="alert alert-{{ $type }} alert-dismissable" role="alert">
                                        {{ $message }}
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    @yield('content')
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right small" style="margin-right:10px;margin-top:-7px;color:rgba(255,255,255,0.2);">
                    <strong><i class="fa fa-fw {{ $appIsGit ? 'fa-git-square' : 'fa-code-fork' }}"></i></strong> {{ $appVersion }}<br />
                    <strong><i class="fa fa-fw fa-clock-o"></i></strong> {{ round(microtime(true) - LARAVEL_START, 3) }}s
                </div>
                <span style="color:rgba(255,255,255,0.18);font-size:0.75rem;">
                    Kroxy &mdash; Powered by <a href="https://pterodactyl.io/" style="color:rgba(255,255,255,0.3);">Pterodactyl</a>
                    &copy; 2015&ndash;{{ date('Y') }}
                </span>
            </footer>
        </div>

        @section('footer-scripts')
            <script src="/js/keyboard.polyfill.js" type="application/javascript"></script>
            <script>keyboardeventKeyPolyfill.polyfill();</script>

            {!! Theme::js('vendor/jquery/jquery.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/sweetalert/sweetalert.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/bootstrap/bootstrap.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/slimscroll/jquery.slimscroll.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/adminlte/app.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/bootstrap-notify/bootstrap-notify.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/select2/select2.full.min.js?t={cache-version}') !!}
            {!! Theme::js('js/admin/functions.js?t={cache-version}') !!}
            <script src="/js/autocomplete.js" type="application/javascript"></script>

            @if(Auth::user()->root_admin)
                <script>
                    $('#logoutButton').on('click', function (event) {
                        event.preventDefault();
                        swal({
                            title: 'Do you want to log out?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ffffff',
                            cancelButtonColor: '#444',
                            confirmButtonText: 'Log out'
                        }, function () {
                             $.ajax({
                                type: 'POST',
                                url: '{{ route('auth.logout') }}',
                                data: { _token: '{{ csrf_token() }}' },
                                complete: function () {
                                    window.location.href = '{{route('auth.login')}}';
                                }
                            });
                        });
                    });
                </script>
            @endif

            <script>
                $(function () { $('[data-toggle="tooltip"]').tooltip(); });
                // Hide loader
                $(window).on('load', function() {
                    setTimeout(function() {
                        $('#kroxy-admin-loader').addClass('loaded');
                    }, 400);
                });
            </script>
        @show
    </body>
</html>
