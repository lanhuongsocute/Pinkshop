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
   
<section class="wrapper !bg-[#ffffff]">
    <div class="container pb-[4.5rem] xl:pb-24 lg:pb-24 md:pb-24 pt-14">
        <div class="flex flex-wrap mx-[-15px] mt-[-50px]">
            <div class="xl:w-9/12 lg:w-9/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:!order-2 lg:!order-2 mt-[50px]">
                <div class="flex flex-wrap mx-[-15px] items-center mb-10 !relative z-[1]">
                    <div class="md:w-7/12 lg:w-7/12 xl:w-8/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:pr-40">
                            <h2 class="text-[calc(1.265rem_+_0.18vw)] font-bold xl:text-[1.4rem] leading-[1.35] !mb-1">Danh sách sản phẩm</h2>
                            <p class="mb-0 primarytextcolor"> </p>
                    </div>
                    <!--/column -->
                    <div class="md:w-5/12 lg:w-5/12 xl:w-4/12 w-full flex-[0_0_auto] px-[15px] max-w-full xl:!ml-auto lg:!ml-auto md:!ml-auto xl:!text-right lg:!text-right md:!text-right mt-5 xl:!mt-0 lg:!mt-0 md:!mt-0">
                            <div class="form-select-wrapper">
                                <select class="form-select">
                                    <option value="popularity">Sort by popularity</option>
                                    <option value="rating">Sort by average rating</option>
                                    <option value="newness">Sort by newness</option>
                                    <option value="price: low to high">Sort by price: low to high</option>
                                    <option value="price: high to low">Sort by price: high to low</option>
                                </select>
                            </div>
                        <!--/.form-select-wrapper -->
                    </div>
              <!--/column -->
                </div>
                <!--/.row -->
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
                                <p class="price !m-0"> <ins class="no-underline "><span class="amount">{{number_format($product->price,0,'.',',')}} đ</span></ins></p>
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
                <!-- /nav -->
            </div>
                <!-- /column -->
            <aside class="xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] px-[15px] max-w-full sidebar mt-[50px]">
                @include('frontend_tp.layouts.catpromenu')
                @include('frontend_tp.layouts.sideproduct')
                @include('frontend_tp.layouts.sidehotproduct')
            </aside>
                    <!-- /column .sidebar -->
        </div>
        <!-- /.row -->
    </div>
      <!-- /.container -->
</section>

@endsection
