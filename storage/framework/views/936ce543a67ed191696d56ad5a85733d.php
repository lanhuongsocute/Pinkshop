<div class="widget  ">
    <div class="post-header  ">
        <div class="mb-5 ">
            <h3>
                <a href="#"  class="secondarytextcolor hover" rel="category"> <span  style="color: black !important">Sản phẩm mới</span></a> 
            </h3>
        </div>
    </div>
    <ul class="m-0 p-0 after:content-[''] after:block after:h-0 after:clear-both after:invisible">
        <?php $__currentLoopData = $newpros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
             $photos = explode( ',', $pro->photo);
        ?>
        <li class=" clear-both block overflow-hidden mb-5">
            <figure class="!rounded-[.4rem] float-left w-14 !h-[4.5rem]">
                <a href="<?php echo e(route('front.product.view',$pro->slug)); ?>" >
                    <img class="!rounded-[.4rem]" src="<?php echo e($photos[0]); ?>" alt="<?php echo e($pro->title); ?>">
                </a>
            </figure>
            <div class="!relative ml-[4.25rem] mb-0">
                <h6 class="!mb-2"> <a class="title_color" href="<?php echo e(route('front.product.view',$pro->slug)); ?>"><?php echo e($pro->title); ?></a> </h6>
                <ul class="text-[0.7rem] text-[#e2626b] m-0 p-0 list-none">
                    <li class="post-date inline-block"> <span><?php echo e(number_format($pro->price,0,'.',',')); ?> đ</span></li>
                </ul>
                <!-- /.post-meta -->
            </div>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
                <!-- /.image-list -->
</div><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/frontend_tp/layouts/sideproduct.blade.php ENDPATH**/ ?>