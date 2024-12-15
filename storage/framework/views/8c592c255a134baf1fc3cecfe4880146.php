
<?php $__env->startSection('content'); ?>
<div class="content">
    <?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                      Tạo phiếu thu chi
                    </h2>
                   
    </div>
     <!-- BEGIN: Form Layout -->   
     <form method="post" action="<?php echo e(route('freetransaction.store')); ?>" id= "myform">
            <?php echo csrf_field(); ?>   
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box p-5">
                    <div class="mt-3">
                       <label class="font-medium"> Nội dung </label>
                       <input class="form-control" type ="text" name="content" value=""/> 
                    </div>
                    <div class="mt-3">
                       <label class="font-medium"> Số tiền: </label>
                       <input class="form-control" id='total' type ="text" name="total" value="" oninput="formatNumber(this)" /> 
                    </div>
                    <div class="mt-3">
                       <label class="font-medium"> Loại </label>
                       <select name="operation" class="form-select mt-2 sm:mr-2"   >
                                <option value ="-1"  >Chi</option>
                                <option value ="1"  >Thu</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y box p-5">
                    <div class="mt-3">
                    <label class="font-medium"> Phân loại nội dung: </label>       
                    <select name="type_id" class="form-select mt-2 sm:mr-2"   >
                           <?php $__currentLoopData = $typelist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value ="<?php echo e($type->id); ?>"  ><?php echo e($type->title); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                    </select>
                    
                
                    </div>
                 
                    <div class="mt-6">
                    <label class="font-medium"> Tài khoản: </label>       
                    <select name="bank_id" class="form-select mt-2 sm:mr-2"   >
                           <?php $__currentLoopData = $banklist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value ="<?php echo e($bank->id); ?>"  ><?php echo e($bank->title); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
                    </select>
                    <div class="form-help mt-8">
                        * Kiểm tra số tiền, tài khoản trước khi lưu. Thông tin sẽ không được điều chỉnh sau khi lưu.
                    </div>
                
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
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
           
        </div> 
        <div>
                <button type="submit" class="btn btn-primary w-24">Lưu</button>
        </div>                  
    </form>          
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    function formatNumber(input) {
            // Loại bỏ tất cả các dấu phẩy
            let value = input.value.replace(/,/g, '');
            
            // Chuyển thành dạng số và định dạng lại với dấu phân cách hàng nghìn
            if (!isNaN(value) && value.length > 3) {
                input.value = parseFloat(value).toLocaleString('en');
            }
        }

        document.getElementById("myform").addEventListener("submit", function(event) {
            // Get the input element
            let numberInput = document.getElementById("total");

            // Remove all commas before submission
            numberInput.value = numberInput.value.replace(/,/g, '');

            // The form will now submit the cleaned value without commas
        });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/freetransactions/create.blade.php ENDPATH**/ ?>