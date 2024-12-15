@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')
<section class="section-b-space blog-page ratio2_3">
        <div class="container blog-container">
            <div class="row">
                <div class="col-xl-9 col-lg-8 col-md-7 ">
                    <div class="blog-detail">
                        <!-- <h3>{{$blog->title}}</h3> -->
                    
                        <?php 
                        echo $blog->content;
                        ?>
                    </div>

                    <div class="row"> 
                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <?php
                                if($preblog && count ($preblog) > 0)
                                {
                                echo '  <h5>  <a class=" btn-solid me-3 page-link"
                                href="'.route('front.page.view',$preblog[0]->slug).'"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;'.$preblog[0]->title.'</a></h5> ';
                                }
                                ?>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                                <?php
                                if($nextblog && count ($nextblog) > 0)
                                {
                                echo '  <a class="btn-solid me-3 page-link"
                                href="'.route('front.page.view',$nextblog[0]->slug).'">'.$nextblog[0]->title.' &nbsp;&nbsp;<i class="fa fa-chevron-right"
                                aria-hidden="true"></i></a> ';
                                }
                            ?>
                        </div>
                    </div>
                    <!-- tags -->
                    @include('frontend.layouts.tag')
                   <!-- end tags -->
                    @include('frontend.layouts.comment')
                    @include('frontend.layouts.comment_form')
                </div>
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="blog-sidebar">
                        <div class="theme-card" style="padding-left:10px !important">
                            <h4>Bài viết mới</h4>
                            <ul class="recent-blog">
                                @foreach ($newblogs as $newblog )
                                <li>
                                    <a href="{{route('front.page.view',$newblog->slug)}}">
                                        <div style="margin-bottom:2px" class="media"> <img class="img-fluid blur-up lazyload"
                                                src="{{$newblog->photo?$newblog->photo: asset('frontend/assets/images/blog/2.jpg')}}" alt="{{$newblog->title}}">
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
                        <div class="theme-card" style="padding-left:10px !important">
                            <h4>Bài viết nổi bật</h4>
                            <ul class="popular-blog">
                                @foreach ($popblogs as $popblog)
                                <a href="{{route('front.page.view',$popblog->slug)}}">
                                    <li>
                                        <div style="margin-bottom:2px" class="media">
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