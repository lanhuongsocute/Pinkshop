<div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="page-title">
                            <h2><?php echo e(isset($pagetitle)?$pagetitle:""); ?> </h2>
                        </div>
                    </div>
                    <div class="col-sm-8">
                         
                        <nav aria-label="breadcrumb" class="theme-breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Trang chủ</a></li> 
                              <?php if(isset($links)): ?>
                                <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="breadcrumb-item"><a href="<?php echo e($link->url); ?>"><?php echo e($link->title); ?></a></li> 
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                              <?php endif; ?>
                               
                                
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend/layouts/breadcrumb.blade.php ENDPATH**/ ?>