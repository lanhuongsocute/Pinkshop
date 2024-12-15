
<?php $__env->startSection('scriptop'); ?>
 
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
           Thông tin sản phẩm
        </h2>
    </div>
                <div class="grid grid-cols-12 gap-6">
                    <!-- BEGIN: Profile Menu -->
                    <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                    <?php
                                $photos = explode( ',', $product->photo);
                    ?>
                        <div class="intro-y box mt-5">
                            <div class="relative flex items-center p-5">
                            <div class="mx-6"> 
                                <div class="single-item"> 
                                     <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="h-32 px-2"> 
                                            <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md"> 
                                            <img    src="<?php echo e($photo); ?>"/>
                                            </div> 
                                        </div> 
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div> 
                            </div> 
                          
                            </div>
                        </div>
                    </div>
                    <!-- END: Profile Menu -->
                    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                        <!-- BEGIN: Display Information -->
                        <div class="intro-y box lg:mt-5">
                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">
                                    <?php echo e($product->title); ?>

                                </h2>
                            </div>
                            <div class="p-5">
                                <div class="flex flex-col-reverse xl:flex-row flex-col">
                                    <div class="flex-1 mt-6 xl:mt-0">
                                        <div class="grid grid-cols-12 gap-x-5">
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Tồn kho:</label>
                                                    <?php echo e($product->stock); ?>

                      
                                                </div>
                                                
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Giá nhập tb:</label>
                                                    <?php echo e(number_format($product->price_in,0,".",",")); ?> 
                      
                                                </div>
                                                
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Giá vốn:</label>
                                                     <?php echo e(number_format($product->price_avg,0,".",",")); ?>

                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label  class="font-medium  form-label">Giá bán tb:</label>
                                                    <?php echo e(number_format($product->price_out,0,".",",")); ?>

                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label  class="font-medium  form-label">Giá niêm yết website:</label>
                                                    <?php echo e(number_format($product->price,0,".",",")); ?>  
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label  class="font-medium  form-label">Bảo hành: </label>
                                                    <?php echo e($product->expired); ?>

                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label   class=" font-medium  form-label">Loại sản phẩm: </label>
                                                   <?php echo e($product->type); ?>

                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-3">
                                                <div>
                                                    <label class="font-medium  form-label">Trạng thái:</label>
                                                   <?php echo e($product->status); ?>

                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label   class="font-medium form-label">Danh mục: </label>
                                                    <?php echo e($product->cat_id!=null ? \App\Models\Category::where('id',$product->cat_id)->value('title'):''); ?>   
                     
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-6">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Nhà sản xuất:</label>
                                                    <?php echo e($product->brand_id!=null ? \App\Models\Brand::where('id',$product->brand)->value('title'):''); ?>   
                     
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Mô tả ngắn:</label>
                                                    <p>
                                                    <?php echo $product->summary ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-span-12 2xl:col-span-12">
                                                <div>
                                                    <label for="update-profile-form-1" class="font-medium  form-label">Mô tả:</label>
                                                    <p>
                                                    <?php echo $product->description?>
                                                     
                                                    </p>
                                                </div>
                                            </div>
                                           
                                        </div>
                                         
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- END: Display Information -->
                        <!-- BEGIN: Personal Information -->
                     
                        <!-- END: Personal Information -->
                    </div>
                </div>
        
     
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
  
 
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/products/show.blade.php ENDPATH**/ ?>