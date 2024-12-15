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
    <div class="container py-[0.5rem] xl:!py-10 lg:!py-10 md:!py-10  mb-8">
        <div class="flex flex-wrap mx-[-15px] xl:mx-[-35px] lg:mx-[-20px]">
            <div class=" ">
                <div class="blog single">
                    <div class="card">
                        
                        <div class="card-body flex-[1_1_auto] p-[40px] xl:p-[2.8rem_3rem_2.8rem] lg:p-[2.8rem_3rem_2.8rem] md:p-[2.8rem_3rem_2.8rem]">
                            <div class="classic-view">
                                <article class="post mb-8">
                                    <div class="relative mb-5">
                                        <h2 class="h1 !mb-4 !leading-[1.3]">{{$blog->title}}</h2>
                                        <?php
                                            echo $blog->content;
                                        ?>
                                    </div>
                                <!-- /.post-footer -->
                                </article>
                                <!-- /.post -->
                            </div>
                        </div>
                    </div>
                    <!--  -->
                   
                    
                    
                <!--  -->
                </div>
            </div>
            
          <!-- /column .sidebar -->
        </div>
        <!-- /.row -->
    </div>
      <!-- /.container -->
</section>
@endsection
@section('scripts')
@endsection