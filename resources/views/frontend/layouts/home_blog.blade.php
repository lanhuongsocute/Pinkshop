<!-- blog section -->
<section class="blog gym-blog ratio3_2 slick-default-margin section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-basic">
                        <h2 class="title">Tin tức mới</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="slide-4 no-arrow">
                        @foreach ($blogs as $blog )
                            <div>
                                <a href="{{route('front.page.view',$blog->slug)}}">
                                    <div class="basic-effect">
                                        <div>
                                            <img src="{{$blog->photo}}"
                                                class="img-fluid blur-up lazyload bg-img" alt="{{$blog->title}}" title="{{$blog->title}}">
                                            <span></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="blog-details">
                                   
                                    <a href="{{route('front.page.view',$blog->slug)}}">
                                        <p>{{$blog->title}} </p>
                                    </a>
                                    <h6> </h6>
                                </div>
                            </div>
                            
                        @endforeach
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog section end -->