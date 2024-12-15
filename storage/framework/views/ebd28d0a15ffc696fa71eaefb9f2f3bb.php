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

<?php $__env->startSection('head_css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('frontend_tp.layouts.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                        <h2 class="h1 !mb-4 !leading-[1.3]"><?php echo e($blog->title); ?></h2>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend_tp.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend_tp2/blog/chinhsach.blade.php ENDPATH**/ ?>