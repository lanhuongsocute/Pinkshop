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
            <div class ='job-list'>
                @foreach ($orders as $order )
                    <a  href="javascript:void(0)" onclick="show({{$order->id}})" class="card mb-4 lift">
                        <div class="card-body p-5">
                            <span class="flex flex-wrap mx-[-15px] justify-between items-center">
                                <span class="xl:w-4/12 lg:w-5/12 md:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full mb-2 xl:mb-0 lg:mb-0 md:mb-0 flex items-center text-[#60697b]">
                                  
                                <span class=" flex items-center justify-center font-bold leading-[1.7] tracking-[-0.01rem] rounded-[100%] bg-[rgba(116,126,209)] opacity-100 text-white !w-9 !h-9 text-[0.85rem] mr-3">{{$order->id}}</span> 
                                    {{substr($order->created_at,0,10)}}
                                </span>
                                <span class="xl:w-4/12 lg:w-3/12 md:w-3/12 w-5/12   flex-[0_0_auto] px-[15px] max-w-full text-[#60697b] flex items-center">
                                    {{number_format($order->final_amount,0,'.',',')}}
                                </span>
                                <span class="w-7/12 md:w-4/12 lg:w-3/12 xl:w-3/12 flex-[0_0_auto] px-[15px] max-w-full text-[#60697b] flex items-center">
                                    {{$order->status}} 
                                </span>
                                <span class="hidden xl:block lg:block w-1/12 flex-[0_0_auto] px-[15px] max-w-full !text-center text-[#60697b]">
                                <i class="uil uil-angle-right-b before:content-['\e930']"></i>
                                </span>
                            </span>
                            <div id="order{{$order->id}}" style="background: #eee; display:none; padding-left: 10px; padding-top:10px">
                                @foreach ($order->details as $detail )
                                    <div class="card-body p-2">
                                        <span class="flex flex-wrap mx-[-15px] justify-between items-center">
                                            <span class="xl:w-5/12 lg:w-5/12 md:w-5/12 w-full flex-[0_0_auto] px-[15px] max-w-full mb-2 xl:mb-0 lg:mb-0 md:mb-0 flex items-center text-[#60697b]">
                                                {{$detail->title}}
                                            </span>
                                            <span class="xl:w-4/12 lg:w-3/12 md:w-3/12 w-5/12   flex-[0_0_auto] px-[15px] max-w-full text-[#60697b] flex items-center">
                                                {{number_format($detail->price,0,'.',',')}}
                                            </span>
                                            <span class="w-7/12 md:w-4/12 lg:w-3/12 xl:w-3/12 flex-[0_0_auto] px-[15px] max-w-full text-[#60697b] flex items-center">
                                                {{$detail->quantity}} 
                                            </span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
function show(id)
{
    $("#order" + id).toggle();
}
</script>
@endsection
