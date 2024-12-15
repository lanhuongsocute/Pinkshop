
<?php $__env->startSection('content'); ?>
<div class="content">
    <?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Nhận tiền từ đối tác: <?php echo e(\App\Models\User::where('id',$supplier->id)->value('full_name')); ?>

                    </h2>
                   
                </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="<?php echo e(route('user.saveusertostore')); ?>"  id ="myform">
                <?php echo csrf_field(); ?>
                <div class="intro-y box p-5">
                    <div>
                        <input type="hidden" name="id" value = "<?php echo e($supplier->id); ?>"/>

                        <?php
                            if ($supplier->budget > 0)
                            {
                                $classname = "text-danger";
                                $text_c="Cửa hàng còn nợ tiền";
                            }
                            else
                            {
                                $classname = "text-primary";
                                $text_c="Người dùng còn nợ tiền";
                            }
                            ?>
                         <div class="<?php echo e($classname); ?>"> 
                            <label for="regular-form-1 " class="form-label font-medium">Tổng công nợ hiện tại :</label> 
                            <?php echo e(number_format($supplier->budget  ,0,'.',',')); ?> (<?php echo e($text_c); ?>)<br/>
                        </div>
                       
                    </div>
                    <div>
                        <label for="regular-form-1" class="form-label">Số tiền </label>
                        <input id="paid_amount" name="paid_amount" 
                        value = "<?php echo e($supplier->budget<0?number_format(-$supplier->budget , 0,'.',',') : number_format($supplier->budget , 0,'.',',')); ?>"
                            type="text" class="form-control" placeholder="" oninput="formatNumberS(this)">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Ghi chú </label>
                        <input id="content" name="content" value = ""
                            type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tài khoản</label>
                           
                            <select name="bank_id" class="form-select mt-2 sm:mr-2"   >
                                <?php $__currentLoopData = $bankaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value ="<?php echo e($bank->id); ?>"><?php echo e($bank->title); ?>  </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        </div>
    </div>                   
                
    
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
 
   

   document.getElementById("myform").addEventListener("submit", function(event) {
       // Get the input element
       let numberInput = document.getElementById("paid_amount");

       // Remove all commas before submission
       numberInput.value = numberInput.value.replace(/,/g, '');

       // The form will now submit the cleaned value without commas
   });
 
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/users/usertostore.blade.php ENDPATH**/ ?>