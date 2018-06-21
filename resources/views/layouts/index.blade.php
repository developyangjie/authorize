<!DOCTYPE html>
<html>
<head>
    @include('layouts.meta')
    @section('title')
     <title>365学堂</title>
    @show
    @include('layouts.header')
    @yield('style')
</head>

<body class="">
    <div id="wrapper">
        @include('layouts.nav')
        <div id="page-wrapper" class="gray-bg">
            @include('layouts.top')
                @yield('content')
            @include('layouts.foot')
        </div>
    </div>
@include('layouts.script')
@yield('script')
</body>
</html>