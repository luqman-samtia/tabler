<!doctype html>
<html lang="{{ Config::get('app.locale') }}" {!! config('tablar.layout') == 'rtl' ? 'dir="rtl"' : '' !!}>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Custom Meta Tags --}}
    @yield('meta_tags')
    {{-- Title --}}

            <!-- Toastr CSS -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
  .progress-container {
            margin:10px;
            width: 98%;
            background-color: #f0f0f0;
            padding: 3px;
            border-radius: 3px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
        }
        .progress-bar {
            height: 20px;
            background-color: #4CAF50;
            width: 0%;
            border-radius: 3px;
            transition: width 0.5s ease-in-out;
        }
        #progress-text {
            color: #4CAF50;
            margin-top: 10px;
            font-family: Arial, sans-serif;
            margin: 10px;
            background: black;
            width: 23%;
            border-radius: 6px;
            text-align: center;
            /* font-size: large; */
            font-weight: bold;
        }
        .modal-lg-custom {
        width: 1000px !important;
    }
    @media (min-width: 1200px) {
    .container-xl, .container-lg, .container-md, .container-sm, .container {
        max-width: 1249px !important;
    }
}
@media (min-width: 992px) {
    .modal-lg-custom {
        max-width: 1100px !important;
    }
}
    </style>
    @vite('resources/css/app.css')
    <title>
        @yield('title_prefix', config('tablar.title_prefix', ''))
        @yield('title', config('tablar.title', 'Tablar'))
        @yield('title_postfix', config('tablar.title_postfix', ''))
    </title>

    <!-- CSS/JS files -->
    @if(config('tablar','vite'))
        @vite(['resources/js/app.js'])
    @endif

    {{-- Livewire Styles --}}
    @if(config('tablar.livewire'))
        @livewireStyles
    @endif

    {{-- Custom Stylesheets (post Tablar) --}}
    @yield('tablar_css')

</head>
@yield('body')
@include('tablar::extra.modal')

{{-- Livewire Script --}}
@if(config('tablar.livewire'))
    @livewireScripts
@endif

@yield('tablar_js')
</html>
