<?php
 
    $setting =\App\Models\SettingDetail::find(1);
    $user = auth()->user();
    if($user)
    {
        $sql  = "select c.quantity, d.* from (SELECT * from shoping_carts where user_id = "
        .$user->id.") as c left join products as d on c.product_id = d.id where d.status = 'active'  ";
        $pro_carts =   \DB::select($sql ) ;
    }
    else
    {
        $pro_carts = [];
    }
    $cart_size= count($pro_carts);
?>
@extends('frontend_tp.layouts.master')
@section('head_css')
@endsection
@section('content')
@include('frontend_tp.layouts.breadcrumb')
<div class="wrapper !bg-[#ffffff]">
    <div class="container py-[1.5rem]   md:!py-24">
        <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
            <div class="xl:w-8/12 lg:w-8/12 w-full flex-[0_0_auto] px-[15px] max-w-full md:px-[20px] lg:px-[20px] xl:px-[35px]">
                <!-- /.blog -->
                <div class="blog itemgrid grid-view">
                    <div class="flex flex-wrap mx-[-15px] isotope xl:mx-[-20px] lg:mx-[-20px] md:mx-[-20px] mt-[-5px] !mb-8">
                        
                        @foreach ($blogs as $blog)
                            <article class="item post xl:w-6/12 lg:w-6/12 md:w-6/12 w-full flex-[0_0_auto] xl:px-[10px] lg:px-[10px] md:px-[10px] mt-[10px] px-[10px] max-w-full">
                                <div class="card">
                                    <figure class="card-img-top overlay overlay-1 hover-scale group">
                                        <a href="{{route('front.page.view',$blog->slug)}}"> 
                                            <img class="!transition-all !duration-[0.35s] !ease-in-out group-hover:scale-105" src="{{$blog->photo}}" alt="{{$blog->title}}">
                                        </a>
                                        <figcaption class="group-hover:opacity-100 absolute w-full h-full opacity-0 text-center px-4 py-3 inset-0 z-[5] pointer-events-none p-2">
                                            <h5 class="from-top  !mb-0 absolute w-full translate-y-[-80%] p-[.75rem_1rem] left-0 top-2/4">Xem thêm</h5>
                                        </figcaption>
                                    </figure>
                                    <div class="  card-body flex-[1_1_auto] p-[10px] xl:px-[20px] lg:px-[20px] md:px-[20px]  sm:pb-4 xsm:pb-4  ">
                                        <?php
                                            $cat = \App\Models\BlogCategory::find($blog->cat_id);
                                        ?>
                                        <div class="post-header !mb-[.9rem] !mt-4 ">
                                            <div class="inline-flex mb-[.4rem] uppercase tracking-[0.02rem] text-[0.7rem] font-bold primarytextcolor relative align-top pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4 leading_title_color">
                                                @if ($cat)
                                                <a href="{{route('front.category.view',$cat->slug)}}" class="hover" rel="category">{{$cat->title}}</a>
                                                @endif  
                                            </div>
                                            <!-- /.post-category -->
                                            <h2 class="post-title h3 !mt-1 !mb-3"><a class="title_color" href="{{route('front.page.view',$blog->slug)}}">{{$blog->title}}</a></h2>
                                        </div>
                                        <!-- /.post-header -->
                                        <div class="!relative">
                                            <p>{{$blog->summary}}</p>
                                        </div>
                                        <!-- /.post-content -->
                                    </div>
                                    <!--/.card-body -->
                                    
                                    <!-- /.card-footer -->
                                </div>
                            </article>
                        @endforeach
                        <!-- /.post -->
                    </div>
                <!-- /.row -->
                </div>
                <!-- /.blog -->
                <nav class="flex" aria-label="pagination">
                    {{$blogs->links('vendor.pagination.simple-new')}}
                    <!-- /.pagination -->
                </nav>
                <!-- /nav -->
            </div>
          <!-- /column -->
            <aside class="xl:w-4/12 lg:w-4/12 w-full flex-[0_0_auto] xl:px-[15px] lg:px-[15px] px-[15px] max-w-full sidebar mt-0 xl:!mt-0 lg:!mt-0">
                <div class="widget">
                    <form method="post" action="{{route('front.page.search')}}" class="search-form relative before:content-['\eca5'] before:block before:absolute before:-translate-y-2/4 before:text-[0.9rem] before:text-[#959ca9] before:z-[9] before:right-3 before:top-2/4 font-Unicons">
                        @csrf
                        <div class="form-floating relative !mb-0">
                        <input name="searchdata" id="search-form" type="text" class="form-control relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" placeholder="Search">
                        <label for="search-form" class="inline-block text-[#959ca9] text-[.75rem] absolute z-[2] h-full overflow-hidden text-start text-ellipsis whitespace-nowrap pointer-events-none border origin-[0_0] px-4 py-[0.6rem] border-solid border-transparent left-0 top-0 font-Manrope
                        ">Tìm kiếm bài viết</label>
                        </div>
                    </form> 
                </div>
                <!-- /.widget -->
                
                <div class="widget mt-[40px]">
                <h4 class="widget-title !mb-3">Bài viết mới</h4>
                <ul class="m-0 p-0 after:content-[''] after:block after:h-0 after:clear-both after:invisible">
                    @foreach ($newblogs as $newblog )
                    <li class=" clear-both block overflow-hidden">
                        <figure class="!rounded-[.4rem] float-left w-14 !h-[4.5rem]">
                            <a href="{{route('front.page.view',$newblog->slug)}}" >
                                <img class="!rounded-[.4rem]" src="{{$newblog->photo}}" alt="{{$newblog->title}}">
                            </a>
                        </figure>
                    <div class="!relative ml-[4.25rem] mb-0">
                        <h6 class="!mb-2"> <a class="title_color" href="{{route('front.page.view',$newblog->slug)}}">{{$newblog->title}}</a> </h6>
                        <ul class="text-[0.7rem] primarytextcolor m-0 p-0 list-none">
                        <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>{{substr($newblog->created_at,0,10)}}</span></li>
                        </ul>
                        <!-- /.post-meta -->
                    </div>
                    </li>
                    @endforeach
                </ul>
                <!-- /.image-list -->
                </div>
                <!-- /.widget -->
                <div class="widget mt-[40px]">
                <h4 class="widget-title !mb-3">Bài viết nổi bật</h4>
                <ul class="m-0 p-0 after:content-[''] after:block after:h-0 after:clear-both after:invisible">
                    @foreach ($popblogs as $newblog )
                    <li class=" clear-both block overflow-hidden">
                        <figure class="!rounded-[.4rem] float-left w-14 !h-[4.5rem]">
                            <a href="{{route('front.page.view',$newblog->slug)}}" >
                                <img class="!rounded-[.4rem]" src="{{$newblog->photo}}" alt="{{$newblog->title}}">
                            </a>
                        </figure>
                    <div class="!relative ml-[4.25rem] mb-0">
                        <h6 class="!mb-2"> <a class="title_color" href="{{route('front.page.view',$newblog->slug)}}">{{$newblog->title}}</a> </h6>
                        <ul class="text-[0.7rem] primarytextcolor m-0 p-0 list-none">
                        <li class="post-date inline-block"><i class="uil uil-calendar-alt pr-[0.2rem] align-[-.05rem] before:content-['\e9ba']"></i><span>{{substr($newblog->created_at,0,10)}}</span></li>
                        </ul>
                        <!-- /.post-meta -->
                    </div>
                    </li>
                    @endforeach
                </ul>
                <!-- /.image-list -->
                </div>
                <!-- /.widget -->
               @include('frontend_tp.layouts.blogcatmenu')
                <!-- /.widget -->
                @if(isset($tags))

                <div class="widget mt-[40px]">
                    <h4 class="widget-title !mb-3">Tags</h4>
                    <ul class="pl-0 list-none tag-list">
                        @foreach ($tags as $tag )
                            <span class=" mt-0 mb-[0.45rem] mr-[0.2rem] inline-block">
                                <a href="{{route('front.tag.view',$tag->slug)}}" class="btn btn-soft-ash btn-sm !rounded-[50rem] flex items-center hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,.05)] before:not-italic   ">
                                    {{$tag->title}}
                                </a>
                            </span>
                        @endforeach
                    </ul>
                </div>
                @endif
                <!-- /.widget -->
             
            </aside>
          <!-- /column .sidebar -->
        </div>
        <!-- /.row -->
    </div>
    
      <!-- /.container -->
</div>
    
@endsection
@section('scripts')
@endsection