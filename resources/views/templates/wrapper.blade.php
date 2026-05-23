<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name', 'Kroxy') }}</title>

        @section('meta')
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta name="robots" content="noindex">
            <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
            <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
            <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
            <link rel="manifest" href="/favicons/manifest.json">
            <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#ffffff">
            <link rel="shortcut icon" href="/favicons/favicon.ico">
            <meta name="msapplication-config" content="/favicons/browserconfig.xml">
            <meta name="theme-color" content="#0a0a0a">
        @show

        @section('user-data')
            @if(!is_null(Auth::user()))
                <script>
                    window.PterodactylUser = {!! json_encode(Auth::user()->toVueObject()) !!};
                </script>
            @endif
            @if(!empty($siteConfiguration))
                <script>
                    window.SiteConfiguration = {!! json_encode($siteConfiguration) !!};
                </script>
            @endif
        @show

        @yield('assets')

        @include('layouts.scripts')

        <style>
            /* Kroxy Loading Screen */
            #kroxy-loader {
                position: fixed; inset: 0;
                background: #0a0a0a;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                transition: opacity 0.5s ease, visibility 0.5s ease;
            }
            #kroxy-loader.loaded {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
            .kroxy-loader-logo {
                font-family: 'IBM Plex Sans', 'Roboto', system-ui, sans-serif;
                font-size: 2.2rem;
                font-weight: 700;
                color: #ffffff;
                letter-spacing: 0.1em;
                margin-bottom: 32px;
                animation: kroxy-pulse 2s ease-in-out infinite;
            }
            .kroxy-loader-bar-track {
                width: 200px;
                height: 2px;
                background: rgba(255,255,255,0.08);
                border-radius: 2px;
                overflow: hidden;
                margin-bottom: 18px;
            }
            .kroxy-loader-bar {
                height: 100%;
                background: #ffffff;
                border-radius: 2px;
                animation: kroxy-loading 1.6s cubic-bezier(0.4,0,0.2,1) infinite;
            }
            .kroxy-loader-dots {
                display: flex;
                gap: 8px;
            }
            .kroxy-loader-dots span {
                width: 5px; height: 5px;
                background: rgba(255,255,255,0.35);
                border-radius: 50%;
                animation: kroxy-dot-pulse 1.4s ease-in-out infinite;
            }
            .kroxy-loader-dots span:nth-child(2) { animation-delay: 0.2s; }
            .kroxy-loader-dots span:nth-child(3) { animation-delay: 0.4s; }
            .kroxy-loader-powered {
                position: absolute;
                bottom: 28px;
                font-family: system-ui, sans-serif;
                font-size: 0.6rem;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: rgba(255,255,255,0.18);
            }
            @keyframes kroxy-pulse {
                0%, 100% { opacity: 1; }
                50%       { opacity: 0.6; }
            }
            @keyframes kroxy-loading {
                0%   { transform: translateX(-100%); }
                50%  { transform: translateX(0%); }
                100% { transform: translateX(100%); }
            }
            @keyframes kroxy-dot-pulse {
                0%, 80%, 100% { transform: scale(1);   opacity: 0.35; }
                40%           { transform: scale(1.5); opacity: 1; }
            }
        </style>
    </head>
    <body class="{{ $css['body'] ?? 'bg-neutral-900' }}" style="background:#0a0a0a;">

        <!-- Kroxy Loading Screen -->
        <div id="kroxy-loader">
            <div class="kroxy-loader-logo">Kroxy</div>
            <div class="kroxy-loader-bar-track">
                <div class="kroxy-loader-bar"></div>
            </div>
            <div class="kroxy-loader-dots">
                <span></span><span></span><span></span>
            </div>
            <div class="kroxy-loader-powered">Powered by Pterodactyl Panel</div>
        </div>

        @section('content')
            @yield('above-container')
            @yield('container')
            @yield('below-container')
        @show

        @section('scripts')
            {!! $asset->js('main.js') !!}
        @show

        <script>
            // Hide loader once React has mounted
            (function() {
                var loader = document.getElementById('kroxy-loader');
                var app = document.getElementById('app');
                var attempts = 0;
                var check = setInterval(function() {
                    attempts++;
                    if ((app && app.children.length > 0) || attempts > 60) {
                        clearInterval(check);
                        if (loader) loader.classList.add('loaded');
                    }
                }, 100);
            })();
        </script>
    </body>
</html>
