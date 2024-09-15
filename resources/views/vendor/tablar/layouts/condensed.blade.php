@php
    $stickyTopClass = config('tablar.sticky_top_nav_bar') ? 'sticky-top' : '';
    $layoutData['cssClasses'] = 'navbar navbar-expand-md ' . $stickyTopClass . ' d-print-none';
@endphp
@section('body')
    <body>
    <div class="page">
        <!-- Top Navbar -->
        @include('tablar::partials.navbar.condensed-top')
        <div class="page-wrapper">
            <!-- Page Content -->
            @hasSection('content')
                @yield('content')
            @endif
            <!-- Page Error -->
            @include('tablar::error')
            @include('tablar::partials.footer.bottom')
        </div>
    </div>
    </body>
@endsection
