<section class="ratio_square bg-title section-b-space  wo-bg">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="theme-tab">
                        <div class="bg-title-part mt-0">
                            <div class="title-basic mb-0">
                                <h2 class="title">Có thể bạn quan tâm</h2>
                            </div>
                            <ul class="tabs tab-title w-bg">
                                <li class="current">
                                    <a href="tab-4">Sản phẩm mới</a>
                                </li>
                                <li class="">
                                    <a href="tab-5">yêu thích</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content-cls">
                            <div id="tab-4" class="tab-content active default">
                                <div class="no-slider five-product row">
                                    @foreach ($new_pros as $newpro)
                                    <?php
                                        $photos = explode( ',', $newpro->photo);
                                    ?> 
                                    <div class="product-box product-wrap">
                                        <div class="img-wrapper">
                                            <div class="front">
                                                <a href="{{route('front.product.view',$newpro->slug)}}"><img
                                                        src="{{ $photos[0]?$photos[0]:''}}"
                                                        class="img-fluid blur-up lazyload bg-img" title="{{$newpro->title}}" alt="{{$newpro->title}}"></a>
                                            </div>
                                            <div class="cart-box style-1 rounded-0">
                                            <button onclick="openCart()" title="Add to cart"><i
                                                class="ti-shopping-cart" data-id="{{ $newpro->id}}"></i></button>
                                            <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" data-id="{{ $newpro->id}}"
                                                    aria-hidden="true"></i></a>
                                               
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                           
                                            <a href="{{route('front.product.view',$newpro->slug)}}">
                                            <p style="font-size:16px">{{$newpro->title}}</p>
                                            </a>
                                            <h4>{{number_format($newpro->price,0,".",",")}}</h4>
                                            
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="tab-5" class="tab-content">
                                <div class="no-slider five-product row">
                                @foreach ($hit_pros as $hitpro)
                                    <?php
                                        $photos = explode( ',', $hitpro->photo);
                                    ?> 
                                    <div class="product-box product-wrap">
                                        <div class="img-wrapper">
                                            <div class="front">
                                                <a href="{{route('front.product.view',$hitpro->slug)}}"><img
                                                        src="{{ $photos[0]?$photos[0]:''}}"
                                                        class="img-fluid blur-up lazyload bg-img" title = "{{$hitpro->title}}" alt="{{$hitpro->title}}"></a>
                                            </div>
                                            <div class="cart-box style-1 rounded-0">
                                                <button onclick="openCart()" title="Add to cart"><i
                                                        class="ti-shopping-cart"></i></button>
                                                <!-- <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart"
                                                        aria-hidden="true"></i></a>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick-view"
                                                    title="Quick View"><i class="ti-search" aria-hidden="true"></i></a>
                                                <a href="compare.html" title="Compare"><i class="ti-reload"
                                                        aria-hidden="true"></i></a> -->
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                           
                                            <a href="{{route('front.product.view',$hitpro->slug)}}">
                                            <p style="font-size:16px">{{$hitpro->title}}</p>
                                            </a>
                                            <h4>{{number_format($hitpro->price,0,".",",")}}</h4>
                                            
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>