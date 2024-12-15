@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')


<section class="section-b-space blog-page ratio2_3">
        <div class="container">
            <!-- starrt categories -->
        <div class="row">
            @if (count ($blogcategories) > 0)
                <div class="container" style=" margin-bottom:30px">
                    <div class="row margin-default ratio_square  ">
                        @foreach ($blogcategories as $subcat )
                            <div class="col-xl-2 col-sm-3 col-4">
                                <a href="{{route('front.category.view',$subcat->slug)}}">
                                    <div class="img-category">
                                        <div style="max-height:120px" class="img-sec">
                                            <img   src="{{$subcat->photo?$subcat->photo:asset('frontend/assets/images/fashion/category/1.jpg')}}" class="img-fluid bg-img" alt="">
                                        </div>
                                        <h4>{{$subcat->title}}</h4>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            </div>
            <!-- end categories -->
            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-7">
                    @foreach ($blogs as $blog)
                        <div class="row blog-media">
                            <div class="col-xl-6">
                                <div class="blog-left">
                                    <a href="{{route('front.page.view',$blog->slug)}}"><img src="{{$blog->photo?$blog->photo: asset('frontend/assets/images/blog/1.jpg')}}"
                                            class="img-fluid blur-up lazyload bg-img" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="blog-right">
                                    <div>
                                        <h6>{{$blog->created_at}}</h6> <a href="{{route('front.page.view',$blog->slug)}}">
                                            <h4>{{$blog->title}}</h4>
                                        </a>
                                        <ul class="post-social">
                                            <?php
                                                $author = \App\Models\User::find($blog->user_id);
                                             ?>   
                                            <li>Viết bởi : {{$author?$author->full_name:''}}</li>
                                            <li><i class="fa fa-view"></i> {{$blog->hit}} lần xem</li>
                                             
                                        </ul>
                                        
                                            <?php echo $blog->summary?>
                                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- BEGIN: Pagination -->
                        
                    <div class="product-pagination">
                        <div class="theme-paggination-block">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                <nav  aria-label="Page navigation">
                                    {{$blogs->links('vendor.pagination.simple-new')}}
                                </nav>
                                    
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="product-search-count-bottom">
                                        <h5>Bài viết từ {{($blogs->currentPage()-1)*$blogs->perPage() + 1}}-{{($blogs->currentPage())*$blogs->perPage()}} trong tổng số {{$blogs->total()}} </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- left bar -->
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="blog-sidebar">
                        <!-- first blog -->
                        <div class="theme-card" style="padding-left:10px !important">
                            <h4>Bài viết mới</h4>
                            <ul class="recent-blog">
                                @foreach ($newblogs as $newblog )
                                <li>
                                    <a href="{{route('front.page.view',$newblog->slug)}}">
                                        <div class="media"> <img class="img-fluid blur-up lazyload"
                                                src="{{$newblog->photo?$newblog->photo: asset('frontend/assets/images/blog/2.jpg')}}" alt="Generic placeholder image">
                                            <div class="media-body align-self-center">
                                                <h6>{{$newblog->title}}</h6>
                                                <p>{{$newblog->hit}} <i class="fa fa-eye"></i></p>
                                            </div>
                                        </div>
                                    </a>
                                </li> 
                                @endforeach
                               
                               
                            </ul>
                        </div>
                         <!-- second blog -->
                        <div class="theme-card" style="padding-left:10px !important">
                            <h4>Bài viết nổi bật</h4>
                            <ul class="popular-blog">
                                @foreach ($popblogs as $popblog)
                                <a href="{{route('front.page.view',$popblog->slug)}}">
                                    <li>
                                        <div class="media">
                                            <div class="blog-date"><span>{{substr($popblog->created_at, 0,4)}}</span>
                                                <span>Tháng {{substr($popblog->created_at, 5,2)}}</span>
                                        </div>
                                            <div class="media-body align-self-center">
                                                <h6>{{$popblog->title}}</h6>
                                                <p>{{$popblog->hit}} <i class="fa fa-eye"></i></p>
                                            </div>
                                        </div>
                                        
                                    </li>
                                </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
@section('scripts')
@endsection