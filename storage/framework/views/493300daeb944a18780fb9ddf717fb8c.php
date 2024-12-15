
<?php $__env->startSection('scriptop'); ?>

 
 
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh giá
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="<?php echo e(route('product.priceupdate')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value = "<?php echo e($product->id); ?>" />

                <div class="intro-y box p-5">
                     <?php $__currentLoopData = $group_prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gprice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <div class="mt-3">
                        <label for="regular-form-1" class="form-label"><?php echo e($gprice->title); ?></label>
                        <input id="weight" name="gp<?php echo e($gprice->id); ?>" value="<?php echo e($gprice->price); ?>" type="text" class="form-control"  >
                    </div>
                    
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Giá trước khuyến mãi</label>
                        <input id="old_price" name="old_price" value="<?php echo e($productextend->old_price); ?>" type="text" class="form-control"  >
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Giá chung hiện tại</label>
                        <input id="price" name="price" value="<?php echo e($product->price); ?>" type="text" class="form-control"  >
                    </div>
                    <div class="mt-3">
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
                    <div class="text-right mt-5">
                        <button type="submit" class="btn btn-primary w-24">Lưu</button>
                    </div>
                </div>
            </form>
             
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
 
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/products/viewprice.blade.php ENDPATH**/ ?>