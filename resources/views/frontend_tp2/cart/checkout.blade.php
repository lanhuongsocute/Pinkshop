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
    <div class="container pt-14 xl:pt-[4.5rem] lg:pt-[4.5rem] md:pt-[4.5rem] pb-[4.5rem] xl:pb-24 lg:pb-24 md:pb-24">
        <form method="POST" action ="{{route('front.shopingcart.order')}}">
            <div class="flex flex-wrap mx-[-15px] md:mx-[-20px] xl:mx-[-35px] mt-[-70px]">
           
                @csrf
                <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] xl:px-[35px] lg:px-[20px] md:px-[20px] px-[15px] mt-[70px] max-w-full">
                    <div class="row check-out">
                        <h2> Địa chỉ nhận hóa đơn </h2>
                        <div id="invoice_div" class="form-group col-md-12 col-sm-12 col-xs-12">
                                            
                            <div id = "invoice_div_detail">
                                @if ($defaut_setting && isset($invoiceaddress))
                                                
                                <input type="hidden" name="invoice_id" value="{{$invoiceaddress->id}}" />
                                <div style="padding-left:30px">  
                                    <h6> {{$invoiceaddress->full_name}} </h6>
                                    <h6> {{$invoiceaddress->phone}} </h6>
                                    <h6> {{$invoiceaddress->address}} </h6>
                                </div>
                                @endif
                                </div>
                            <a href="javascript:void(0)"
                                                data-bs-target="#addInvoiceAddress"
                                                data-bs-toggle="modal" class="bottom_btn">thêm</a> |
                            <a href="javascript:void(0)"
                                                data-bs-target="#changeInvoiceAddress"
                                                data-bs-toggle="modal" class="bottom_btn">chọn địa chỉ khác</a>
                        </div>
                        <h2> Địa chỉ giao hàng </h2>
                        <div id="ship_div" class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div id = "ship_div_detail">
                                @if ($defaut_setting && isset($shipaddress))
                                <input type="hidden" name="ship_id" value="{{$shipaddress->id}}" />
                                <div style="padding-left:30px">  
                                    <h6> {{$shipaddress->full_name}} </h6>
                                    <h6> {{$shipaddress->phone}} </h6>
                                    <h6> {{$shipaddress->address}} </h6>
                                </div>
                                @endif
                            </div>
                            <a href="javascript:void(0)"
                                                data-bs-target="#addShipAddress"
                                                data-bs-toggle="modal" class="bottom_btn">thêm</a> |
                            <a href="javascript:void(0)"
                                                data-bs-target="#changeShipAddress"
                                                data-bs-toggle="modal" class="bottom_btn">chọn địa chỉ khác</a>
                        </div>
                    </div>
                </div>
                <div class="xl:w-6/12 lg:w-6/12 w-full flex-[0_0_auto] xl:px-[35px] lg:px-[20px] md:px-[20px] px-[15px] mt-[70px] max-w-full">
                    <h3 class="!mb-4">Đơn hàng</h3>
                    <div class="shopping-cart mb-7">
                        <?php $tong = 0;?>
                        @foreach ( $products as $pro)
                        <?php
                             $photos = explode( ',', $pro->photo);
                        ?>
                        <div class="shopping-cart-item flex justify-between mb-4">
                            <div class="flex flex-row flex items-center">
                                <figure class="!rounded-[.4rem] !w-[7rem]">
                                    <a href="{{route('front.product.view',$pro->slug)}}">
                                        <img class="!rounded-[.4rem]" src="{{$photos[0]}}" style="width:90px;height:100px;"   alt="{{$pro->title}}">
                                    </a>
                                </figure>
                                <div class="w-full ml-4">
                                    <h3 class="post-title h6 !leading-[1.35] !mb-1">
                                        <a href="{{route('front.product.view',$pro->slug)}}" class="title_color">
                                        {{$pro->title}}
                                        </a>
                                    </h3>
                                </div>
                            </div>
                            <div class="ml-2 flex items-center">
                                <p class="price text-[0.7rem]"><span class="amount">{{number_format($pro->price,0,'.',',')}}</span></p>
                            </div>
                        </div>
                        <?php $tong += $pro->quantity * $pro->price;?>
                        @endforeach
                    </div>
                    
                    <hr class="!my-4">
                    <h3 class="!mb-2">Chi phí vận chuyển</h3>
                    <div class="!mb-5">
                        <label class="form-check-label" for="express">Thông báo sau cho khách hàng</label> 
                    </div>
                    <div class="table-responsive">
                        <table class="table table-order">
                            <tbody>
                        
                            <tr>
                                <td class="!pl-0"><strong class="title_color">Tổng</strong></td>
                                <td class="!pr-0 text-right">
                                <p class="price title_color font-bold !m-0">{{number_format($tong,0,".",",")}} đ</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="payment-options">
                            <?php echo $paymentinfo ?>
                        </div>
                        <button type="submit"  class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] rounded w-full mt-4 hover:translate-y-[-0.15rem] hover:shadow-[0_0.25rem_0.75rem_rgba(30,34,40,0.15)]">Đặt hàng</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>  
 <!--  -->

 <div class="modal fade" id="addInvoiceAddress" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content !text-center">
            <div class="modal-body relative flex-auto pt-[2.5rem] pr-[2.5rem] pb-[2.5rem] pl-[2.5rem]">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-3 text-left">Thêm địa chỉ</h2>
                <form class="text-left mb-3" method= "POST" action="{{route('front.profile.addinvoiceadd')}}">
                @csrf    
                    <div class="relative mb-4">
                        <label for="email">Tên đầy đủ</label>
                        <input   class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
                          name="full_name"   id="full_name" value="{{old('full_name')}}" >
                    </div>
                    <div class="relative mb-4">
                        <label for="review">Điện thoại</label>
                        <input   class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
                          name="phone"   id="phone" value="{{old('phone')}}" >
                    </div>
                    <div class="relative mb-4">
                        <label for="review">Địa chỉ</label>
                        <input   class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
                        name="address"   id="address" value="{{old('address')}}" >
                        
                    </div>
                    
                    <div class="relative mb-4">
                        <div class="form-check block min-h-[1.36rem] mb-0.5 pl-[1.55rem]">
                            <input class="form-check-input" type="checkbox" value="1" name="default" checked="">
                            <label class="form-check-label" for="flexCheckChecked"> địa chỉ mặc định </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-login w-full mb-2">
                        Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.modal -->

<div class="modal fade" id="addShipAddress" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content !text-center">
            <div class="modal-body relative flex-auto pt-[2.5rem] pr-[2.5rem] pb-[2.5rem] pl-[2.5rem]">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-3 text-left">Thêm địa chỉ</h2>
                <form class="text-left mb-3" method= "POST" action="{{route('front.profile.addshipadd')}}">
                @csrf    
                    <div class="relative mb-4">
                        <label for="email">Tên đầy đủ</label>
                        <input   class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
                          name="full_name"   id="full_name" value="{{old('full_name')}}" >
                    </div>
                    <div class="relative mb-4">
                        <label for="review">Điện thoại</label>
                        <input   class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
                          name="phone"   id="phone" value="{{old('phone')}}" >
                    </div>
                    <div class="relative mb-4">
                        <label for="review">Địa chỉ</label>
                        <input   class=" form-control  relative block w-full text-[.75rem] font-medium text-[#60697b] bg-[#fefefe] bg-clip-padding border shadow-[0_0_1.25rem_rgba(30,34,40,0.04)] rounded-[0.4rem] border-solid border-[rgba(8,60,130,0.07)] transition-[border-color] duration-[0.15s] ease-in-out focus:text-[#60697b] focus:bg-[rgba(255,255,255,.03)] focus:shadow-[0_0_1.25rem_rgba(30,34,40,0.04),unset] focus:!border-[rgba(63,120,224,0.5)] focus-visible:!border-[rgba(63,120,224,0.5)] focus-visible:!outline-0 placeholder:text-[#959ca9] placeholder:opacity-100 m-0 !pr-9 p-[.6rem_1rem] h-[calc(2.5rem_+_2px)] min-h-[calc(2.5rem_+_2px)] leading-[1.25]" 
                        name="address"   id="address" value="{{old('address')}}" >
                        
                    </div>
                    
                    <div class="relative mb-4">
                        <div class="form-check block min-h-[1.36rem] mb-0.5 pl-[1.55rem]">
                            <input class="form-check-input" type="checkbox" value="1" name="default" checked="">
                            <label class="form-check-label" for="flexCheckChecked"> địa chỉ mặc định </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary text-white !bg-[#3f78e0] border-[#3f78e0] hover:text-white hover:bg-[#3f78e0] hover:border-[#3f78e0] focus:shadow-[rgba(92,140,229,1)] active:text-white active:bg-[#3f78e0] active:border-[#3f78e0] disabled:text-white disabled:bg-[#3f78e0] disabled:border-[#3f78e0] !rounded-[50rem] btn-login w-full mb-2">
                        Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/.modal -->

<div class="modal fade" id="changeInvoiceAddress" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content !text-center">
            <div class="modal-body relative flex-auto pt-[2.5rem] pr-[2.5rem] pb-[2.5rem] pl-[2.5rem]">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-3 text-left">Chọn địa chỉ nhận hóa đơn</h2>
                <?php $i = 0; ?>
                @foreach ($addressbooks as $address )
                    <div class="form-check block min-h-[1.36rem] mb-0.5" style="border-bottom:1px solid">
                    <input class="invoice_ra form-check-input" type="radio" id="ra{{$i}}}" data-name="{{$address->full_name}}" data-phone="{{$address->phone}}" data-address=" {{$address->address}}" class="invoice_ra" name="invoice_id" value="{{$address->id}}">
                        <label class="form-check-label" for="ra{{$i}}}">
                           
                            <div  >  
                                <h6> Tên: <span> {{$address->full_name}}</span> </h6>
                                <h6> Điện thoại: <span> {{$address->phone}}</span> </h6>
                                <h6> Địa chỉ: <span> {{$address->address}}</span> </h6>
                            </div> 
                        </label>
                        <?php $i ++; ?>
                    </div>
                @endforeach  
                 
            </div>
        </div>
    </div>
</div>
<!--/.modal -->
   
<div class="modal fade" id="changeShipAddress" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content !text-center">
            <div class="modal-body relative flex-auto pt-[2.5rem] pr-[2.5rem] pb-[2.5rem] pl-[2.5rem]">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h2 class="mb-3 text-left">Chọn địa chỉ nhận hóa đơn</h2>
                <?php $i = 0; ?>
                @foreach ($addressbooks as $address )
                    <div class="form-check block min-h-[1.36rem] mb-0.5" style="border-bottom:1px solid">
                    <input class="ship_ra form-check-input" type="radio" id="ra{{$i}}}" data-name="{{$address->full_name}}" data-phone="{{$address->phone}}" data-address=" {{$address->address}}" class="invoice_ra" name="invoice_id" value="{{$address->id}}">
                        <label class="form-check-label" for="ra{{$i}}}">
                           
                            <div  >  
                                <h6> Tên: <span> {{$address->full_name}}</span> </h6>
                                <h6> Điện thoại: <span> {{$address->phone}}</span> </h6>
                                <h6> Địa chỉ: <span> {{$address->address}}</span> </h6>
                            </div> 
                        </label>
                        <?php $i ++; ?>
                    </div>
                @endforeach  
                 
            </div>
        </div>
    </div>
</div>
<!--/.modal -->
 

@endsection
@section('scripts')
<script>
    $('.invoice_ra').on('click', function () {
        var invoice_id = $(this).attr("value");
        var inner = '<input type="hidden" name="invoice_id" value="' + invoice_id+'"  />'
            + '<div class="px-20">'+$(this).attr("data-name")+' <h6> </h6>'
            + '<h6> '+$(this).attr("data-phone")+' </h6>'
            +'<h6> '+$(this).attr("data-address")+' </h6> </div>';
        $('#invoice_div_detail').html(inner);
        $('#changeInvoiceAddress').modal('hide');

    });
    $('.ship_ra').on('click', function () {
        var ship_id = $(this).attr("value");
        var inner = '<input type="hidden" name="ship_id" value="' + ship_id+'"  />'
            + '<div class="px-20">'+$(this).attr("data-name")+' <h6> </h6>'
            + '<h6> '+$(this).attr("data-phone")+' </h6>'
            +'<h6> '+$(this).attr("data-address")+' </h6> </div>';
        $('#ship_div_detail').html(inner);
        $('#changeShipAddress').modal('hide');
    });
</script>
@endsection