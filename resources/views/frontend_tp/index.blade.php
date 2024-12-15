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
  
        @if (env('DEMOAPP') == 1)
          @include(('frontend_tp2.layouts.modalpopup'))
        @endif
             @include(('frontend_tp.layouts.modmenubanner'))
            <!-- end top menu banner -->
         
            <!-- end blog-banner -->
            @include ('frontend_tp.layouts.mod6pro')
            @include ('frontend_tp.layouts.mod8randpro')
            @include('frontend_tp.layouts.bannerpromo')
            @include ('frontend_tp.layouts.mod8hotpro')
        
            <!-- end 8pro-righ-banner -->
            @include('frontend_tp.layouts.mod_4_blog')

@endsection
