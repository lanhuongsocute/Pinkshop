
<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="<?php echo e(__('Pagination Navigation')); ?>" class="">
        <ul class="pagination">
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item "><a class="page-link" ><<</a></li>
            <?php else: ?>
                <li class="page-item active"><a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev"><<</a></li>
            <?php endif; ?>
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li  class="page-item "  aria-current="page">
                                <a   class=" page-link"> <?php echo e($page); ?></a>
                            </li>
                        <?php else: ?>
                            <li  class="page-item active"  aria-current="page">
                                <a  href="<?php echo e($url); ?>"  class=" page-link"> <?php echo e($page); ?></a>
                            </li>
                             
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item active" ><a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">>></a></li>
            <?php else: ?>
                <li class="page-item " aria-disabled="true"><a class="page-link" >>></a></li>
            <?php endif; ?>
        </ul>
 
    </nav>
<?php endif; ?>
<?php /**PATH D:\xampp1\htdocs\shop4\resources\views/vendor/pagination/simple-new.blade.php ENDPATH**/ ?>