
<?php $__env->startSection('css'); ?>
    
     
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
   <!-- Home slider -->
 
   <?php echo $__env->make('frontend.layouts.homeslider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Home slider end -->


    <!-- service section start -->
    <?php echo $__env->make('frontend.layouts.home_service', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- service section end -->


    <!-- product deal section start -->
    <?php echo $__env->make('frontend.layouts.home_dealday', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- product deal section start -->


    <!-- banner section start -->
     <!-- include('frontend.layouts.home_banner') -->
    <!-- banner section end -->

    <!-- slider and product -->
     <!-- include('frontend.layouts.product_slider') -->
    <!-- slider and product -->



    <!-- banner section start -->
    <?php echo $__env->make('frontend.layouts.home_banner2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- banner section end -->


    <!-- collection banner -->
     <!-- include('frontend.layouts.home_banner3') -->
    <!-- collection banner end -->


    <!-- Tab product -->
    <?php echo $__env->make('frontend.layouts.product_tab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Tab product end -->
    <!--  blog section -->
     <?php echo $__env->make('frontend.layouts.home_blog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--  blog section end-->

    <!--  logo section -->
     <!-- include('frontend.layouts.home_logobrand') -->
    <!--  logo section end-->
    <?php if(env('DEMOAPP') == 1): ?>
          <?php echo $__env->make(('frontend.layouts.modalpopup'), \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('frontend/assets/js/timer.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master1', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/index.blade.php ENDPATH**/ ?>