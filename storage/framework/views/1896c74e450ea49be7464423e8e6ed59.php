<section class="small-section small-slider pt-res-0">
        <div class="container">
            <div class="slider-animate home-slider">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="home">
                            <img src=" <?php echo e($banner->photo); ?>" alt="" class="bg-img blur-up lazyload">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div class="slider-contain px-padding">
                                            <div>
                                            <?php echo $banner->description ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section><?php /**PATH D:\xampp\htdocs\shop4\resources\views/frontend/layouts/homeslider.blade.php ENDPATH**/ ?>