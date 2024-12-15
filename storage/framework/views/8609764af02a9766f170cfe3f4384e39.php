
<?php $__env->startSection('scriptop'); ?>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link rel="stylesheet" href="<?php echo e(asset('vendor/file-manager/css/file-manager.css')); ?>">
<script src="<?php echo e(asset('vendor/file-manager/js/file-manager.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh thông tin cá nhân
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="<?php echo e(route('user.profileupdate' )); ?>">
                <?php echo csrf_field(); ?>
                
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tên đầy đủ</label>
                        <input id="title" name="full_name" type="text" value="<?php echo e($user->full_name); ?>" class="form-control" placeholder="" required>
                    </div>
                    <div class="mt-3">
                    <label for="regular-form-1" class="form-label">Avatar</label>
                        <div class="   ">
                            <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                                <div data-single="true" class="dropzone  "    url="<?php echo e(route('upload.avatar')); ?>" >
                                    <div class="fallback"> <input name="file" type="file" /> </div>
                                    <div class="dz-message" data-dz-message>
                                        <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                            
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                                <?php if($user->photo): ?>
                                <div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md "   src="<?php echo e($user->photo); ?>">
                                    
                                </div>
                                <?php endif; ?>
                                 
                        </div>
                        <input type="hidden" id="photo" name="photo"/>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Địa chỉ</label>
                        <input id="address" name="address" type="text" value="<?php echo e($user->full_name); ?>" class="form-control" placeholder="địa chỉ" required>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Mật khẩu hiện tại</label>
                        <input id="current_password" name="current_password" type="password" value="" class="form-control" placeholder="">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Mật khẩu mới</label>
                        <input id="new_password" name="new_password" type="password" value="" class="form-control" placeholder="">
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
                        <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
 
                // previewsContainer: ".dropzone-previews",
    Dropzone.instances[0].options.multiple = false;
    Dropzone.instances[0].options.autoQueue= true;
    Dropzone.instances[0].options.maxFilesize =  1; // MB
    Dropzone.instances[0].options.maxFiles =1;
    Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (6 images Max)';
    Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Remove this image?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
                                           +' </div>';
    // Dropzone.instances[0].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
    Dropzone.instances[0].options.addRemoveLinks =  true;
    Dropzone.instances[0].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
 
    Dropzone.instances[0].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
    });
    Dropzone.instances[0].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
            $('#photo').val(response.link);
        console.log('File success successfully!' +response.link);
    });
    Dropzone.instances[0].on("removedfile", function (file ) {
            $('#photo').val('');
        console.log('File removed successfully!'  );
    });
    Dropzone.instances[0].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);
       
        console.log('error !' +message);
    });
     console.log(Dropzone.instances[0].options   );
 
    // console.log(Dropzone.optionsForElement);
 
</script>

<!-- <script src="<?php echo e(asset('backend/assets/dist/js/ckeditor-classic.js')); ?>"></script> -->
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    // $('#lfm').filemanager('image');
</script>
 

<script>
        // document.addEventListener("DOMContentLoaded", function() {

        // document.getElementById('button-image').addEventListener('click', (event) => {
        // event.preventDefault();

        // window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
        // });
        // });

        // // set file link
        // function fmSetLink($url) {
        // document.getElementById('image_label').value = $url;
        // document.getElementById('holder').innerHTML = '<img src = "'+ $url +'" width ="100"/>';
        // }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\KhoaLuan_2024\shop\resources\views/backend/users/profile.blade.php ENDPATH**/ ?>