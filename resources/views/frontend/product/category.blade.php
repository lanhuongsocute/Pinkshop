@extends('frontend.layouts.master')
@section('css')
<style>
    .theme-card{
        margin-bottom:30px;
    }
</style>  
@endsection
@section('content')
  
  <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="collection-content col col-lg-9  col-sm-9">
                        <div class="page-main-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="collection-product-wrapper">
                                         <!--filter-->
                                        <div class="product-top-filter">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="filter-main-btn"><span
                                                            class="filter-btn btn btn-theme"><i class="fa fa-filter"
                                                                aria-hidden="true"></i> Filter</span></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="product-filter-content">
                                                        <div class="search-count">
                                                            <h5> {{$cat->title}} </h5>
                                                        </div>
                                                        <div class="collection-view">
                                                            <ul>
                                                                <li><i class="fa fa-th grid-layout-view"></i></li>
                                                                <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                            </ul>
                                                        </div>
                                                        <div class="collection-grid-view">
                                                            <ul>
                                                                <li><img src="{{asset('frontend/assets/images/icon/2.png')}}" alt=""
                                                                        class="product-2-layout-view"></li>
                                                                <li><img src="{{asset('frontend/assets/images/icon/3.png')}}" alt=""
                                                                        class="product-3-layout-view"></li>
                                                                <li><img src="{{asset('frontend/assets/images/icon/4.png')}}" alt=""
                                                                        class="product-4-layout-view"></li>
                                                                <li><img src="{{asset('frontend/assets/images/icon/6.png')}}" alt=""
                                                                        class="product-6-layout-view"></li>
                                                            </ul>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <!--filter-->
                                         <!-- sub category start -->
                                        @if (count ($subcats) > 0)
                                            <div class="container" style="margin-top:20px">
                                                <div class="row margin-default ratio_square">
                                                    @foreach ($subcats as $subcat )
                                                        <div class="col-xl-2 col-sm-3 col-4">
                                                            <a href="{{route('front.product.cat',$subcat->slug)}}">
                                                                <div class="img-category">
                                                                    <div style="max-height:120px" class="img-sec">
                                                                        <img   src="{{$subcat->photo?$subcat->photo:asset('frontend/assets/images/fashion/category/1.jpg')}}" class="img-fluid bg-img" alt="{{$subcat->title}}">
                                                                    </div>
                                                                    <h5>{{$subcat->title}}</h5>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <!-- sub category start -->

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
                                                                <a href="{{route('front.product.view',$product->slug)}}">
                                                                    <img src="{{count($photos)>0?$photos[0]:asset('frontend/assets/images/pro3/35.jpg')}}"
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
                                                                       
                                                            </div>
                                                        </div>
                                                        <div class="product-detail">
                                                            <div>
                                                                
                                                                <a href="{{route('front.product.view',$product->slug)}}">
                                                                    <h6>{{$product->title}}</h6>
                                                                </a>
                                                               
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-lg-3 collection-filter">
                        <div class="theme-card">
                            <h5 class="title-border">Sản phẩm mới</h5>
                            <div class="offer-slider slide-1">
                                <div>
                                <?php
                                    $i = 0;
                                    for($i = 0;  $i < 3 && $i < count($newpros);$i ++)
                                    {
                                        $newpro = $newpros[$i];
                                        $nphotos = explode( ',', $newpro->photo);
                                        ?>
                                            <div class="media">
                                                <a href=""><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}" alt="{{$newpro->title}}"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="{{route('front.product.view',$newpro->slug)}}">
                                                        <h6>{{$newpro->title}}</h6>
                                                    </a>
                                                    <h4>{{number_format($newpro->price,0,".",",")}}</h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                                 <div>
                                <?php
                                     
                                    for( ;  $i < 6 && $i < count($newpros);$i ++)
                                    {
                                        $newpro = $newpros[$i];
                                        $nphotos = explode( ',', $newpro->photo);
                                        ?>
                                            <div class="media">
                                                <a href=""><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}" alt="{{$newpro->title}}"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="{{route('front.product.view',$newpro->slug)}}">
                                                        <h6>{{$newpro->title}}</h6>
                                                    </a>
                                                    <h4>{{number_format($newpro->price,0,".",",")}}</h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                            </div>
                        </div>
                        <div class="theme-card">
                            <h5 class="title-border">Có thể bạn quan tâm</h5>
                            <div class="offer-slider slide-1">
                                <div>
                                <?php
                                    $i = 0;
                                    for($i = 0;  $i < 3 && $i < count($randpros);$i ++)
                                    {
                                        $randpro = $randpros[$i];
                                        $nphotos = explode( ',', $randpro->photo);
                                        ?>
                                            <div class="media">
                                                <a href=""><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}" alt="{{$randpro->title}}"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="{{route('front.product.view',$randpro->slug)}}">
                                                        <h6>{{$randpro->title}}</h6>
                                                    </a>
                                                    <h4>{{number_format($randpro->price,0,".",",")}}</h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                                 <div>
                                <?php
                                     
                                    for( ;  $i < 6 && $i < count($randpros);$i ++)
                                    {
                                        $randpro = $randpros[$i];
                                        $nphotos = explode( ',', $randpro->photo);
                                        ?>
                                            <div class="media">
                                                <a href=""><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}" alt="{{$randpro->title}}"></a>
                                                <div class="media-body align-self-center">
                                                    <a href="{{route('front.product.view',$randpro->slug)}}">
                                                        <h6>{{$randpro->title}}</h6>
                                                    </a>
                                                    <h4>{{number_format($randpro->price,0,".",",")}}</h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                            </div>
                        </div>
                        <div class="theme-card">
                            <h5 class="title-border">Nổi bật</h5>
                            <div class="offer-slider slide-1">
                                <div>
                                <?php
                                    $i = 0;
                                    for($i = 0;  $i < 3 && $i < count($poppros);$i ++)
                                    {
                                        $poppro = $poppros[$i];
                                        $nphotos = explode( ',', $poppro->photo);
                                        ?>
                                            <div class="media">
                                                <a href=""><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}" alt=""></a>
                                                <div class="media-body align-self-center">
                                                    <a href="{{route('front.product.view',$poppro->slug)}}">
                                                        <h6>{{$poppro->title}}</h6>
                                                    </a>
                                                    <h4>{{number_format($poppro->price,0,".",",")}}</h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                                 <div>
                                <?php
                                     
                                    for( ;  $i < 6 && $i < count($poppros);$i ++)
                                    {
                                        $randpro = $poppros[$i];
                                        $nphotos = explode( ',', $poppro->photo);
                                        ?>
                                            <div class="media">
                                                <a href=""><img style="max-width:160px; max-height:160px" class="img-fluid blur-up lazyload"
                                                        src="{{count($nphotos)>0?$nphotos[0]:asset('frontend/assets/images/fashion/pro/1.jpg')}}" alt=""></a>
                                                <div class="media-body align-self-center">
                                                    <a href="{{route('front.product.view',$poppro->slug)}}">
                                                        <h6>{{$poppro->title}}</h6>
                                                    </a>
                                                    <h4>{{number_format($poppro->price,0,".",",")}}</h4>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                 </div>
                            </div>
                        </div>
                        <!-- side-bar single product slider end -->
                        <!-- side-bar banner start here -->
                        <div class="collection-sidebar-banner py-10">
                             
                        </div>
                        <!-- side-bar banner end here -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section End -->
    
@endsection
@section('scripts')
@endsection