
<?php $__env->startSection('scriptop'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh người dùng
        </h2>
    </div>
  
    <div class="grid grid-cols-12 gap-12 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
             <!-- BEGIN: Form Layout -->
            
             <form method="post" action="<?php echo e(route('user.update',$user->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('patch'); ?>
                <div class="intro-y box p-5">
                    
                    <div>
                        <label for="regular-form-1" class="form-label">Tên</label>
                        <input id="title" name="full_name" type="text" value="<?php echo e($user->full_name); ?>" class="form-control" placeholder="tên" required>
                    </div>
                    <!-- upload photo -->
                    <div class="mt-3">
                    <label for="" class="form-label">Photo</label>
                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                            <div data-single="true" id="mydropzone" class="dropzone  "    url="<?php echo e(route('upload.avatar')); ?>" >
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                        
                                </div>
                            </div>
                             
                        </div>
                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                                <?php
                                    $photos = explode( ',', $user->photo);
                                ?>
                                <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div data-photo="<?php echo e($photo); ?>" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md "   src="<?php echo e($photo); ?>">
                                    <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="btn_remove w-4 h-4"></i> </div>  
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                               
                                <input type="hidden" id="photo_old" name="photo_old"/>
                                 
                        </div>
                        <input type="hidden" id="photo" name="photo"/>
                    </div>
                    <!-- end upload photo -->
                    <?php if(auth()->user()->role=="admin"): ?>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Điện thoại</label>
                        <input id="phone" value="<?php echo e($user->phone); ?>" name="phone" type="text" class="form-control" placeholder="điện thoại" required>
                        <div class="form-help">Chỉ admin mới có quyền thay đổi. Tuy nhiên số điện thoại không được trùng.</div>
                    </div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Địa chỉ</label>
                        <input id="address" name="address" value="<?php echo e($user->address); ?>"  type="text" class="form-control" placeholder="địa chỉ" required>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Email</label>
                        <input id="email" name="email" value="<?php echo e($user->email); ?>" type="text" class="form-control" placeholder="email">
                        
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Password</label>
                        <input id="password" name="password" type="text" class="form-control" placeholder="password">
                        <div class="form-help">Để trống nếu không reset mật khẩu</div>
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Mô tả</label>
                       
                        <textarea class="editor"   id="editor1" name="description" >
                            <?php echo $user->description;?>
                        </textarea>
                    </div>
                   
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="">Vai trò</label><br/>
                            <select name="role"  class="form-select mt-2 sm:mr-2"   >
                                <?php $__currentLoopData = $uroles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e($user->role==$role->alias?'selected':''); ?> value ="<?php echo e($role->alias); ?>"> <?php echo e($role->title); ?> </option> 
                                
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                            </select>
                        </div>
                    </div>
                   <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label" for="status">Nhóm người dùng</label><br/>
                            <select name="ugroup_id"  class="form-select mt-2 sm:mr-2"   >
                                
                                <?php $__currentLoopData = $ugroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ugroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value ="<?php echo e($ugroup->id); ?>" <?php echo e($ugroup->id == $user->ugroup_id?'selected':''); ?>> <?php echo e($ugroup->title); ?> </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex flex-col sm:flex-row items-center">
                            <label style="min-width:70px  " class="form-select-label"  for="status">Tình trạng</label>
                           
                            <select name="status" class="form-select mt-2 sm:mr-2"   >
                                <option value ="active" <?php echo e($user->status=='active'?'selected':''); ?>>Active</option>
                                <option value = "inactive" <?php echo e($user->status =='inactive'?'selected':''); ?>>Inactive</option>
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

 
<script>
    $(".btn_remove").click(function(){
        $(this).parent().parent().remove();   
        var link_photo = "";
        $('.product_photo').each(function() {
            if (link_photo != '')
            {
            link_photo+= ',';
            }   
            link_photo += $(this).data("photo");
        });
        $('#photo_old').val(link_photo);
    });

 
                // previewsContainer: ".dropzone-previews",
    Dropzone.instances[0].options.multiple = true;
    Dropzone.instances[0].options.autoQueue= true;
    Dropzone.instances[0].options.maxFilesize =  1; // MB
    Dropzone.instances[0].options.maxFiles =5;
    Dropzone.instances[0].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[0].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
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
        {
            var value_link = $('#photo').val();
            if(value_link != "")
            {
                value_link += ",";
            }
            value_link += response.link;
            $('#photo').val(value_link);
        }
           
        // console.log('File success successfully!' +$('#photo').val());
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp1\htdocs\shop4\resources\views/backend/users/edit.blade.php ENDPATH**/ ?>