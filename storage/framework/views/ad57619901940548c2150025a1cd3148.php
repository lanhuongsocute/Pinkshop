
<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<div class = 'content'>
<?php echo $__env->make('backend.layouts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Điều chỉnh setting
        </h2>
    </div>
    <form method="post" action="<?php echo e(route('setting.update',$setting->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('patch'); ?>
        <div class="grid grid-cols-12 gap-12 mt-5">
            <div class="intro-y px-5 col-span-6 lg:col-span-6">
            <!-- BEGIN: Form Layout -->
                <div class="intro-y box p-5">
                <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tiêu đề web</label>
                        <input id="web_title" name="web_title" type="text" value="<?php echo e($setting->web_title); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tên công ty</label>
                        <input id="company_name" name="company_name" type="text" value="<?php echo e($setting->company_name); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Điện thoại</label>
                        <input id="phone" name="phone" type="text" value="<?php echo e($setting->phone); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Địa chỉ</label>
                        <input id="address" name="address" type="text" value="<?php echo e($setting->address); ?>" class="form-control" placeholder="address">
                    </div >
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Email nhận thuế</label>
                        <input id="email" name="email" type="text" value="<?php echo e($setting->email); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Tên ngắn</label>
                        <input id="short_name" name="short_name" type="text" value="<?php echo e($setting->short_name); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Site Url</label>
                        <input id="site_url" name="site_url" type="text" value="<?php echo e($setting->site_url); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">MST</label>
                        <input id="mst" name="mst" type="text" value="<?php echo e($setting->mst); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Miên tả ngắn</label>
                        <input id="memory" name="memory" type="text" value="<?php echo e($setting->memory); ?>" class="form-control" placeholder="memory">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Keywords</label>
                        <input id="keyword" name="keyword" type="text" value="<?php echo e($setting->keyword); ?>" class="form-control" placeholder="từ khóa cách nhau dấu phẩy">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Facebook</label>
                        <input id="facebook" name="facebook" type="text" value="<?php echo e($setting->facebook); ?>" class="form-control" placeholder="facebook">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Hotline</label>
                        <input id="hotline" name="hotline" type="text" value="<?php echo e($setting->hotline); ?>" class="form-control" placeholder="hotline">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">itcctv email</label>
                        <input id="itcctv_email" name="itcctv_email" type="text" value="<?php echo e($setting->itcctv_email); ?>" class="form-control" placeholder="hotline">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">itcctv_pass</label>
                        <input id="itcctv_pass" name="itcctv_pass" type="text" value="<?php echo e($setting->itcctv_pass); ?>" class="form-control" placeholder="hotline">
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Thông tin thanh toán</label>
                       
                        <textarea class="editor" name="paymentinfo" id="editor1"  >
                            <?php echo e($setting->paymentinfo); ?>

                        </textarea>
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">thời gian đăng ký</label>
                        <input id="itcctv_pass" name="thoigiandk" type="text" value="<?php echo e($setting->thoigiandk); ?>" class="form-control" placeholder="hotline">
                    </div>
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">người liên hệ:</label>
                        <input id="itcctv_pass" name="nguoilienhe" type="text" value="<?php echo e($setting->nguoilienhe); ?>" class="form-control" placeholder="hotline">
                    </div>
                    <div class="mt-3">
                        
                        <label for="" class="form-label">Logo chứng nhận bộ công thương</label>
                       
                        <textarea class="editor" name="logo_bct" id="editor2"  >
                            <?php echo e($setting->logo_bct); ?>

                        </textarea>
                    </div>
                </div>
            
            </div>
            <div class="intro-y px-5 col-span-6 lg:col-span-6">
            <!-- BEGIN: Form Layout -->
           
                <div class="intro-y box p-5">
                    
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Shopee</label>
                        <input id="shopee" name="shopee" type="text" value="<?php echo e($setting->shopee); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    <div class="mt-3">
                        <label for="regular-form-1" class="form-label">Lazada</label>
                        <input id="lazada" name="lazada" type="text" value="<?php echo e($setting->lazada); ?>" class="form-control" placeholder="computer_name">
                    </div>
                    
                    
                    <div class="mt-3">
                        <label for="" class="form-label">Logo (170 x 30)</label>
                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                            <div data-single="true" id="mylogo" class="dropzone  "    url="<?php echo e(route('upload.avatar')); ?>" >
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                        
                                </div>
                            </div>
                             
                        </div>
                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                                <?php
                                    $photos = explode( ',', $setting->logo);
                                ?>
                                 
                                <div data-photo="<?php echo e($setting->logo); ?>" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md "   src="<?php echo e($setting->logo); ?>">
                                    <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="btn_remove w-4 h-4"></i> </div>  
                                </div>
                                
                               
                                <input type="hidden" id="logo_old" name="logo_old"/>
                                 
                        </div>
                        <input type="hidden" id="logo" name="logo" value="<?php echo e($setting->logo); ?>"/>
                    </div>
                    
                    <div class="mt-3">
                        <label for="" class="form-label">Icon (16x16)</label>
                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                            <div data-single="true" id="mymap" class="dropzone  "    url="<?php echo e(route('upload.avatar')); ?>" >
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                        
                                </div>
                            </div>
                             
                        </div>
                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                                <?php
                                    $photos = explode( ',', $setting->icon);
                                ?>
                                 
                                <div data-photo="<?php echo e($setting->icon); ?>" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md "   src="<?php echo e($setting->icon); ?>">
                                    <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="btn_remove w-4 h-4"></i> </div>  
                                </div>
                                
                               
                                <input type="hidden" id="icon_old" name="icon_old"/>
                                 
                        </div>
                        <input type="hidden" id="icon" name="icon" value="<?php echo e($setting->icon); ?>"/>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Bản đồ</label>
                        <div class="px-4 pb-4 mt-5 flex items-center  cursor-pointer relative">
                            <div data-single="true" id="mymap" class="dropzone  "    url="<?php echo e(route('upload.avatar')); ?>" >
                                <div class="fallback"> <input name="file" type="file" /> </div>
                                <div class="dz-message" data-dz-message>
                                    <div class=" font-medium">Kéo thả hoặc chọn ảnh.</div>
                                        
                                </div>
                            </div>
                             
                        </div>
                        <div class="grid grid-cols-10 gap-5 pl-4 pr-5 py-5">
                                <?php
                                    $photos = explode( ',', $setting->map);
                                ?>
                                 
                                <div data-photo="<?php echo e($setting->map); ?>" class="product_photo col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">
                                    <img class="rounded-md "   src="<?php echo e($setting->map); ?>">
                                    <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="btn_remove w-4 h-4"></i> </div>  
                                </div>
                                
                               
                                <input type="hidden" id="map_old" name="map_old"/>
                                 
                        </div>
                        <input type="hidden" id="map" name="map" value="<?php echo e($setting->map); ?>"/>
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
            
            </div>
       
        </div>
    </form>
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
 
 
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script> -->
<script>
    

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
            $('#logo').val(response.link);
        }
    });
    Dropzone.instances[0].on("removedfile", function (file ) {
            $('#logo').val('');
        console.log('File removed successfully!'  );
    });
    Dropzone.instances[0].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);
       
        console.log('error !' +message);
    });
     console.log(Dropzone.instances[0].options   );
 


    Dropzone.instances[1].options.multiple = true;
    Dropzone.instances[1].options.autoQueue= true;
    Dropzone.instances[1].options.maxFilesize =  1; // MB
    Dropzone.instances[1].options.maxFiles =1;
    Dropzone.instances[1].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[1].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
                                           +' </div>';
    // Dropzone.instances[1].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
    Dropzone.instances[1].options.addRemoveLinks =  true;
    Dropzone.instances[1].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
 
    Dropzone.instances[1].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
    });
    Dropzone.instances[1].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
        {
            $('#icon').val(response.link);
            
        }
           
        // console.log('File success successfully!' +$('#photo').val());
    });
    Dropzone.instances[1].on("removedfile", function (file ) {
            $('#icon').val('');
        console.log('File removed successfully!'  );
    });
    Dropzone.instances[1].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);
       
        console.log('error !' +message);
    });
     console.log(Dropzone.instances[1].options   );
     

     Dropzone.instances[2].options.multiple = true;
    Dropzone.instances[2].options.autoQueue= true;
    Dropzone.instances[2].options.maxFilesize =  1; // MB
    Dropzone.instances[2].options.maxFiles =1;
    Dropzone.instances[2].options.acceptedFiles= "image/jpeg,image/png,image/gif";
    Dropzone.instances[2].options.previewTemplate =  '<div class="col-span-5 md:col-span-2 h-28 relative image-fit cursor-pointer zoom-in">'
                                               +' <img    data-dz-thumbnail >'
                                               +' <div title="Xóa hình này?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="octagon"   data-dz-remove> x </i> </div>'
                                           +' </div>';
    // Dropzone.instances[2].options.previewTemplate =  '<li><figure><img data-dz-thumbnail /><i title="Remove Image" class="icon-trash" data-dz-remove ></i></figure></li>';      
    Dropzone.instances[2].options.addRemoveLinks =  true;
    Dropzone.instances[2].options.headers= {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
 
    Dropzone.instances[2].on("addedfile", function (file ) {
        // Example: Handle success event
        console.log('File addedfile successfully!' );
    });
    Dropzone.instances[2].on("success", function (file, response) {
        // Example: Handle success event
        // file.previewElement.innerHTML = "";
        if(response.status == "true")
        {
            $('#map').val(response.link);
        }
           
        // console.log('File success successfully!' +$('#photo').val());
    });
    Dropzone.instances[2].on("removedfile", function (file ) {
            $('#map').val('');
        console.log('File removed successfully!'  );
    });
    Dropzone.instances[2].on("error", function (file, message) {
        // Example: Handle success event
        file.previewElement.innerHTML = "";
        console.log(file);
       
        console.log('error !' +message);
    });
     console.log(Dropzone.instances[2].options   );
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\shop4\resources\views/backend/setting/edit.blade.php ENDPATH**/ ?>