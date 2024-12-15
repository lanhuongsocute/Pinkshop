 
<?php if(session('success')): ?>
<div class="alert alert-success !text-[#308970] !bg-[#edf9f6] !border-[#308970] !p-[1rem]  alert-icon !pl-10 alert-dismissible fade show !border-0 !shadow-none" role="alert">
     
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close !text-[#308970]" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger !text-[#9e454b] !bg-[#fcf0f1] !border-[#9e454b] !p-[1rem]  alert-icon !pl-10 alert-dismissible fade show !border-0 !shadow-none" role="alert">
     
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close !text-[#308970]" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
 
<?php endif; ?>
<?php if($errors->any()): ?>
<div class="alert alert-danger !text-[#9e454b] !bg-[#fcf0f1] !border-[#9e454b] !p-[1rem]  alert-icon !pl-10 alert-dismissible fade show !border-0 !shadow-none" role="alert">
     
    <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>    <?php echo e($error); ?> </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <button type="button" class="btn-close !text-[#308970]" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
 
<?php endif; ?>
                
 <?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/frontend_tp/layouts/notification.blade.php ENDPATH**/ ?>