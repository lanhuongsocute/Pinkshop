@extends('frontend.layouts.master')
@section('css')
    
     
@endsection
@section('content')
<section class="section-b-space blog-page ratio2_3">
        <div class="container">
            <div class="row">
     
        <!-- products -->
                <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                                @if(count($products)==0)
                                                    <h5 style="margin-top:20px"> Không có sản phẩm </h5>
                                                @endif

                                                @foreach ($products as $product )
                                                <?php $photos = explode( ',', $product->photo); ?>
                                                <div class="col-xl-3 col-6 col-grid-box">
                                                    <div class="product-box">
                                                        <div class="img-wrapper">
                                                            <div class="front">
                                                                <a href="{{route('front.product.view',$product->slug)}}"><img src="{{count($photos)>0?$photos[0]:asset('frontend/assets/images/pro3/35.jpg')}}"
                                                                        class="img-fluid blur-up lazyload bg-img"
                                                                        alt="{{$product->title}}"></a>
                                                            </div>
                                                            @if (count($photos)> 1  )
                                                                <div class="back">
                                                                    <a href="{{route('front.product.view',$product->slug)}}"><img src="{{$photos[1]}}"
                                                                            class="img-fluid blur-up lazyload bg-img"
                                                                            alt="{{$product->title}}"></a>
                                                                </div>
                                                            @endif
                                                           
                                                            <div class="cart-info cart-wrap">
                                                            <button onclick="openCart()" title="Add to cart"><i
                                                                    class="ti-shopping-cart" data-id="{{ $product->id}}"></i></button>
                                                            <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" data-id="{{ $product->id}}"
                                                                    aria-hidden="true"></i></a>
                                                                        <!-- <a  href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#quick-view" title="Quick View"><i
                                                                        class="ti-search" aria-hidden="true"></i></a> 
                                                                        <a       href="compare.html" title="Compare"><i
                                                                        class="ti-reload" aria-hidden="true"></i></a> -->
                                                            </div>
                                                        </div>
                                                        <div class="product-detail">
                                                            <div>
                                                                
                                                                <a href="{{route('front.product.view',$product->slug)}}">
                                                                    <h6>{{$product->title}}</h6>
                                                                </a>
                                                               <?php echo substr( $product->summary,0,100)?>
                                                                <h4>{{number_format($product->price,0,".",",")}}</h4>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                              
                                                
                                            </div>
                                        </div>
                                        <div class="product-pagination">
                                            <div class="theme-paggination-block">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                    <nav  aria-label="Page navigation">
                                                        {{$products->links('vendor.pagination.simple-new')}}
                                                    </nav>
                                                        
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <div class="product-search-count-bottom">
                                                            <h5>Sản phẩm từ {{($products->currentPage()-1)*$products->perPage() + 1}}-{{($products->currentPage())*$products->perPage()}} trong tổng số {{$products->total()}} </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                </div>
        <!-- products -->
           <!-- blog -->
           <div class="  row">
           @if(count($blogs)==0)
                <h5 style="margin-top:20px"> Không có bài viết </h5>
            @endif
                    @foreach ($blogs as $blog)
                        <div class="col-xl-6 col-lg-6 col-md-6 blog-media row">
                            <div class="col-xl-6">
                                <div class="blog-left">
                                    <a href="{{route('front.page.view',$blog->slug)}}"><img src="{{$blog->photo?$blog->photo: asset('frontend/assets/images/blog/1.jpg')}}"
                                            class="img-fluid blur-up lazyload bg-img" alt="{{$blog->title}}"></a>
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
                        <!-- <div class="theme-paggination-block">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                            <nav class="w-full sm:w-auto sm:mr-auto">
                                {{$blogs->links('vendor.pagination.tailwind')}}
                            </nav>
                            </div>
                        </div>
                        </div> -->
                    <!-- END: Pagination -->
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
        <!-- blog -->
            </div>
        </div>
    </section>
    
@endsection
@section('scripts')
@endsection