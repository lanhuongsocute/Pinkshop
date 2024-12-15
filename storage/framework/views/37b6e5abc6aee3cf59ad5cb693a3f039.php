
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
           Cập nhật hệ thống
        </h2>
         
        
    </div>
    <div class="mt-3">
        <form action="<?php echo e(route('setting.updateinvpro')); ?>" method = "post">
         <?php echo csrf_field(); ?>
        
        <button type="submit" class ="btn"> Cập nhật chương trình </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="<?php echo e(route('setting.updatesitemap')); ?>" method = "post">
         <?php echo csrf_field(); ?>
        
        <button type="submit" class ="btn"> Cập nhật sitemap </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="<?php echo e(route('setting.kiemtracongno')); ?>" method = "post">
         <?php echo csrf_field(); ?>
        
        <button type="submit" class ="btn"> Kiểm tra công nợ hệ thống </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="<?php echo e(route('setting.cnsp_brand')); ?>" method = "post">
         <?php echo csrf_field(); ?>
        <input type="text" name="brand_id" placeholder="brand_id"/>
        <button type="submit" class ="btn"> Cập nhật sản phẩm theo danh mục </button>
        </form>
    </div>
    <div class="mt-3">
        <form action="<?php echo e(route('setting.getbrand')); ?>" method = "post">
         <?php echo csrf_field(); ?>
        <button type="submit" class ="btn">Xem danh mục</button>
        </form>
    </div>
    <div class="mt-3">
        <form action="<?php echo e(route('setting.testapi')); ?>" method = "post">
         <?php echo csrf_field(); ?>
        <button type="submit" class ="btn">Test api</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/setting/updatedata.blade.php ENDPATH**/ ?>