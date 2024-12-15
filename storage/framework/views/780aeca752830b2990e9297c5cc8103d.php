
<?php $__env->startSection('scriptop'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thêm người dùng
        </h2>
    </div>
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Layout -->
            <form method="post" action="<?php echo e(route('user.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="intro-y box p-5">
                    <div>
                        <label for="regular-form-1" class="form-label">Tên</label>
                        <input id="title" name="full_name" type="text" class="form-control" placeholder="tên" required>
                    </div>
                    <div class="mt-3">
                    <label for="" class="form-label">Photo</label>
                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone  "    url="<?php echo e(route('upload.avatar')); ?>" >
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                        
                                </div>
                            </div>
                            <input type="hidden" id="photo" name="photo"/>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Điện thoại</label>
                        <input id="phone" name="phone" type="text" class="form-control" placeholder="điện thoại" required>
                        <div class="form-help">Kiểm tra lại số điện thoại, thông tin nãy sẽ không được chỉnh sửa sau khi hoàn thành việc thêm mới.</div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label" >Địa chỉ</label>
                        <input id="address" name="address" type="text" class="form-control" placeholder="địa chỉ" required>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Email</label>
                        <input id="email" name="email" type="text" class="form-control" placeholder="email">
                        <div class="form-help">Để trống nếu lấy số điện thoại làm email.</div>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Password</label>
                        <input id="password" name="password" type="text" class="form-control" placeholder="password">
                        <div class="form-help">Để trống nếu lấy số điện thoại làm mật khẩu.</div>
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor"  id="editor1" name="description" >
                            <?php echo e(old('description')); ?>

                        </textarea>
                    </div>
                   
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="">Vai trò</label><br/>
                            <select name="role"  class="form-select mt-2 sm:mr-2"   >
                                
                                <?php $__currentLoopData = $uroles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value ="<?php echo e($role->alias); ?>"> <?php echo e($role->title); ?> </option> 
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="status">Nhóm người dùng</label><br/>
                            <select name="ugroup_id"  class="form-select mt-2 sm:mr-2"   >
                                
                                <?php $__currentLoopData = $ugroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ugroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value ="<?php echo e($ugroup->id); ?>"> <?php echo e($ugroup->title); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
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

 
<script>
        //  Dropzone.autoDiscover = false;
    
    // Dropzone class:
    // var myDropzone = new Dropzone("div#mydropzone", { url: "<?php echo e(route('upload.avatar')); ?>"});
        // previewsContainer: ".dropzone-previews",
        // Dropzone.instances[0].options.url = "<?php echo e(route('upload.avatar')); ?>";
        Dropzone.instances[0].options.multiple = false;
        Dropzone.instances[0].options.autoQueue= true;
        Dropzone.instances[0].options.maxFilesize =  1; // MB
        Dropzone.instances[0].options.maxFiles =1;
        Dropzone.instances[0].options.dictDefaultMessage = 'Drop images anywhere to upload (6 images Max)';
        Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
        Dropzone.instances[0].options.previewTemplate =  '<div class=" d-flex flex-column  position-relative">'
                                        +' <img    data-dz-thumbnail >'
                                        
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

<script src="<?php echo e(asset('js/js/ckeditor.js')); ?>"></script>
<script>
     
        // CKSource.Editor
        ClassicEditor.create( document.querySelector( '#editor1' ), 
        {
            ckfinder: {
                uploadUrl: '<?php echo e(route("upload.ckeditor")."?_token=".csrf_token()); ?>'
                },
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
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/users/create.blade.php ENDPATH**/ ?>