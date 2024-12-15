
 
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm mod
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="<?php echo e(route('modpro.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tiêu đề</label>
                        <input id="title" name="title" type="text" class="form-control" placeholder="tên">
                    </div>
                     
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Vị trí</label>
                        <input id="order_id" name="order_id" type="number" class="form-control" placeholder="vị trí sắp xếp">
                        
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Loại (0 - hotsale)</label>
                        <input id="mod_type" name="mod_type" type="number" class="form-control" placeholder="loại">
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Kiểu (0 - hotsale)</label>
                        <input id="op_type" name="op_type" type="number" class="form-control" placeholder="loại">
                    </div>
                     
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                            <option value = "inactive" <?php echo e(old('status')=='inactive'?'selected':''); ?>>Inactive</option>
                                <option value ="active" <?php echo e(old('status')=='active'?'selected':''); ?>>Active</option>
                                
                            </select>
                        </div>
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
            <!-- end form layout -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/frontpromods/create.blade.php ENDPATH**/ ?>