
<?php $__env->startSection('css'); ?>
    
     
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<section class="blog-detail-page section-b-space ratio2_3">
        <div class="container blog-container">
            <div class="row">
                <div class="col-sm-12 blog-detail">
                    
                    <h3><?php echo e($blog->title); ?></h3>
                    
                    <p> 
                        <?php 
                        echo $blog->content;
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/frontend/blog/chinhsach.blade.php ENDPATH**/ ?>