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
    <div class="container pt-14 xl:pt-[4.5rem] lg:pt-[4.5rem] md:pt-[4.5rem] pb-[4.5rem] xl:pb-24 lg:pb-24 md:pb-24">
        <div class="xl:w-10/12 w-full flex-[0_0_auto] px-[15px] max-w-full !mx-auto">
        <a href="javascript:void(0)"
                                                                    data-bs-target="#addAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thêm</a>
                                || <a href="javascript:void(0)"
                                                                    data-bs-target="#changeInvoiceAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thay đổi địa chỉ hóa đơn mặc định</a>           
                                || <a href="javascript:void(0)"
                                                                    data-bs-target="#changeShipAddress"
                                                                    data-bs-toggle="modal" class="bottom_btn">thay đổi địa chỉ giao hàng mặc định</a>           
                                
        <div class ='job-list'>
                @foreach ($addressbooks as $adbook)
                       <div class="card-body p-5">
                            <div  class="card mb-4 lift" >
                            <span class="flex flex-wrap mx-[-15px] justify-between items-center" style="padding-top:20px; padding-bottom:20px">
                                <span class="xl:w-3/12 lg:w-3/12 md:w-4/12 w-full w-3/12  flex-[0_0_auto] px-[15px] max-w-full mb-2 xl:mb-0 lg:mb-0 md:mb-0 flex items-center text-[#60697b]">
                                  
                                {{$adbook->full_name}}
                                    
                                </span>
                                <span class="xl:w-6/12 lg:w-6/12 md:w-5/12 w-6/12   flex-[0_0_auto] px-[15px] max-w-full text-[#60697b] flex items-center">
                                     
                                    {{$adbook->phone }} - {{$adbook->address }}
                                    
                                </span>
                                <span class="xl:w-3/12 lg:w-3/12 md:w-3/12 w-3/12   flex-[0_0_auto] px-[15px] max-w-full text-[#60697b] flex items-center">
                                        <a href="{{route('front.address.delete',$adbook->id)}} " >
                                                     Xóa   
                                        </a>
                                </span>
                            </div>
                        </div>
                    
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAddress" tabindex="-1">
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
        $.ajax({
            type: 'GET',
            url: '{{route("front.address.setinvoice")}}',
            data: {
                id: invoice_id,
            },
            success: function(data) {
                $.notify(data.msg,'success');
                $('#changeInvoiceAddress').modal('hide');
            },
        }); 
    });

    $('.ship_ra').on('click', function () {
        var ship_id = $(this).attr("value");
        $.ajax({
            type: 'GET',
            url: '{{route("front.address.setship")}}',
            data: {
                id: ship_id,
            },
            success: function(data) {
                $.notify(data.msg,'success');
                $('#changeShipAddress').modal('hide');
            },
        }); 
    });
</script>
@endsection
