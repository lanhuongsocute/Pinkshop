<!DOCTYPE html>
<!--
Template Name: Enigma - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <?php echo $__env->make('backend.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
    </head>
    <!-- END: Head -->
    <body class="py-5 md:py-0">
        <!-- BEGIN: Mobile Menu -->
        <?php echo $__env->make('backend.layouts.mobilemenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- END: Mobile Menu -->
        <!-- BEGIN: Top Bar -->
        <?php echo $__env->make('backend.layouts.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- END: Top Bar -->
      
        <div class="flex overflow-hidden">
            
            <!-- BEGIN: Side Menu -->
            <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- END: Side Menu -->
          
            <!-- BEGIN: Content -->
            <div id="fm" style="height: 600px;"></div>
            <?php echo $__env->yieldContent('content'); ?>
            <!-- END: Content -->
        </div>
        
        <?php echo $__env->make('backend.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>
</html>


<?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/layouts/master.blade.php ENDPATH**/ ?>