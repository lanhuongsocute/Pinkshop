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
    <div class="container py-[4.5rem] xl:!py-24 lg:!py-24 md:!py-24">
        <div class="flex flex-wrap  mx-[-15px] mt-[-60px]">
            <div class="xl:w-9/12 lg:w-9/12 w-full flex-[0_0_auto] px-[15px] max-w-full md:px-[20px] lg:px-[20px] xl:px-[35px]">
                <div class="flex flex-wrap mx-[-15px] md:mx-[-20px] lg:mx-[-20px] xl:mx-[-35px] mt-[-40px]">
                    <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] xl:px-[35px] lg:px-[20px] mt-[40px] max-w-full">
                        <div class="swiper-container swiper-thumbs-container" data-margin="10" data-dots="false" data-nav="true" data-thumbs="true">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <?php
                                        $photos = explode( ',', $product->photo);
                                    ?>
                                    @foreach ($photos as $photo )
                                    <div class="swiper-slide group">
                                        <figure class="rounded-[0.4rem]">
                                            <img class="rounded-[0.4rem]" src="{{$photo}}"   alt="{{$product->title}}">
                                            <a class="item-link absolute w-[2.2rem] h-[2.2rem] leading-[2.2rem] z-[1] transition-all duration-[0.3s] ease-in-out opacity-0 title_color shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.02)] text-[1rem] flex items-center justify-center rounded-[100%] right-0 bottom-4 bg-[rgba(255,255,255,.7)] hover:bg-[rgba(255,255,255,.9)] hover:!text-[#343f52] group-hover:opacity-100 group-hover:right-[1rem]"
                                            href="{{$photo}}" data-glightbox data-gallery="product-group">
                                                <i class="uil uil-focus-add before:content-['\eb22']"></i>
                                            </a>
                                        </figure>
                                    </div>
                                    @endforeach
                                </div>
                            <!--/.swiper-wrapper -->
                            </div>
                    <!-- /.swiper -->
                            <div class="swiper swiper-thumbs">
                                    <div class="swiper-wrapper">
                                        @foreach ($photos as $photo )
                                        <div class="swiper-slide">
                                            <img src="{{$photo}}"  style="width:114px; height: 120px" class="rounded-[.4rem]" alt="{{$product->title}}"></div>
                                        @endforeach
                                    
                                    </div>
                                <!--/.swiper-wrapper -->
                            </div>
                    <!-- /.swiper -->
                        </div>
                    <!-- /.swiper-container -->
                    </div>
                
                    <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] px-[15px] xl:px-[35px] lg:px-[20px] mt-[40px] max-w-full">
                        <div class="post-header mb-5">
                            <h2 class="post-title text-[calc(1.285rem_+_0.42vw)] font-bold xl:text-[1.6rem] leading-[1.3]">{{$product->title}} </h2>
                            <p class="price text-[1rem] mb-2"><span class="   amount">{{number_format($product->price,0,'.',',')}} đ</span></p>
                        </div>
                        <p class="!mb-6"> <?php echo $product->summary; ?></p>
                        <div class="flex flex-wrap mx-[-15px]">
                            <div class="xl:w-9/12 lg:w-9/12 w-full flex-[0_0_auto] px-[15px] max-w-full flex flex-row pt-2">
                                <div>
                                    <div class="form-select-wrapper">
                                        <select id="pro_quantity" class="form-select">
                                            <option value="1" selected>1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                    <!--/.form-select-wrapper -->
                                </div>
                                <div class="grow mx-2">
                                    <button data-id="{{$product->id}}" id="btn_add_to_cart" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] btn-icon btn-icon-start rounded !w-full grow hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]">
                                        <i class="uil uil-shopping-bag font-normal mt-[-0.05rem] mr-1 before:content-['\ecba']"></i> Add to Cart</button>
                                </div>
                                <div>
                                    <button data-id="{{$product->id}}" id="btn_add_to_wish" class="btn btn-block btn-red text-white !bg-[#e2626b] border-[#e2626b] hover:text-white hover:bg-[#e2626b] hover:border-[#e2626b] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#e2626b] active:border-[#e2626b] disabled:text-white disabled:bg-[#e2626b] disabled:border-[#e2626b] btn-icon rounded !px-3 !w-full !h-full hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]"><i class="uil uil-heart before:content-['\eb66']"></i></button>
                                </div>
                            </div>
                            <!-- /column -->
                        </div>
                        <p class="!mt-2 !mb-6 hidden xl:block lg:block"> 
                            @foreach ($tags as $tag )
                                <span class=" mt-2 mb-[0.45rem] mr-[0.2rem] inline-block">
                                    <a href="{{route('front.tag.view',$tag->slug)}}" class="btn btn-soft-ash btn-sm !rounded-[50rem] flex items-center hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,.05)] before:not-italic   ">
                                        {{$tag->title}}
                                    </a>
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>
                <!-- /.row -->
                <ul class="nav nav-tabs nav-tabs-basic mt-[70px]">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab1-1">Thông tin sản phẩm</a>
                    </li>
                
                </ul>
                    <!-- /.nav-tabs -->
                <div class="tab-content mt-0 md:!mt-5">
                    <div class="tab-pane fade show active" id="tab1-1">
                        <?php
                            echo $product->description;
                        ?>
                    </div>
                    <!--/.tab-pane -->
                </div>
                <!-- /.tab-content -->
                @include('frontend_tp.layouts.mod_4_pro')
                @include('frontend_tp.layouts.comment')
                @include('frontend_tp.layouts.comment_form')
            </div>
            <aside class="  hidden xl:block lg:block  xl:w-3/12 lg:w-3/12 w-full flex-[0_0_auto] xl:px-[15px] lg:px-[15px] px-[15px] max-w-full sidebar mt-[0rem] xl:!mt-0 lg:!mt-0">
                @include('frontend_tp.layouts.sideproduct')
                @include('frontend_tp.layouts.catpromenu')
                @include('frontend_tp.layouts.sidehotproduct')
            </aside>
        </div>
    </div>
      <!-- /.container -->
</section>

@endsection
