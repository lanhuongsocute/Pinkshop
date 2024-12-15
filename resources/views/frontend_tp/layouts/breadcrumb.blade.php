<div class="wrapper  bg-[rgba(246,247,249,1)] ">
      <div class="container py-3 xl:!py-5 lg:!py-5 md:!py-5">
       
          <nav class="inline-block" aria-label="breadcrumb">
          <ol class="breadcrumb  flex flex-wrap bg-[none] p-0 !rounded-none list-none !mb-0">
            <li class="breadcrumb-item flex text-[#60697b]"><a class=" text-inherit nav_color" href="{{route('home')}}">Trang chủ</a></li>
            @foreach ($links as $link )
            <li class="breadcrumb-item flex text-[#60697b] pl-2 before:font-normal before:flex before:items-center before:text-[rgba(96,105,123,0.35)] before:content-['\e931'] before:text-[0.9rem] before:-mt-px before:pr-2 before:font-Unicons active" aria-current="page">
              @if ($link->url != '#')
              <a class=" text-inherit nav_color" href="{{$link->url}}">
              @endif
                {{$link->title}}
              @if ($link->url != '#')
              </a>
              @endif
              
            </li>
            @endforeach
           
          </ol>
        </nav>  
        <!-- /nav -->
      </div>
      <!-- /.container -->
    </div>