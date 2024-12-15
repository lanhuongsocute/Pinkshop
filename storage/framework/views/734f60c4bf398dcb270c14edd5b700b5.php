<section class="container">
    <?php $__currentLoopData = $pro_banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
 
        <div class="full-banner small-banner text-center p-center">
            <img src="<?php echo e($pb->photo); ?>" alt="" class="bg-img blur-up lazyload">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="banner-contain app-detail">
                            <?php echo $pb->description ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/layouts/home_banner2.blade.php ENDPATH**/ ?>