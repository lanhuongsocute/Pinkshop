<!DOCTYPE html>
<html lang="en">

<head>
   <?php echo $__env->make('frontend.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</head>

<body class="theme-color-10" style="over-flow:auto">


    <!-- loader start -->
   
    <!-- loader end -->


    <!-- header start -->
    <?php echo $__env->make('frontend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- header end -->
    <!-- breadcrumb -->
   
    <!-- breadcrumb -->
<!-- error display -->
        <div>
        <?php if(session('success')): ?>
        <div class="alert alert-primary alert-dismissible show flex items-center mb-2" role="alert"> 
            <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i> 
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
                <i data-lucide="x" class="w-4 h-4"> </i> 
            </button> 
        </div>
    
    <?php endif; ?>
         <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible show flex items-center mb-2" role="alert"> 
                <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i> 
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close"> 
                    <i data-lucide="x" class="w-4 h-4"></i> 
                </button> 
            </div>
            
            <?php endif; ?>
    
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>    <?php echo e($error); ?> </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
             
    </div>
<!-- error display -->
    <?php echo $__env->yieldContent('content'); ?>
    


    <!-- footer section start -->
    <?php echo $__env->make('frontend.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- footer section end -->


    <!--modal popup start-->
    <!-- include('frontend.layouts.home_popup') -->
    <!--modal popup end-->


    <!-- Quick-view modal popup start-->
    <?php echo $__env->make('frontend.layouts.popup_quickview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Quick-view modal popup end-->

 

    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="fa fa-angle-double-up"></i>
        </div>
    </div>
    <!-- tap to top end -->
    <?php echo $__env->make('frontend.layouts.foot', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
    <?php echo $__env->yieldContent('scripts'); ?>

</body>

</html><?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend/layouts/master1.blade.php ENDPATH**/ ?>