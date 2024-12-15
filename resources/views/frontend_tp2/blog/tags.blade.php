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

        <div class=" mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]   " style="padding-top:10px">
            <div>
                <!-- /.blog -->
                 <h3 style="padding-top: 10px;padding-bottom:10px"> Danh sách bài viết </h3>
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
                                            <div class="inline-flex mb-[.4rem] uppercase tracking-[0.02rem] text-[0.7rem] font-bold primarytextcolor relative align-top pl-[1.4rem] before:content-[''] before:absolute before:inline-block before:translate-y-[-60%] before:w-3 before:h-[0.05rem] before:left-0 before:top-2/4  leading_title_color">
                                            <a href="{{route('front.category.view',$cat->slug)}}" class="hover" rel="category">{{$cat->title}}</a>
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
           
            <div>
                <h3 style="padding-top: 10px;padding-bottom:10px"> Danh sách sản phẩm </h3>
                <div class="itemgrid grid-view projects-masonry shop mb-5">
                    <div class="flex flex-wrap mx-[15px] xl:mx-[20px] lg:mx-[20px] md:mx-[20px] mt-[-20px] xl:mt-[-20px] lg:mt-[-20px] md:mt-[-20px] isotope">
                        @foreach ($products as $product )
                        
                        <div  style="padding-right:10px" class="    project item group md:w-6/12 lg:w-6/12 xl:w-4/12 w-full flex-[0_0_auto] xl:px-[20px] lg:px-[20px] md:px-[20px] px-[15px] mt-[20px] xl:mt-[20px] lg:mt-[20px] md:mt-[20px]  max-w-full">
                            <div class="card" style="padding-right:10px;padding-left:10px;">
                                
                                <figure class="!rounded-[.4rem] !mb-6" style=" height:250px; overflow:hidden; padding:0px">
                                    <?php
                                        $photos = explode( ',', $product->photo);
                                        $cat = \App\Models\Category::find($product->cat_id);
                                    ?>
                                    <a href="{{route('front.product.view',$product->slug)}}">
                                        <img src="{{$photos[0]}}" alt="{{$product->title}}">
                                    </a>
                                    <a class="item-like opacity-0 absolute !w-[2.2rem] !h-[2.2rem] leading-[2.2rem] z-[1] transition-all duration-[0.3s] ease-in-out title_color shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.02)] text-[1rem] flex items-center justify-center rounded-[100%] right-0 bg-[#ffffff] top-4 group-hover:opacity-100 group-hover:right-4" 
                                        href="javascript:void(0)" data-bs-toggle="white-tooltip" title="Add to wishlist"><i data-id="{{ $product->id}}" class="uil uil-heart before:content-['\eb66']"></i></a>
                                    <a href="javascript:void(0)" data-id="{{ $product->id}}" class="item-cart opacity-0 absolute bottom-[-2rem] w-full h-auto text-white text-center transition-all duration-[0.3s] ease-in-out text-[0.85rem] flex items-center justify-center font-bold m-0 p-[0.8rem] left-0 bg-[rgba(38,43,50,.8)] hover:bg-[rgba(38,43,50,.9)] hover:!text-white group-hover:opacity-100 group-hover:bottom-0"><i class="uil uil-shopping-bag font-normal mt-[-0.05rem] mr-1 before:content-['\ecba']"></i> Thêm giỏ hàng</a>
                                    @if ($product->feature == 1)
                                        <span class="  flex items-center justify-center font-bold leading-[1.7] tracking-[-0.01rem] rounded-[100%] !bg-[rgba(209,107,134)] !opacity-100 text-white !w-[2.5rem] !h-[2.5rem] absolute uppercase text-[0.65rem]" style="top: 1rem; left: 1rem;"><span>Sale!</span></span>
                                    @endif
                                </figure>
                                <div class="post-header"  style=" height:100px; overflow:hidden; padding:0px">
                                    <div class="flex flex-row items-center justify-between mb-2">
                                    @if ($cat)
                                        <div class="uppercase tracking-[0.02rem] text-[0.7rem] font-bold text-[#9499a3] !mb-0">
                                            <a class="secondarytextcolor  " href="{{route('front.product.cat',$cat->slug)}}">  {{$cat->title}} </a>
                                        </div>
                                    @endif
                                    </div>
                                    
                                    <h2 class="post-title h3 text-[1.1rem]"><a href="{{route('front.product.view',$product->slug)}}" class="title_color"> {{$product->title}}</a></h2>
                                    
                                </div>
                                <p class="price !m-0"> <ins class="no-underline  "><span class="amount">{{number_format($product->price,0,'.',',')}} đ</span></ins></p>
                                <!-- /.post-header -->
                                
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- /.grid -->
                <nav class="flex" aria-label="pagination">
                        <!-- /.pagination -->
                        {{$products->links('vendor.pagination.simple-new')}}
                </nav>
            </div>
          <!-- /column -->
            
          <!-- /column .sidebar -->
        </div>
        <!-- /.row -->
    </div>
    
      <!-- /.container -->
</div>
    
@endsection
@section('scripts')
@endsection