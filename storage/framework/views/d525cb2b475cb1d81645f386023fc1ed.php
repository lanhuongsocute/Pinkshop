<!-- blog section -->
<section class="blog gym-blog ratio3_2 slick-default-margin section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-basic">
                        <h2 class="title">Tin tức mới</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="slide-4 no-arrow">
                        <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <a href="<?php echo e(route('front.page.view',$blog->slug)); ?>">
                                    <div class="basic-effect">
                                        <div>
                                            <img src="<?php echo e($blog->photo); ?>"
                                                class="img-fluid blur-up lazyload bg-img" alt="<?php echo e($blog->title); ?>" title="<?php echo e($blog->title); ?>">
                                            <span></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="blog-details">
                                   
                                    <a href="<?php echo e(route('front.page.view',$blog->slug)); ?>">
                                        <p><?php echo e($blog->title); ?> </p>
                                    </a>
                                    <h6> </h6>
                                </div>
                            </div>
                            
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog section end --><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/layouts/home_blog.blade.php ENDPATH**/ ?>