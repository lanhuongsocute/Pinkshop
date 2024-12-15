@extends('frontend.layouts.master1')
@section('css')
    
     
@endsection
@section('content')
   <!-- Home slider -->
 
   <!-- include('frontend.layouts.homeslider') -->
    <!-- Home slider end -->


    <!-- service section start -->
    <!--  include('frontend.layouts.home_service') -->
    <!-- service section end -->


    <!-- product deal section start -->
    <!-- include('frontend.layouts.home_dealday') -->
    <!-- product deal section start -->
    @include('frontend.layouts.modpro')

    <!-- banner section start -->
     <!-- include('frontend.layouts.home_banner') -->
    <!-- banner section end -->

    <!-- slider and product -->
     <!-- include('frontend.layouts.product_slider') -->
    <!-- slider and product -->



    <!-- banner section start -->
    @include('frontend.layouts.home_banner2')
    <!-- banner section end -->


    <!-- collection banner -->
     <!-- include('frontend.layouts.home_banner3') -->
    <!-- collection banner end -->


    <!-- Tab product -->
    @include('frontend.layouts.product_tab')
    <!-- Tab product end -->
    <!--  blog section -->
     @include('frontend.layouts.home_blog')
    <!--  blog section end-->

    <!--  logo section -->
     <!-- include('frontend.layouts.home_logobrand') -->
    <!--  logo section end-->
    @if (env('DEMOAPP') == 1)
          @include(('frontend.layouts.modalpopup'))
        @endif
@endsection
@section('scripts')
<script src="{{asset('frontend/assets/js/timer.js')}}"></script>
@endsection