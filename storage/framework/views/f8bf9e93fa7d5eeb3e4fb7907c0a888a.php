
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh khách hàng
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
             <!-- BEGIN: Form Layout -->
             <form method="post" action="<?php echo e(route('customer.update',$customer->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('patch'); ?>
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tên</label>
                        <input id="title" name="full_name" type="text" value="<?php echo e($customer->full_name); ?>" class="form-control" placeholder="tên">
                    </div>
                     

                   
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Địa chỉ</label>
                        <input id="address" name="address" value="<?php echo e($customer->address); ?>"  type="text" class="form-control" placeholder="địa chỉ">
                    </div>
                    
                    
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor"   id="editor1" name="description" >
                            <?php echo $customer->description;?>
                        </textarea>
                    </div>
                   
                    
                    
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                              
                                <option value ="active" <?php echo e($customer->status=='active'?'selected':''); ?>>Active</option>
                                <option value = "inactive" <?php echo e($customer->status =='inactive'?'selected':''); ?>>Inactive</option>
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
           <!-- end form -->
             
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>


 
<script src="<?php echo e(asset('js/js/ckeditor.js')); ?>"></script>
<script>
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor1' ), 
        {
            ckfinder: {
                uploadUrl: '<?php echo e(route("upload.ckeditor")."?_token=".csrf_token()); ?>'
                }
            
                ,
                mediaEmbed: {previewsInData: true}
        })

        .then( editor => {
            console.log( editor );
        })
        .catch( error => {
            console.error( error );
        })

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/customers/edit.blade.php ENDPATH**/ ?>