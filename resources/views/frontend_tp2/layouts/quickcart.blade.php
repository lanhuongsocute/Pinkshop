<?php

?>

<div class="offcanvas-body flex flex-col">
    <div class="shopping-cart" id="head_shoping_cart">
        <?php $i = 0;  
        $tong = 0;
        foreach ($pro_carts as $procart )
        {
                $photos = explode( ',', $procart->photo); 
                $tong += $procart->price * $procart->quantity;
                 
            ?> 
            <div class="shopping-cart-item flex justify-between !mb-4">
                <div class="flex flex-row">
                    <figure class="!rounded-[.4rem] !w-[7rem]">
                        <a href="{{route('front.product.view',$procart->slug)}}">
                            <img class="!rounded-[.4rem]" src="{{$photos[0]}}"   alt="{{$procart->title}}">
                        </a>
                    </figure>
                    <div class="!w-full !ml-[1rem]">
                        <h3 class="post-title !text-[.8rem] !leading-[1.35] !mb-1"><a href="{{route('front.product.view',$procart->slug)}}" class="title_color">{{$procart->title}}</a></h3>
                        <p class="price !text-[.7rem]"> <ins class="no-underline text-[#e2626b]"><span class="amount">{{number_format($procart->price,0,'.',',')}}đ</span></ins> x  {{$procart->quantity}}</p>
                         
                    <!--/.form-select-wrapper -->
                    </div>
                </div>
                <!-- <div class="!ml-[.5rem]"><a href="#" class="title_color"><i class="uil uil-trash-alt before:content-['\ed4b']"></i></a></div> -->
            </div>
            
        <?php
        }
        ?>
       
        <!--/.shopping-cart-item -->
        
     
    </div>
    <!-- /.shopping-cart-->
    <div class="offcanvas-footer flex-col text-center">
    <div class="flex !w-full justify-between !mb-4">
        <span>Tổng:</span>
        <span id="tong_quick_cart" class="h6 !mb-0">{{number_format($tong,0,'.',',')}} đ</span>
    </div>
    <a href="{{route('front.shopingcart.view')}}" class="view-cart">  Xem giỏ hàng</a>
    <a href="{{route('front.shopingcart.checkout')}}" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] btn-icon btn-icon-start rounded !w-full !mb-4 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]"><i class="uil uil-credit-card !text-[.9rem] mr-[0.3rem] before:content-['\ea74']"></i>Mua hàng</a>
    <p class="!text-[.7rem] !mb-0">Liên hệ hotline: {{$setting->hotline}} nếu gặp khó khăn trong quá trình mua hàng!</p>
    </div>
    <!-- /.offcanvas-footer-->
</div>