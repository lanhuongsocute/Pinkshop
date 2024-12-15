<!DOCTYPE html>
<html lang="en">

<head>
   @include('frontend.layouts.head')

</head>

<body class="theme-color-10" style="over-flow:auto">


    <!-- loader start -->
   
    <!-- loader end -->


    <!-- header start -->
    @include('frontend.layouts.header')
    <!-- header end -->
    <!-- breadcrumb -->
   
    <!-- breadcrumb -->
<!-- error display -->
        <div>
        @if(session('success'))
        <div class="alert alert-primary alert-dismissible show flex items-center mb-2" role="alert"> 
            <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i> 
            {{session('success')}}
            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
                <i data-lucide="x" class="w-4 h-4"> </i> 
            </button> 
        </div>
    
    @endif
         @if(session('error'))
            <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert"> 
                <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> 
                {{session('error')}}
                <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
                    <i data-lucide="x" class="w-4 h-4"></i> 
                </button> 
            </div>
            
            @endif
    
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                        @foreach ($errors->all() as $error)
                            <li>    {{$error}} </li>
                        @endforeach
                </ul>
            </div>
            @endif
             
    </div>
<!-- error display -->
    @yield('content')
    


    <!-- footer section start -->
    @include('frontend.layouts.footer')
    <!-- footer section end -->


    <!--modal popup start-->
    <!-- include('frontend.layouts.home_popup') -->
    <!--modal popup end-->


    <!-- Quick-view modal popup start-->
    @include('frontend.layouts.popup_quickview')
    <!-- Quick-view modal popup end-->

 

    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="fa fa-angle-double-up"></i>
        </div>
    </div>
    <!-- tap to top end -->
    @include('frontend.layouts.foot')
  
    @yield('scripts')

</body>

</html>