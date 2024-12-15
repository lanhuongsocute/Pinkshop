
<?php $__env->startSection('content'); ?>
<div class="content">
    <?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Thanh toán phiếu bán hàng của: <?php echo e(\App\Models\User::where('id',$wo->supplier_id)->value('full_name')); ?>

                    </h2>
                   
                </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="<?php echo e(route('warehouseout.storepaid')); ?>">
                <?php echo csrf_field(); ?>
                <div class="intro-y box p-5">
                    <div>
                        <input type="hidden" name="id" value = "<?php echo e($wo->id); ?>"/>
                        <label for="regular-form-1 " class="form-label font-medium">Tổng tiền hóa đơn :</label> 
                        <?php echo e(number_format($wo->final_amount  ,0,'.',',')); ?> <br/>
                        <label for="regular-form-1" class="form-label font-medium">Tổng tiền đã thanh toán :</label> 
                        <?php echo e(number_format($wo->paid_amount  ,0,'.',',')); ?> <br/>
                        <label for="regular-form-1" class="form-label font-medium">Số tiền cần thanh toán :</label> 
                        <?php echo e(number_format($wo->final_amount - $wo->paid_amount,0,'.',',')); ?> <br/>
                    </div>
                    <div>
                        <label for="regular-form-1" class="form-label">Số tiền </label>
                        <input id="paid_amount" name="paid_amount" value = "<?php echo e($wo->final_amount - $wo->paid_amount); ?>"
                            type="number" class="form-control" placeholder="">
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tài khoản</label>
                           
                            <select name="bank_id" class="form-select mt-2 sm:mr-2"   >
                                <?php $__currentLoopData = $bankaccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value ="<?php echo e($bank->id); ?>"><?php echo e($bank->title); ?></option>
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
   
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/warehouseouts/paid.blade.php ENDPATH**/ ?>