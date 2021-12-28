<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>@yield('seo_title') | Bigshop</title>

    <!-- Favicon  -->
    <link rel="icon" href="{{url('/themes/front/img/core-img/favicon.ico')}}">

    <!-- Style CSS -->
    <link rel="stylesheet" href="{{url('/themes/front/style.css')}}">

    @stack('head_css')
</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner-grow" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Header Area -->
    <header class="header_area">
        
        @include('front._layout.navigation')
    </header>
    <!-- Header Area End -->

    
    @yield('content')
    
    
    @include('front._layout.footer')

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="/themes/front/js/jquery.min.js"></script>
    <script src="/themes/front/js/popper.min.js"></script>
    <script src="/themes/front/js/bootstrap.min.js"></script>
    <script src="/themes/front/js/jquery.easing.min.js"></script>
    <script src="/themes/front/js/default/classy-nav.min.js"></script>
    <script src="/themes/front/js/owl.carousel.min.js"></script>
    <script src="/themes/front/js/default/scrollup.js"></script>
    <script src="/themes/front/js/default/sticky.js"></script>
    <script src="/themes/front/js/waypoints.min.js"></script>
    <script src="/themes/front/js/jquery.countdown.min.js"></script>
    <script src="/themes/front/js/jquery.counterup.min.js"></script>
    <script src="/themes/front/js/jquery-ui.min.js"></script>
    <script src="/themes/front/js/jarallax.min.js"></script>
    <script src="/themes/front/js/jarallax-video.min.js"></script>
    <script src="/themes/front/js/jquery.magnific-popup.min.js"></script>
    <script src="/themes/front/js/jquery.nice-select.min.js"></script>
    <script src="/themes/front/js/wow.min.js"></script>
    <script src="/themes/front/js/default/active.js"></script>

    @stack('footer_javascript')
    
</body>

</html>