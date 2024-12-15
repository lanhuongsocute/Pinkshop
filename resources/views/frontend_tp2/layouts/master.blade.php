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
<!DOCTYPE html>
<html lang="en">
    <head>
        @include('frontend_tp2.layouts.head')
        @yield('head_css')
    </head>
    <body class="[word-spacing:.05rem!important] font-Manrope text-[0.8rem] !leading-[1.7] font-medium">
     
    @include('frontend_tp2.layouts.header')
    @include('frontend_tp2.layouts.notification')
        @yield('content')
        @include('frontend_tp2.layouts.footer')
        @include('frontend_tp2.layouts.foot')
        @yield('scripts')
    </body>
</html>